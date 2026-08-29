<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\InternetSession;
use App\Services\Devices\MikroTikDriver;
use Illuminate\Support\Facades\Crypt;

class CleanupSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sessions:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks for expired internet sessions and disconnects them from the router';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredSessions = InternetSession::where('ends_at', '<', now())
            ->where('status', 'active')
            ->get();

        $this->info("Found {$expiredSessions->count()} expired sessions.");

        foreach ($expiredSessions as $session) {
            $device = $session->device;
            if ($device && $device->type === 'mikrotik') {
                try {
                    $credentials = json_decode(Crypt::decryptString($device->credentials_encrypted), true);
                    
                    $driver = new MikroTikDriver();
                    $connected = $driver->connect($device->ip_address, $device->api_port, $credentials);
                    
                    if ($connected) {
                        $driver->disconnectUser($session->mac_address);
                        $session->update(['status' => 'expired']);
                        $this->info("Terminated session for MAC: {$session->mac_address}");
                    } else {
                        $this->error("Failed to connect to router IP: {$device->ip_address} for MAC {$session->mac_address}");
                    }
                } catch (\Exception $e) {
                    $this->error("Exception terminating session for MAC {$session->mac_address}: " . $e->getMessage());
                }
            } else {
                $session->update(['status' => 'expired']);
                $this->info("Marked session as expired for MAC: {$session->mac_address} (No device linked)");
            }
        }
    }
}
