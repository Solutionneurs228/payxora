<?php

namespace App\Services;

class WebhookSecurityService
{
    public function validateSignature(
        string $payload,
        ?string $signature,
        string $secret
    ): bool {
        if (!$signature) {
            return false;
        }

        $computed = hash_hmac('sha256', $payload, $secret);

        return hash_equals($computed, $signature);
    }
}
