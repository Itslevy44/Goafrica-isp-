<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\InternetSession;
use App\Services\Billing\BillingService;
use Illuminate\Support\Facades\Log;

class CleanupSessions extends Command
{
    protected $signature   = 'sessions:cleanup';
    protected $description = 'Checks for expired internet sessions and disconnects them from the router.';

    public function handle(BillingService $billingService): void
    {
        $expiredSessions = InternetSession::where('ends_at', '<', now())
            ->where('status', 'active')
            ->with('device')
            ->get();

        $this->info("Found {$expiredSessions->count()} expired sessions to clean up.");

        $disconnected = 0;
        $failed       = 0;

        foreach ($expiredSessions as $session) {
            $device = $session->device;

            if ($device) {
                try {
                    $driver = $billingService->resolveDeviceDriver($device);
                    $driver->disconnectUser($session->mac_address);
                    $disconnected++;
                    $this->line("  ✓ Disconnected MAC {$session->mac_address} from {$device->name}");
                } catch (\Exception $e) {
                    $failed++;
                    $this->warn("  ✗ Could not disconnect MAC {$session->mac_address}: {$e->getMessage()}");
                    Log::warning("CleanupSessions: failed to disconnect {$session->mac_address}", [
                        'session_id' => $session->id,
                        'device_id'  => $device->id,
                        'error'      => $e->getMessage(),
                    ]);
                }
            }

            // Mark expired regardless of whether router disconnect succeeded
            $session->update(['status' => 'expired']);
        }

        $this->info("Done. Disconnected: {$disconnected} | Failed: {$failed} | Total: {$expiredSessions->count()}");
    }
}
