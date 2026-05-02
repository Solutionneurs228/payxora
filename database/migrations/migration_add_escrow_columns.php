<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ajoute les colonnes manquantes pour le workflow escrow.
     * Certaines colonnes peuvent deja exister (tracking_number, shipped_at, etc.)
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Colonnes qui peuvent manquer - on les ajoute seulement si elles n'existent pas

            if (!Schema::hasColumn('transactions', 'confirmation_deadline')) {
                $table->timestamp('confirmation_deadline')
                    ->nullable()
                    ->after('delivered_at')
                    ->comment('Date limite pour confirmer la reception avant liberation auto');
            }

            if (!Schema::hasColumn('transactions', 'tracking_number')) {
                $table->string('tracking_number', 100)
                    ->nullable()
                    ->after('status')
                    ->comment('Numero de suivi du colis');
            }

            if (!Schema::hasColumn('transactions', 'shipped_at')) {
                $table->timestamp('shipped_at')
                    ->nullable()
                    ->after('tracking_number')
                    ->comment('Date d\'expedition du produit');
            }

            if (!Schema::hasColumn('transactions', 'delivered_at')) {
                $table->timestamp('delivered_at')
                    ->nullable()
                    ->after('shshipped_at')
                    ->comment('Date de livraison au client');
            }

            if (!Schema::hasColumn('transactions', 'completed_at')) {
                $table->timestamp('completed_at')
                    ->nullable()
                    ->after('delivered_at')
                    ->comment('Date de confirmation et liberation des fonds');
            }

            if (!Schema::hasColumn('transactions', 'cancelled_at')) {
                $table->timestamp('cancelled_at')
                    ->nullable()
                    ->after('completed_at')
                    ->comment('Date d\'annulation de la transaction');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $columns = [];

            if (Schema::hasColumn('transactions', 'confirmation_deadline')) {
                $columns[] = 'confirmation_deadline';
            }
            if (Schema::hasColumn('transactions', 'tracking_number')) {
                $columns[] = 'tracking_number';
            }
            if (Schema::hasColumn('transactions', 'shipped_at')) {
                $columns[] = 'shipped_at';
            }
            if (Schema::hasColumn('transactions', 'delivered_at')) {
                $columns[] = 'delivered_at';
            }
            if (Schema::hasColumn('transactions', 'completed_at')) {
                $columns[] = 'completed_at';
            }
            if (Schema::hasColumn('transactions', 'cancelled_at')) {
                $columns[] = 'cancelled_at';
            }

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
