<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'PayXora',
            'email' => 'admin@payxora.tg',
            'phone' => '+22890000000',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'kyc_status' => 'verified',
            'is_active' => true,
        ]);

        // Vendeur test
        User::create([
            'first_name' => 'Kossi',
            'last_name' => 'Amedegnato',
            'email' => 'vendeur@payxora.tg',
            'phone' => '+22891111111',
            'password' => Hash::make('password'),
            'role' => 'seller',
            'kyc_status' => 'verified',
            'is_active' => true,
        ]);

        // Acheteur test
        User::create([
            'first_name' => 'Afi',
            'last_name' => 'Kodjo',
            'email' => 'acheteur@payxora.tg',
            'phone' => '+22892222222',
            'password' => Hash::make('password'),
            'role' => 'buyer',
            'kyc_status' => 'verified',
            'is_active' => true,
        ]);
    }
}
