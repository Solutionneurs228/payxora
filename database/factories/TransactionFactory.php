<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        $amount = fake()->randomFloat(2, 1000, 500000);
        $commission = $amount * 0.03;

        return [
            'buyer_id' => User::factory(),
            'seller_id' => User::factory()->seller(),
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'amount' => $amount,
            'commission_amount' => $commission,
            'net_amount' => $amount - $commission,
            'currency' => 'XOF',
            'status' => fake()->randomElement(['pending', 'paid', 'shipped', 'delivered', 'completed']),
            'published_at' => now(),
            'expires_at' => now()->addDays(7),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'paid_at' => null,
        ]);
    }

    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'paid',
            'paid_at' => now(),
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'paid_at' => now(),
            'shipped_at' => now()->addDay(),
            'delivered_at' => now()->addDays(2),
            'completed_at' => now()->addDays(3),
        ]);
    }
}
