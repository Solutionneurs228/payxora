<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kyc_profiles', function (Blueprint $table) {
            // Rendre les champs obligatoires nullable
            if (Schema::hasColumn('kyc_profiles', 'id_number')) {
                $table->string('id_number')->nullable()->change();
            }
            if (Schema::hasColumn('kyc_profiles', 'id_photo_path')) {
                $table->string('id_photo_path')->nullable()->change();
            }
            if (Schema::hasColumn('kyc_profiles', 'selfie_path')) {
                $table->string('selfie_path')->nullable()->change();
            }
            if (Schema::hasColumn('kyc_profiles', 'id_type')) {
                $table->string('id_type')->nullable()->change();
            }
        });

        // Modifier le statut pour accepter not_submitted
        // On doit recréer la colonne car enum ne peut pas être modifiée facilement
        Schema::table('kyc_profiles', function (Blueprint $table) {
            $table->string('status_temp')->default('not_submitted');
        });

        \DB::table('kyc_profiles')->update(['status_temp' => \DB::raw('status')]);

        Schema::table('kyc_profiles', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('kyc_profiles', function (Blueprint $table) {
            $table->renameColumn('status_temp', 'status');
        });
    }

    public function down(): void
    {
        Schema::table('kyc_profiles', function (Blueprint $table) {
            $table->string('id_number')->nullable(false)->change();
            $table->string('id_photo_path')->nullable(false)->change();
            $table->string('selfie_path')->nullable(false)->change();
            $table->string('id_type')->nullable(false)->change();
        });
    }
};
