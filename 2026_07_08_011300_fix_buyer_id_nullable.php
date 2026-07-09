<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Supprimer la contrainte FK si elle existe
        $foreignKeys = DB::select("SELECT CONSTRAINT_NAME 
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
            WHERE TABLE_NAME = 'transactions' 
            AND COLUMN_NAME = 'buyer_id' 
            AND CONSTRAINT_SCHEMA = DATABASE()");

        foreach ($foreignKeys as $fk) {
            if ($fk->CONSTRAINT_NAME !== 'PRIMARY') {
                Schema::table('transactions', function (Blueprint $table) use ($fk) {
                    $table->dropForeign($fk->CONSTRAINT_NAME);
                });
            }
        }

        // Rendre buyer_id nullable
        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('buyer_id')->nullable()->change();
        });

        // Recréer la contrainte FK avec ON DELETE SET NULL
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreign('buyer_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['buyer_id']);
            $table->unsignedBigInteger('buyer_id')->nullable(false)->change();
            $table->foreign('buyer_id')->references('id')->on('users');
        });
    }
};
