<?php

namespace App\Services\Payments;

use App\Services\Payments\Contracts\PaymentGatewayInterface;
use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class MpesaGateway implements PaymentGatewayInterface
{
    protected string $consumerKey;
    protected string $consumerSecret;
    protected string $passkey;
    protected string $masterShortcode;
    protected string $partyB;
    protected string $callbackUrl;
    protected string $env;

    public function __construct(?\App\Models\Tenant $tenant = null, ?\App\Models\Network $network = null)
    {
        // Master app credentials (always used for token + password generation)
        $this->consumerKey    = config('services.mpesa.consumer_key', '');
        $this->consumerSecret = config('services.mpesa.consumer_secret', '');
        $this->passkey        = config('services.mpesa.passkey', '');
        $this->masterShortcode = config('services.mpesa.shortcode', '174379');
        $this->env            = config('services.mpesa.env', 'sandbox');
        $this->callbackUrl    = config('services.mpesa.callback_url', '');

        // Resolve PartyB (Till/Paybill) — priority:
        // 1. Network-specific payout account
        // 2. Tenant-level shortcode
        // 3. Master shortcode fallback
        $this->partyB = $this->masterShortcode;

        if ($network) {
            $payoutAccount = \App\Models\PayoutAccount::where('network_id', $network->id)
                ->where('is_active', true)->first();
            if ($payoutAccount && $payoutAccount->mpesa_shortcode) {
                $this->partyB = $payoutAccount->mpesa_shortcode;
                // Use network-specific Daraja creds if set
                if ($payoutAccount->mpesa_consumer_key) {
                    $this->consumerKey    = $payoutAccount->mpesa_consumer_key;
                    $this->consumerSecret = $payoutAccount->mpesa_consumer_secret;
                    $this->passkey        = $payoutAccount->mpesa_passkey;
                    $this->masterShortcode = $payoutAccount->mpesa_shortcode;
                }
                if ($payoutAccount->mpesa_environment) {
                    $this->env = $payoutAccount->mpesa_environment;
                }
            } elseif ($tenant && $tenant->mpesa_shortcode) {
                $this->partyB = $tenant->mpesa_shortcode;
            }
        } elseif ($tenant && $tenant->mpesa_shortcode) {
            $this->partyB = $tenant->mpesa_shortcode;
        }
    }

    /**
     * Get M-Pesa API Access Token.
     */
    protected function getAccessToken(): string
    {
        $url = $this->env === 'production'
            ? 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials'
            : 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

        $response = Http::withoutVerifying()->timeout(60)->withBasicAuth($this->consumerKey, $this->consumerSecret)->get($url);

        if (!$response->successful()) {
            throw new Exception("Failed to fetch M-Pesa access token: " . $response->body());
        }

        return $response->json()['access_token'];
    }

    /**
     * Initiate STK Push checkout.
     */
    public function initiatePayment(Transaction $transaction, string $phone): array
    {
        try {
            $token = $this->getAccessToken();
            $timestamp = now()->format('YmdHis');
            
            // Password MUST be generated using the Master App's Shortcode
            $password = base64_encode($this->masterShortcode . $this->passkey . $timestamp);
            
            $url = $this->env === 'production'
                ? 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest'
                : 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

            // Convert amount to integer units of Shillings
            $amountInShillings = (int) ceil($transaction->amount_minor / 100);

            // Format phone number to 254XXXXXXXXX
            $formattedPhone = $this->formatPhoneNumber($phone);

            // Allow transaction to override the PartyB if needed, else use Tenant's Till
            $partyB = $this->partyB;
            $accountReference = 'Net_' . $transaction->network->slug;
            
            if (isset($transaction->raw_payload['payout_account']) && is_array($transaction->raw_payload['payout_account'])) {
                $pa = $transaction->raw_payload['payout_account'];
                if (!empty($pa['account_identifier'])) {
                    $partyB = $pa['account_identifier'];
                }
                if (!empty($pa['account_name'])) {
                    $accountReference = substr($pa['account_name'], 0, 20);
                }
            }

            $payload = [
                'BusinessShortCode' => $this->masterShortcode, // Must match the App Shortcode
                'Password' => $password,
                'Timestamp' => $timestamp,
                'TransactionType' => 'CustomerPayBillOnline', // Required by Safaricom Sandbox and most STK setups
                'Amount' => $amountInShillings,
                'PartyA' => $formattedPhone,
                'PartyB' => $partyB, // The Tenant's Till Number
                'PhoneNumber' => $formattedPhone,
                'CallBackURL' => $this->callbackUrl . '?transaction_id=' . $transaction->id,
                'AccountReference' => $accountReference,
                'TransactionDesc' => 'Internet Purchase'
            ];

            $response = Http::withoutVerifying()->timeout(60)->withToken($token)->post($url, $payload);

            if (!$response->successful()) {
                Log::error("M-Pesa STK Push error response", ['response' => $response->body()]);
                return [
                    'success' => false,
                    'message' => 'Mpesa API error: ' . ($response->json()['errorMessage'] ?? $response->json()['CustomerMessage'] ?? 'Unknown error')
                ];
            }

            $data = $response->json();
            return [
                'success' => true,
                'checkout_request_id' => $data['CheckoutRequestID'] ?? '',
                'message' => $data['CustomerMessage'] ?? 'STK push sent.'
            ];

        } catch (Exception $e) {
            Log::error("M-Pesa payment initiation failed", ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Exception in payment processing: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Query STK Push Transaction Status.
     */
    public function queryTransactionStatus(string $checkoutRequestId): array
    {
        try {
            $token = $this->getAccessToken();
            $timestamp = now()->format('YmdHis');
            
            // Password MUST be generated using the Master App's Shortcode
            $password = base64_encode($this->masterShortcode . $this->passkey . $timestamp);
            
            $url = $this->env === 'production'
                ? 'https://api.safaricom.co.ke/mpesa/stkpushquery/v1/query'
                : 'https://sandbox.safaricom.co.ke/mpesa/stkpushquery/v1/query';

            $payload = [
                'BusinessShortCode' => $this->masterShortcode,
                'Password' => $password,
                'Timestamp' => $timestamp,
                'CheckoutRequestID' => $checkoutRequestId,
            ];

            $response = Http::withoutVerifying()->timeout(60)->withToken($token)->post($url, $payload);

            if (!$response->successful()) {
                return [
                    'success' => false,
                    'message' => 'Mpesa API error: ' . ($response->json()['errorMessage'] ?? 'Unknown error'),
                    'raw' => $response->json(),
                ];
            }

            return [
                'success' => true,
                'data' => $response->json()
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Exception in query: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Verify Webhook payload from Safaricom.
     */
    public function verifyWebhook(array $headers, array $payload): bool
    {
        return isset($payload['Body']['stkCallback']);
    }

    /**
     * Clean phone number to 254 format.
     */
    protected function formatPhoneNumber(string $phone): string
    {
        // Remove all non-numeric characters (including +)
        $cleaned = preg_replace('/[^0-9]/', '', $phone);
        
        // If it starts with 0 (e.g., 0712345678)
        if (str_starts_with($cleaned, '0')) {
            return '254' . substr($cleaned, 1);
        }
        
        // If it's a 9 digit number starting with 7 or 1 (e.g., 712345678)
        if (strlen($cleaned) === 9 && (str_starts_with($cleaned, '7') || str_starts_with($cleaned, '1'))) {
            return '254' . $cleaned;
        }
        
        return $cleaned;
    }
}
