<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kyc_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('kyc_profiles', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('status');
            }
            if (!Schema::hasColumn('kyc_profiles', 'id_front_path')) {
                $table->string('id_front_path')->nullable()->after('id_number');
            }
            if (!Schema::hasColumn('kyc_profiles', 'id_back_path')) {
                $table->string('id_back_path')->nullable()->after('id_front_path');
            }
            if (!Schema::hasColumn('kyc_profiles', 'selfie_path')) {
                $table->string('selfie_path')->nullable()->after('id_back_path');
            }
            if (!Schema::hasColumn('kyc_profiles', 'submitted_at')) {
                $table->timestamp('submitted_at')->nullable()->after('status');
            }
            if (!Schema::hasColumn('kyc_profiles', 'verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('submitted_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('kyc_profiles', function (Blueprint $table) {
            $table->dropColumn(['rejection_reason', 'id_front_path', 'id_back_path', 'selfie_path', 'submitted_at', 'verified_at']);
        });
    }
};
