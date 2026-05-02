<?php

namespace Tests\Feature;

use App\Enums\TransactionStatus;
use App\Enums\UserRole;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_transaction(): void
    {
        $seller = User::factory()->create(['role' => UserRole::SELLER]);

        $response = $this->actingAs($seller)->post('/transactions', [
            'product_name' => 'Test Product',
            'amount' => 50000,
            'currency' => 'XOF',
            'shipping_address' => 'Lome, Togo',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('transactions', [
            'product_name' => 'Test Product',
            'seller_id' => $seller->id,
        ]);
    }

    public function test_transaction_status_transitions_correctly(): void
    {
        $transaction = Transaction::factory()->create([
            'status' => TransactionStatus::PENDING_PAYMENT,
        ]);

        $this->assertTrue($transaction->status->canTransitionTo(TransactionStatus::FUNDED));
        $this->assertFalse($transaction->status->canTransitionTo(TransactionStatus::COMPLETED));
    }
}
