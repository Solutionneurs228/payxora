<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'transaction_id' => Transaction::factory(),
            'user_id' => User::factory(),
            'provider' => fake()->randomElement(['tmoney', 'moov', 'stripe', 'flutterwave']),
            'provider_reference' => fake()->uuid(),
            'method' => fake()->randomElement(['mobile_money', 'card', 'bank_transfer']),
            'provider_response' => null,
            'amount' => fake()->randomFloat(2, 1000, 500000),
            'currency' => 'XOF',
            'status' => fake()->randomElement(['pending', 'success', 'failed']),
        ];
    }

    public function success(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'success',
            'processed_at' => now(),
        ]);
    }
}
