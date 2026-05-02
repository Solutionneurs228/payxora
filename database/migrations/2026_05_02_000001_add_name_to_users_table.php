<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'name')) {
                $table->string('name')->after('id')->nullable();
            }
        });

        // Migrer les données : concaténer first_name + last_name dans name
        \DB::table('users')->whereNull('name')->orWhere('name', '')->update([
            'name' => \DB::raw("CONCAT(first_name, ' ', last_name)")
        ]);

        // Rendre name non-null après migration
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }
};
