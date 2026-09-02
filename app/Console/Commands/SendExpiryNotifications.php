<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use App\Models\User;
use App\Notifications\SubscriptionExpiryNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendExpiryNotifications extends Command
{
    protected $signature   = 'notifications:send-expiry';
    protected $description = 'Send subscription expiry email & dashboard notifications to ISPs whose subscriptions are expiring soon or have just expired.';

    // How many days before expiry to notify (sends once per threshold)
    private array $thresholds = [7, 3, 1, 0];

    public function handle(): void
    {
        $this->info('Checking subscription expiry notifications...');

        $notified = 0;

        foreach ($this->thresholds as $days) {
            // Find tenants whose subscription ends exactly N days from today (±12 hours window)
            if ($days > 0) {
                $tenants = Tenant::whereBetween('subscription_ends_at', [
                    now()->addDays($days)->startOfDay(),
                    now()->addDays($days)->endOfDay(),
                ])->get();
            } else {
                // Expired: ended in the last 24 hours
                $tenants = Tenant::whereBetween('subscription_ends_at', [
                    now()->subDay()->startOfDay(),
                    now()->startOfDay(),
                ])->get();
            }

            foreach ($tenants as $tenant) {
                // Notify all admin users of this tenant
                $admins = User::where('tenant_id', $tenant->id)
                    ->whereIn('role', ['admin'])
                    ->get();

                if ($admins->isEmpty()) {
                    // Fall back: notify by tenant email directly (no user account)
                    $this->line("  No admin users for tenant {$tenant->name} — skipping.");
                    continue;
                }

                foreach ($admins as $admin) {
                    try {
                        $admin->notify(new SubscriptionExpiryNotification($days, $tenant->name));
                        $notified++;
                        $this->line("  ✓ Notified {$admin->email} ({$tenant->name}) — {$days} days left");
                    } catch (\Exception $e) {
                        Log::error("Failed to send expiry notification to {$admin->email}: " . $e->getMessage());
                        $this->error("  ✗ Failed: {$admin->email} — " . $e->getMessage());
                    }
                }
            }
        }

        $this->info("Done. {$notified} notification(s) sent.");
    }
}
