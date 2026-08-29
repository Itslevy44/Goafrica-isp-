<?php

namespace App\Services\Devices;

use App\Services\Devices\Contracts\DeviceDriverInterface;
use Illuminate\Support\Facades\Log;
use Exception;

class MikroTikDriver implements DeviceDriverInterface
{
    protected $socket;
    protected bool $connected = false;
    protected string $host;
    protected int $port;
    protected array $credentials;

    /**
     * Connect to RouterOS socket and authenticate.
     */
    public function connect(string $host, int $port, array $credentials): bool
    {
        $this->host = $host;
        $this->port = $port;
        $this->credentials = $credentials;
        
        $username = $credentials['username'] ?? 'admin';
        $password = $credentials['password'] ?? '';

        try {
            $this->socket = @fsockopen($host, $port, $errno, $errstr, 5);
            if (!$this->socket) {
                Log::error("Connection to MikroTik {$host}:{$port} failed: {$errstr} ({$errno})");
                return false;
            }

            stream_set_timeout($this->socket, 5);

            // Authentication challenge is no longer required in ROS v6.43+
            // Cleartext name and password are sent directly
            $response = $this->write('/login', [
                '=name=' . $username,
                '=password=' . $password,
            ]);

            if (isset($response['!trap'])) {
                Log::error("RouterOS Login Trap: " . json_encode($response['!trap']));
                $this->disconnect();
                return false;
            }

            $this->connected = true;
            return true;
        } catch (Exception $e) {
            Log::error("MikroTik Connection Exception: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Run a console/API command.
     */
    public function runCommand(string $command): string
    {
        if (!$this->connected) {
            return "Error: Not connected to MikroTik.";
        }

        // Split raw command from args
        $words = array_map('trim', explode(' ', $command));
        $cmd = array_shift($words);

        $response = $this->write($cmd, $words);
        return $this->formatResponse($response);
    }

    /**
     * Create Hotspot user with duration minutes.
     */
    public function createHotspotUser(string $macAddress, int $durationMinutes, ?string $rateLimit = null): bool
    {
        if (!$this->connected) {
            return false;
        }

        $limitUptime = $durationMinutes . 'm';
        $params = [
            '=name=' . $macAddress,
            '=password=' . $macAddress,
            '=limit-uptime=' . $limitUptime,
        ];
        if ($rateLimit) {
            $params[] = '=rate-limit=' . $rateLimit;
        }

        $res = $this->write('/ip/hotspot/user/add', $params);
        return !isset($res['!trap']);
    }

    /**
     * Terminate hotspot active and user profile lease.
     */
    public function disconnectUser(string $macAddress): bool
    {
        if (!$this->connected) {
            return false;
        }

        // 1. Query and remove active session
        $printRes = $this->write('/ip/hotspot/active/print', [
            '?user=' . $macAddress,
        ]);

        if (isset($printRes['!re'])) {
            foreach ($printRes['!re'] as $activeSession) {
                if (isset($activeSession['.id'])) {
                    $this->write('/ip/hotspot/active/remove', [
                        '=.id=' . $activeSession['.id'],
                    ]);
                }
            }
        }

        // 2. Query and remove hotspot user profile
        $userPrint = $this->write('/ip/hotspot/user/print', [
            '?name=' . $macAddress,
        ]);
        if (isset($userPrint['!re'])) {
            foreach ($userPrint['!re'] as $user) {
                if (isset($user['.id'])) {
                    $this->write('/ip/hotspot/user/remove', [
                        '=.id=' . $user['.id'],
                    ]);
                }
            }
        }

        return true;
    }

    /**
     * Check if router resources are reachable.
     */
    public function checkStatus(): bool
    {
        if (!$this->connected) {
            return false;
        }
        $res = $this->write('/system/resource/print');
        return isset($res['!re']);
    }

    /**
     * Close socket.
     */
    protected function disconnect(): void
    {
        if ($this->socket) {
            @fclose($this->socket);
        }
        $this->connected = false;
    }

    /**
     * Send words in MikroTik protocol.
     */
    protected function write(string $command, array $words = []): array
    {
        try {
            $this->sendWord($command);
            foreach ($words as $word) {
                $this->sendWord($word);
            }
            $this->sendWord(''); // Terminate block

            return $this->readResponse();
        } catch (Exception $e) {
            Log::error("RouterOS Socket write failed: " . $e->getMessage());
            $this->disconnect();
            return ['!trap' => [$e->getMessage()]];
        }
    }

    /**
     * Write single word with RouterOS length prefixing.
     */
    protected function sendWord(string $word): void
    {
        $length = strlen($word);
        if ($length < 0x80) {
            fwrite($this->socket, chr($length));
        } elseif ($length < 0x4000) {
            $length |= 0x8000;
            fwrite($this->socket, chr(($length >> 8) & 0xFF) . chr($length & 0xFF));
        } elseif ($length < 0x200000) {
            $length |= 0xC00000;
            fwrite($this->socket, chr(($length >> 16) & 0xFF) . chr(($length >> 8) & 0xFF) . chr($length & 0xFF));
        } elseif ($length < 0x10000000) {
            $length |= 0xE0000000;
            fwrite($this->socket, chr(($length >> 24) & 0xFF) . chr(($length >> 16) & 0xFF) . chr(($length >> 8) & 0xFF) . chr($length & 0xFF));
        }
        fwrite($this->socket, $word);
    }

    /**
     * Read RouterOS responses.
     */
    protected function readResponse(): array
    {
        $response = [];
        $currentReply = null;

        while (true) {
            $firstByte = fread($this->socket, 1);
            if ($firstByte === false || strlen($firstByte) === 0) {
                break;
            }
            $byte = ord($firstByte);
            $length = 0;
            if (($byte & 0x80) == 0x00) {
                $length = $byte;
            } elseif (($byte & 0xC0) == 0x80) {
                $byte2 = ord(fread($this->socket, 1));
                $length = (($byte & 0x3F) << 8) + $byte2;
            } elseif (($byte & 0xE0) == 0xC0) {
                $byte2 = ord(fread($this->socket, 1));
                $byte3 = ord(fread($this->socket, 1));
                $length = (($byte & 0x1F) << 16) + ($byte2 << 8) + $byte3;
            } elseif (($byte & 0xF0) == 0xE0) {
                $byte2 = ord(fread($this->socket, 1));
                $byte3 = ord(fread($this->socket, 1));
                $byte4 = ord(fread($this->socket, 1));
                $length = (($byte & 0x0F) << 24) + ($byte2 << 16) + ($byte3 << 8) + $byte4;
            }

            if ($length == 0) {
                break; // End of words
            }

            $word = fread($this->socket, $length);
            
            if ($word === '!done') {
                break;
            }

            if (str_starts_with($word, '!')) {
                $currentReply = $word;
            } else {
                if ($currentReply) {
                    if (str_starts_with($word, '=')) {
                        $parts = explode('=', substr($word, 1), 2);
                        $response[$currentReply][][$parts[0]] = $parts[1] ?? '';
                    }
                }
            }
        }

        return $response;
    }

    /**
     * Format response keys into stdout style string output.
     */
    protected function formatResponse(array $response): string
    {
        if (isset($response['!trap'])) {
            return "Trap Error: " . json_encode($response['!trap']);
        }

        $output = "";
        foreach ($response as $key => $values) {
            $output .= "Reply [{$key}]:\n";
            foreach ($values as $item) {
                foreach ($item as $k => $v) {
                    $output .= "  {$k}: {$v}\n";
                }
                $output .= "\n";
            }
        }
        return empty($output) ? "Done (OK)" : trim($output);
    }
}
