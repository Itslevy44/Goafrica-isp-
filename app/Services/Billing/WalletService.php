<?php

namespace App\Services\Billing;

use App\Models\Tenant;
use App\Models\PayoutAccount;
use App\Models\Settlement;
use App\Models\WalletLedgerEntry;
use Illuminate\Support\Facades\DB;
use Exception;

class WalletService
{
    /**
     * Get current wallet balance of the tenant (ISP).
     */
    public function getBalance(Tenant $tenant): int
    {
        $latest = WalletLedgerEntry::where('tenant_id', $tenant->id)
            ->orderBy('id', 'desc')
            ->first();

        return $latest ? $latest->balance_after_minor : 0;
    }

    /**
     * Record a transaction in the append-only ledger.
     */
    public function recordEntry(
        Tenant $tenant, 
        string $entryType, 
        int $amountMinor, 
        string $currency, 
        ?string $referenceType = null, 
        ?int $referenceId = null
    ): WalletLedgerEntry {
        return DB::transaction(function () use ($tenant, $entryType, $amountMinor, $currency, $referenceType, $referenceId) {
            // Find latest balance with lock
            $latest = WalletLedgerEntry::where('tenant_id', $tenant->id)
                ->lockForUpdate()
                ->orderBy('id', 'desc')
                ->first();

            $prevBalance = $latest ? $latest->balance_after_minor : 0;
            
            // Calculate new balance
            if ($entryType === 'sale_credit' || $entryType === 'adjustment') {
                $newBalance = $prevBalance + $amountMinor;
            } elseif ($entryType === 'payout_debit') {
                $newBalance = $prevBalance - $amountMinor;
            } else {
                throw new Exception("Invalid ledger entry type: {$entryType}");
            }

            return WalletLedgerEntry::create([
                'tenant_id' => $tenant->id,
                'entry_type' => $entryType,
                'amount_minor' => $amountMinor,
                'currency' => $currency,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'balance_after_minor' => $newBalance
            ]);
        });
    }

    /**
     * Process an ISP withdrawal request to their payout account.
     */
    public function withdraw(Tenant $tenant, PayoutAccount $payoutAccount, int $amountMinor): Settlement
    {
        return DB::transaction(function () use ($tenant, $payoutAccount, $amountMinor) {
            $currentBalance = $this->getBalance($tenant);
            if ($amountMinor <= 0) {
                throw new Exception("Withdrawal amount must be greater than zero.");
            }
            if ($currentBalance < $amountMinor) {
                throw new Exception("Insufficient wallet balance.");
            }

            // Create pending settlement record
            $settlement = Settlement::create([
                'tenant_id' => $tenant->id,
                'payout_account_id' => $payoutAccount->id,
                'gross_amount_minor' => $amountMinor,
                'commission_amount_minor' => 0,
                'net_amount_minor' => $amountMinor,
                'currency' => $tenant->default_currency,
                'status' => 'pending',
                'initiated_at' => now(),
            ]);

            try {
                // Generate simulated payout gateway reference
                $gatewayRef = 'WDR_' . uniqid();
                
                // Update settlement status
                $settlement->update([
                    'status' => 'paid',
                    'gateway_ref' => $gatewayRef,
                    'paid_at' => now()
                ]);

                // Write debit entry to ledger
                $this->recordEntry(
                    $tenant,
                    'payout_debit',
                    $amountMinor,
                    $tenant->default_currency,
                    'settlement',
                    $settlement->id
                );

            } catch (Exception $e) {
                $settlement->update([
                    'status' => 'failed',
                ]);
                throw $e;
            }

            return $settlement;
        });
    }
}
