<?php

namespace App\Services\Payments;

use App\Services\Payments\Contracts\PaymentGatewayInterface;
use App\Models\Transaction;

class MockGateway implements PaymentGatewayInterface
{
    /**
     * Initiate a simulated STK Push transaction.
     */
    public function initiatePayment(Transaction $transaction, string $phone): array
    {
        return [
            'success' => true,
            'checkout_request_id' => 'MOCK_' . uniqid(),
            'message' => 'STK Push Simulated Successfully'
        ];
    }

    /**
     * Verify the simulation webhook.
     */
    public function verifyWebhook(array $headers, array $payload): bool
    {
        return true;
    }
}
