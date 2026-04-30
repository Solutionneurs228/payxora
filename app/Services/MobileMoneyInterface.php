<?php

namespace App\Services;

interface MobileMoneyInterface
{
    public function initiatePayment(string $phone, int $amount, string $reference): array;
    public function checkStatus(string $reference): array;
    public function refund(string $reference, int $amount): array;
}