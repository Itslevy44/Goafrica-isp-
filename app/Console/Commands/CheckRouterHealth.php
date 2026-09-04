<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Device;
use App\Services\Billing\BillingService;
use Illuminate\Support\Facades\Log;

class CheckRouterHealth extends Command
{
    protected $signature   = 'app:check-router-health';
    protected $description = 'Pings all registered MikroTik routers to update their online status and last seen timestamp.';

    public function handle(BillingService $billingService): void
    {
        // Scope to active devices only — never query Device::all() (crosses tenant boundaries)
        $devices = Device::whereNotNull('tenant_id')->get();

        $this->info("Checking health for {$devices->count()} routers...");

        $online  = 0;
        $offline = 0;

        foreach ($devices as $device) {
            $this->line("  Pinging {$device->name} ({$device->ip_address})...");

            try {
                $billingService->resolveDeviceDriver($device);

                $device->update([
                    'status'       => 'active',
                    'last_seen_at' => now(),
                ]);

                $online++;
                $this->line("    ✓ ONLINE");
            } catch (\Exception $e) {
                $device->update(['status' => 'offline']);
                $offline++;
                $this->warn("    ✗ OFFLINE: {$e->getMessage()}");

                Log::warning('Router health check failed', [
                    'device_id' => $device->id,
                    'tenant_id' => $device->tenant_id,
                    'name'      => $device->name,
                    'error'     => $e->getMessage(),
                ]);
            }
        }

        $this->info("Health checks complete. Online: {$online} | Offline: {$offline}");
    }
}
