<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ajoute la colonne 'method' à la table payments
     * pour distinguer Mobile Money et Carte Bancaire.
     *
     * Ajoute aussi les colonnes nécessaires
     * pour le suivi des remboursements
     * et la réponse brute du provider.
     */

    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {

            if (!Schema::hasColumn('payments', 'method')) {

                $table->string('method', 20)
                    ->default('mobile_money')
                    ->comment('mobile_money ou card')
                    ->after('transaction_id');
            }

            if (!Schema::hasColumn('payments', 'provider_response')) {

                $table->json('provider_response')
                    ->nullable()
                    ->after('provider_reference')
                    ->comment('Reponse JSON brute du provider de paiement');
            }

            if (!Schema::hasColumn('payments', 'refunded_at')) {

                $table->timestamp('refunded_at')
                    ->nullable()
                    ->after('processed_at')
                    ->comment('Date du remboursement');
            }

        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {

            $columns = [];

            if (Schema::hasColumn('payments', 'method')) {
                $columns[] = 'method';
            }

            if (Schema::hasColumn('payments', 'provider_response')) {
                $columns[] = 'provider_response';
            }

            if (Schema::hasColumn('payments', 'refunded_at')) {
                $columns[] = 'refunded_at';
            }

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }

        });
    }
};
