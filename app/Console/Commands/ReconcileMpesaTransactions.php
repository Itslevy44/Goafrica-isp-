<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use App\Services\Payments\MpesaGateway;
use App\Services\Billing\BillingService;
use Illuminate\Support\Facades\Log;

class ReconcileMpesaTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mpesa:reconcile';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Queries Safaricom for pending M-Pesa transactions and reconciles them.';

    /**
     * Execute the console command.
     */
    public function handle(BillingService $billingService)
    {
        $this->info("Starting M-Pesa reconciliation...");

        // Fetch pending transactions that were created between 3 minutes ago and 2 hours ago
        // (Wait 3 minutes to give the normal webhook time to arrive first)
        $pendingTransactions = Transaction::where('status', 'pending')
            ->where('gateway_name', 'mpesa')
            ->whereNotNull('gateway_transaction_id') // CheckoutRequestID
            ->where('created_at', '<', now()->subMinutes(3))
            ->where('created_at', '>', now()->subHours(2))
            ->get();

        if ($pendingTransactions->isEmpty()) {
            $this->info("No pending M-Pesa transactions found to reconcile.");
            return;
        }

        $this->info("Found {$pendingTransactions->count()} pending transactions.");

        foreach ($pendingTransactions as $transaction) {
            $this->line("Reconciling Transaction ID: {$transaction->id}...");

            // Temporarily set the tenant context to the transaction's tenant
            // so MpesaGateway uses the right Till Number if needed
            app()->instance('currentTenant', $transaction->tenant);
            $mpesaGateway = new MpesaGateway($transaction->tenant);

            $checkoutRequestId = $transaction->gateway_transaction_id;
            $result = $mpesaGateway->queryTransactionStatus($checkoutRequestId);

            if ($result['success'] && isset($result['data']['ResultCode'])) {
                $resultCode = (int)$result['data']['ResultCode'];
                
                if ($resultCode === 0) {
                    $this->info("Transaction {$transaction->id} succeeded at Safaricom. Completing purchase...");
                    
                    // The query response doesn't have the exact receipt number structured the same way as the callback
                    // But we can extract it or generate a fallback
                    $receipt = 'RECONCILED_' . time(); 
                    
                    try {
                        $billingService->completePurchase($transaction, $receipt);
                        $this->info("Transaction {$transaction->id} completed successfully.");
                    } catch (\Exception $e) {
                        Log::error("Failed to complete purchase during reconciliation", ['id' => $transaction->id, 'error' => $e->getMessage()]);
                        $this->error("Failed to complete purchase: " . $e->getMessage());
                    }
                } else {
                    $this->warn("Transaction {$transaction->id} failed at Safaricom (Code: {$resultCode}). Marking as failed.");
                    $transaction->update(['status' => 'failed']);
                }
            } else {
                $this->error("Failed to query transaction {$transaction->id}: " . ($result['message'] ?? 'Unknown error'));
            }
        }

        $this->info("Reconciliation complete.");
    }
}
