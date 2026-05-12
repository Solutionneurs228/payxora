<?php

namespace App\Contracts;

use App\Models\Transaction;

interface PaymentProviderInterface
{
    public function initiate(Transaction $transaction, array $payload = []): array;

    public function verify(array $payload = []): array;

    public function handleWebhook(array $payload = []): array;

    public function refund(Transaction $transaction, float $amount): array;
}
