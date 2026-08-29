<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Device;
use App\Services\Billing\BillingService;
use Illuminate\Support\Facades\Log;

class CheckRouterHealth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-router-health';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pings all registered MikroTik routers to update their online status and last seen timestamp.';

    /**
     * Execute the console command.
     */
    public function handle(BillingService $billingService)
    {
        $devices = Device::all();

        $this->info("Checking health for {$devices->count()} routers...");

        foreach ($devices as $device) {
            $this->info("Pinging {$device->name} ({$device->ip_address})...");

            try {
                // The resolveDeviceDriver method attempts a connection and throws an Exception if it fails.
                $driver = $billingService->resolveDeviceDriver($device);
                
                // If we get here, connection was successful
                $device->update([
                    'status' => 'active',
                    'last_seen_at' => now(),
                ]);
                
                $this->info("✓ Router is ONLINE");

            } catch (\Exception $e) {
                // Connection failed
                $device->update([
                    'status' => 'offline',
                ]);
                
                $this->error("✗ Router is OFFLINE: " . $e->getMessage());
                Log::warning("Router Health Check Failed", [
                    'device_id' => $device->id,
                    'name' => $device->name,
                    'error' => $e->getMessage()
                ]);
            }
        }

        $this->info('Health checks completed.');
    }
}
