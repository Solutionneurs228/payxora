<?php

namespace Database\Seeders;

use App\Enums\KycStatus;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::create([
            'name' => 'Administrateur PayXora',
            'email' => 'admin@payxora.tg',
            'phone' => '90000001',
            'password' => Hash::make('password'),
            'role' => UserRole::ADMIN,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $admin->kycProfile()->create([
            'id_type' => 'passport',
            'id_number' => 'ADMIN001',
            'status' => KycStatus::APPROVED,
            'verified_at' => now(),
            'phone_verified' => true,
        ]);

        // Vendeur
        $seller = User::create([
            'name' => 'Vendeur Demo',
            'email' => 'seller@payxora.tg',
            'phone' => '90000002',
            'password' => Hash::make('password'),
            'role' => UserRole::SELLER,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $seller->kycProfile()->create([
            'id_type' => 'cni',
            'id_number' => 'CNI123456',
            'status' => KycStatus::APPROVED,
            'verified_at' => now(),
            'phone_verified' => true,
        ]);

        // Acheteur
        $buyer = User::create([
            'name' => 'Acheteur Demo',
            'email' => 'buyer@payxora.tg',
            'phone' => '90000003',
            'password' => Hash::make('password'),
            'role' => UserRole::BUYER,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $buyer->kycProfile()->create([
            'id_type' => 'cni',
            'id_number' => 'CNI789012',
            'status' => KycStatus::APPROVED,
            'verified_at' => now(),
            'phone_verified' => true,
        ]);
    }
}
