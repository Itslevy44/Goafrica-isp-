<?php

namespace App\Services\Payments\Contracts;

use App\Models\Transaction;

interface PaymentGatewayInterface
{
    /**
     * Initiate a STK push / mobile money checkout.
     */
    public function initiatePayment(Transaction $transaction, string $phone): array;

    /**
     * Verify the webhook signature and structure.
     */
    public function verifyWebhook(array $headers, array $payload): bool;
}
