<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Services\Billing\BillingService;
use App\Services\Payments\MpesaGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * Handle M-Pesa Daraja STK Push Callback
     */
    public function mpesa(Request $request, BillingService $billingService)
    {
        Log::info('M-Pesa Webhook Received', $request->all());

        $gateway = new MpesaGateway();
        $payload = $request->all();

        if (!$gateway->verifyWebhook($request->headers->all(), $payload)) {
            Log::warning('Invalid M-Pesa webhook signature or structure.');
            return response()->json(['status' => 'ignored'], 400);
        }

        $stkCallback = $payload['Body']['stkCallback'] ?? null;
        if (!$stkCallback) {
            return response()->json(['status' => 'invalid_structure'], 400);
        }

        $resultCode = $stkCallback['ResultCode'] ?? -1;
        $checkoutRequestID = $stkCallback['CheckoutRequestID'] ?? null;

        if (!$checkoutRequestID) {
            return response()->json(['status' => 'missing_ref'], 400);
        }

        if ($resultCode == 0) {
            // Success
            $billingService->confirmPayment($checkoutRequestID, $payload, 'mpesa');
        } else {
            // Failed (e.g., user cancelled, insufficient balance)
            $billingService->failPayment($checkoutRequestID, $payload);
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Handle SaaS ISP Subscription STK Push Callback
     */
    public function subscription(Request $request)
    {
        Log::info('Subscription Webhook Received', $request->all());

        $payload = $request->all();
        $stkCallback = $payload['Body']['stkCallback'] ?? null;
        
        if (!$stkCallback) {
            return response()->json(['status' => 'invalid_structure'], 400);
        }

        $resultCode = $stkCallback['ResultCode'] ?? -1;
        $tenantId = $request->query('tenant_id');

        if ($resultCode == 0 && $tenantId) {
            // Payment Success: Renew the Tenant's subscription by 30 days
            $tenant = \App\Models\Tenant::find($tenantId);
            if ($tenant) {
                $currentEnd = $tenant->subscription_ends_at && $tenant->subscription_ends_at->isFuture() 
                    ? $tenant->subscription_ends_at 
                    : now();
                
                $tenant->update([
                    'subscription_ends_at' => $currentEnd->addDays(30)
                ]);
                Log::info("Tenant {$tenant->id} subscription renewed until {$tenant->subscription_ends_at}");
            }
        } else {
            Log::warning('Subscription payment failed or tenant ID missing', ['ResultCode' => $resultCode, 'tenant_id' => $tenantId]);
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Handle Sandbox Mock Webhook
     */
    public function mock(Request $request, BillingService $billingService)
    {
        Log::info('Mock Webhook Received', $request->all());

        $checkoutRequestID = $request->input('checkout_request_id');
        $status = $request->input('status', 'success');

        if (!$checkoutRequestID) {
            return response()->json(['error' => 'missing_ref'], 400);
        }

        if ($status === 'success') {
            $billingService->confirmPayment($checkoutRequestID, $request->all(), 'mock');
        } else {
            $billingService->failPayment($checkoutRequestID, $request->all());
        }

        return response()->json(['status' => 'success']);
    }
}
