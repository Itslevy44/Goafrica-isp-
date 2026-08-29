<?php

namespace App\Services\Billing;

use App\Models\Customer;
use App\Models\Device;
use App\Models\InternetSession;
use App\Models\Network;
use App\Models\Offer;
use App\Models\Tenant;
use App\Models\Transaction;
use App\Models\PayoutAccount;
use App\Models\Voucher;
use App\Models\VoucherRedemption;
use App\Services\Devices\Contracts\DeviceDriverInterface;
use App\Services\Devices\MikroTikDriver;
use App\Services\Devices\MockDeviceDriver;
use App\Services\Payments\Contracts\PaymentGatewayInterface;
use App\Services\Payments\MockGateway;
use App\Services\Payments\MpesaGateway;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BillingService
{
    public function __construct()
    {
    }

    /**
     * Resolve the Payment Gateway based on the requested name or network config.
     */
    protected function resolveGateway(string $gatewayName, ?Tenant $tenant = null): PaymentGatewayInterface
    {
        if ($gatewayName === 'mock' || env('USE_MOCK_PAYMENT', false)) {
            return new MockGateway();
        }
        
        return new MpesaGateway($tenant);
    }

    /**
     * Resolve the Device Driver for a specific device.
     */
    public function resolveDeviceDriver(Device $device): DeviceDriverInterface
    {
        $credentials = $this->normalizeCredentials($device->credentials_encrypted ?? []);

        if ($device->type === 'mock' || env('USE_MOCK_ROUTER', false)) {
            $driver = new MockDeviceDriver();
            $driver->connect($device->ip_address, $device->api_port, $credentials);
            return $driver;
        }

        $driver = new MikroTikDriver();
        $connected = $driver->connect($device->ip_address, $device->api_port, $credentials);
        
        if (!$connected) {
            throw new Exception("Unable to connect to device: {$device->name}");
        }

        return $driver;
    }

    /**
     * Normalize device credentials from arrays or JSON strings.
     */
    protected function normalizeCredentials(mixed $credentials): array
    {
        if (is_array($credentials)) {
            return $credentials;
        }

        if (is_string($credentials) && trim($credentials) !== '') {
            $decoded = json_decode($credentials, true);
            if (is_array($decoded)) {
                return $decoded;
            }

            return ['value' => $credentials];
        }

        return [];
    }

    /**
     * Step 1: User selects an offer and submits phone number.
     * We create a transaction and push to mobile money.
     */
    public function initiatePurchase(Network $network, Offer $offer, string $phone, string $gatewayName, string $macAddress, string $ipAddress): array
    {
        $tenant = $network->tenant;

        // 1. Find or create customer
        $customer = Customer::firstOrCreate(
            ['tenant_id' => $tenant->id, 'phone' => $phone],
            ['first_seen_at' => now(), 'last_seen_at' => now()]
        );
        $customer->update(['last_seen_at' => now()]);

        // 2. Direct Payment Model (No commission)
        $netAmount = $offer->price_minor;

        // 3. Create Pending Transaction
        $transaction = Transaction::create([
            'tenant_id' => $tenant->id,
            'network_id' => $network->id,
            'customer_id' => $customer->id,
            'offer_id' => $offer->id,
            'gateway' => $gatewayName,
            'gateway_ref' => 'PENDING_' . uniqid(), // Gateway assigns real ref later
            'amount_minor' => $offer->price_minor,
            'currency' => $offer->currency,
            'status' => 'pending',
            'commission_rate' => 0.00,
            'commission_amount_minor' => 0,
            'net_amount_minor' => $netAmount,
            'raw_payload' => ['mac' => $macAddress, 'ip' => $ipAddress],
        ]);

        // 4. Send to Payment Gateway (Using Tenant's keys)
        $gateway = $this->resolveGateway($gatewayName, $tenant);
        $result = $gateway->initiatePayment($transaction, $phone);

        if ($result['success']) {
            $transaction->update(['gateway_ref' => $result['checkout_request_id']]);
        } else {
            $transaction->update([
                'status' => 'failed', 
                'raw_payload' => array_merge($transaction->raw_payload ?? [], ['error' => $result['message']])
            ]);
        }

        return $result;
    }

    /**
     * Step 2: Payment Gateway Webhook confirms payment.
     */
    public function confirmPayment(string $gatewayRef, array $payload, string $gatewayName): void
    {
        $transaction = Transaction::where('gateway_ref', $gatewayRef)->first();

        if (!$transaction) {
            Log::error("Transaction not found for webhook confirmation", ['ref' => $gatewayRef]);
            return;
        }

        if ($transaction->status === 'success') {
            Log::info("Transaction already processed", ['ref' => $gatewayRef]);
            return;
        }

        DB::transaction(function () use ($transaction, $payload) {
            $transaction->update([
                'status' => 'success',
                'raw_payload' => array_merge($transaction->raw_payload ?? [], ['webhook' => $payload])
            ]);

            $tenant = $transaction->tenant;

            $macAddress = $transaction->raw_payload['mac'] ?? null;
            $ipAddress = $transaction->raw_payload['ip'] ?? null;

            if ($macAddress) {
                $this->grantAccess('transaction', $transaction->id, $transaction->customer, $transaction->network, $transaction->offer->duration_minutes, $macAddress, $ipAddress);
            } else {
                Log::error("Cannot grant access: MAC address missing from transaction", ['transaction_id' => $transaction->id]);
            }
        });
    }
    
    /**
     * Mark a transaction as failed via webhook.
     */
    public function failPayment(string $gatewayRef, array $payload): void
    {
        $transaction = Transaction::where('gateway_ref', $gatewayRef)->first();
        if ($transaction && $transaction->status === 'pending') {
            $transaction->update([
                'status' => 'failed',
                'raw_payload' => array_merge($transaction->raw_payload ?? [], ['webhook_fail' => $payload])
            ]);
        }
    }

    /**
     * Redeem a voucher for access.
     */
    public function redeemVoucher(Network $network, string $phone, string $voucherCode, string $macAddress, string $ipAddress): array
    {
        return DB::transaction(function () use ($network, $phone, $voucherCode, $macAddress, $ipAddress) {
            // Remove hyphens, spaces, and make it uppercase to match the database exactly
            $cleanCode = strtoupper(str_replace(['-', ' '], '', $voucherCode));
            
            $voucher = Voucher::where('network_id', $network->id)->where('code', $cleanCode)->lockForUpdate()->first();

            if (!$voucher) {
                throw new Exception("Invalid voucher code.");
            }

            if ($voucher->expires_at && $voucher->expires_at->isPast()) {
                throw new Exception("Voucher has expired.");
            }

            if ($voucher->uses_count >= $voucher->max_uses) {
                throw new Exception("Voucher usage limit reached.");
            }

            $customer = Customer::firstOrCreate(
                ['tenant_id' => $network->tenant_id, 'phone' => $phone],
                ['first_seen_at' => now(), 'last_seen_at' => now()]
            );

            $voucher->increment('uses_count');

            $durationMinutes = $voucher->type === 'time' ? $voucher->value : 60;

            $session = $this->grantAccess('voucher', $voucher->id, $customer, $network, $durationMinutes, $macAddress, $ipAddress);

            VoucherRedemption::create([
                'voucher_id' => $voucher->id,
                'customer_id' => $customer->id,
                'session_id' => $session->id,
                'redeemed_at' => now()
            ]);

            return ['success' => true, 'message' => 'Voucher redeemed successfully. You are now connected.'];
        });
    }

    /**
     * Core method to authorize a MAC address on the physical router.
     */
    public function grantAccess(string $sourceType, int $sourceId, Customer $customer, Network $network, int $durationMinutes, string $macAddress, ?string $ipAddress = null): InternetSession
    {
        $device = Device::where('network_id', $network->id)->first();

        if (!$device) {
            throw new Exception("No active router configured for this network.");
        }

        try {
            $driver = $this->resolveDeviceDriver($device);
            $success = $driver->createHotspotUser($macAddress, $durationMinutes);

            if (!$success) {
                Log::warning("Failed to create hotspot user via driver, but creating session record anyway for tracking.", ['mac' => $macAddress]);
            }
        } catch (Exception $e) {
            Log::error("Device interaction failed during grantAccess: " . $e->getMessage());
        }

        return InternetSession::create([
            'tenant_id' => $network->tenant_id,
            'network_id' => $network->id,
            'device_id' => $device->id,
            'customer_id' => $customer->id,
            'mac_address' => $macAddress,
            'ip_address' => $ipAddress,
            'source_type' => $sourceType,
            'source_id' => $sourceId,
            'started_at' => now(),
            'ends_at' => now()->addMinutes($durationMinutes),
            'status' => 'active'
        ]);
    }
}
