<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique(); // REF-PAYX-XXXXXX
            $table->foreignId('seller_id')->constrained('users');
            $table->foreignId('buyer_id')->nullable()->constrained('users');
            $table->string('product_name');
            $table->text('product_description')->nullable();
            $table->decimal('amount', 12, 2);
            $table->decimal('commission_amount', 12, 2)->default(0);
            $table->decimal('net_amount', 12, 2)->default(0);
            $table->enum('status', [
                'pending',      // En attente de paiement
                'paid',         // Payé, en séquestre
                'shipped',      // Expédié
                'delivered',    // Livré, en attente confirmation
                'completed',    // Terminé, fonds libérés
                'cancelled',    // Annulé
                'disputed',     // En litige
                'refunded'      // Remboursé
            ])->default('pending');
            $table->enum('payment_method', ['tmoney', 'moov', 'bank'])->nullable();
            $table->string('payment_reference')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('shipping_address')->nullable();
            $table->string('tracking_number')->nullable();
            $table->timestamp('dispute_deadline')->nullable(); // 48h après livraison
            $table->text('seller_notes')->nullable();
            $table->text('buyer_notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['seller_id', 'status']);
            $table->index(['buyer_id', 'status']);
            $table->index('reference');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
