<?php

namespace App\Services;

use App\Models\EscrowAccount;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WithdrawalService
{
    public function __construct(
        private PaymentProviderInterface $paymentProvider
    ) {}

    /**
     * Demander un retrait
     */
    public function requestWithdrawal(User $user, float $amount, string $phoneNumber, string $provider): array
    {
        $escrow = EscrowAccount::where('user_id', $user->id)->first();

        if (!$escrow || $escrow->balance < $amount) {
            return [
                'success' => false,
                'message' => 'Solde insuffisant',
            ];
        }

        $fee = config('payxora.withdrawal_fee', 500);
        $total = $amount + $fee;

        if ($escrow->balance < $total) {
            return [
                'success' => false,
                'message' => 'Solde insuffisant pour couvrir les frais de retrait (' . $fee . ' FCFA)',
            ];
        }

        return DB::transaction(function () use ($escrow, $amount, $phoneNumber, $provider, $fee) {
            $escrow->decrement('balance', $amount + $fee);

            // TODO: Creer un record de retrait
            // TODO: Envoyer via le provider MNO

            Log::info('Withdrawal requested', [
                'user_id' => $escrow->user_id,
                'amount' => $amount,
                'fee' => $fee,
                'provider' => $provider,
            ]);

            return [
                'success' => true,
                'message' => 'Retrait de ' . $amount . ' FCFA initie',
                'fee' => $fee,
            ];
        });
    }

    /**
     * Obtenir le solde disponible
     */
    public function getBalance(User $user): float
    {
        $escrow = EscrowAccount::where('user_id', $user->id)->first();
        return $escrow ? (float) $escrow->balance : 0;
    }
}
