<?php

namespace App\Services\Devices;

use App\Services\Devices\Contracts\DeviceDriverInterface;
use Illuminate\Support\Facades\Log;

class MockDeviceDriver implements DeviceDriverInterface
{
    protected string $host;
    protected int $port;
    protected array $credentials;

    /**
     * Connect simulation.
     */
    public function connect(string $host, int $port, array $credentials): bool
    {
        $this->host = $host;
        $this->port = $port;
        $this->credentials = $credentials;
        return true;
    }

    /**
     * Run simulated terminal command.
     */
    public function runCommand(string $command): string
    {
        Log::info("Mock command run on {$this->host}: {$command}");
        
        $command = trim(strtolower($command));
        if ($command === 'reboot') {
            return "Rebooting RouterOS kernel... Success";
        } elseif ($command === 'monitor') {
            return "CPU Load: 8%\nFree Memory: 184.2 MB / 256.0 MB\nUptime: 14d 2h 45m\nActive Hotspot Users: 9";
        } elseif ($command === 'logs') {
            return "[2026-07-25 11:00] hotspot,info 11:22:33:44:55:66 (192.168.88.50): logged in\n[2026-07-25 11:05] dhcp,info lease assigned 192.168.88.50 to 11:22:33:44:55:66\n[2026-07-25 11:15] system,info,account user admin logged in from 192.168.88.10 via api";
        } elseif ($command === 'users') {
            return "MAC: 11:22:33:44:55:66 | IP: 192.168.88.50 | Active Time: 15m | Bytes: 4.8MB\nMAC: AA:BB:CC:DD:EE:FF | IP: 192.168.88.61 | Active Time: 1h 10m | Bytes: 104.2MB";
        }

        return "Command executed successfully.\nDevice response: OK (Simulated)";
    }

    /**
     * Whitelist client MAC.
     */
    public function createHotspotUser(string $macAddress, int $durationMinutes, ?string $rateLimit = null): bool
    {
        Log::info("Mock Router whitelisted MAC {$macAddress} for {$durationMinutes} minutes with speed cap {$rateLimit}");
        return true;
    }

    /**
     * Disconnect client MAC.
     */
    public function disconnectUser(string $macAddress): bool
    {
        Log::info("Mock Router terminated session for MAC {$macAddress}");
        return true;
    }

    /**
     * Status check simulation.
     */
    public function checkStatus(): bool
    {
        return true;
    }
}
