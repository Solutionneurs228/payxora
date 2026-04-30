<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disputes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained();
            $table->foreignId('opened_by')->constrained('users');
            $table->enum('reason', [
                'not_received',
                'not_as_described',
                'damaged',
                'wrong_item',
                'seller_no_ship',
                'other'
            ]);
            $table->text('description');
            $table->text('evidence')->nullable(); // URLs photos/preuves
            $table->enum('status', ['open', 'under_review', 'resolved_buyer', 'resolved_seller', 'closed'])->default('open');
            $table->foreignId('resolved_by')->nullable()->constrained('users');
            $table->text('resolution_notes')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disputes');
    }
};
