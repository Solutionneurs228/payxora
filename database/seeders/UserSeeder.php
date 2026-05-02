<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\KycProfile;
use App\Enums\UserRole;
use App\Enums\KycStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::create([
            'name' => 'Administrateur PayXora',
            'email' => 'admin@payxora.tg',
            'phone' => '+22890123456',
            'password' => Hash::make('password'),
            'role' => UserRole::ADMIN,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        KycProfile::create([
            'user_id' => $admin->id,
            'status' => KycStatus::APPROVED,
            'id_type' => 'passport',
            'id_number' => 'ADMIN001',
            'verified_at' => now(),
        ]);

        // Vendeur
        $seller = User::create([
            'name' => 'Vendeur Demo',
            'email' => 'seller@payxora.tg',
            'phone' => '+22890123457',
            'password' => Hash::make('password'),
            'role' => UserRole::SELLER,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        KycProfile::create([
            'user_id' => $seller->id,
            'status' => KycStatus::APPROVED,
            'id_type' => 'cni',
            'id_number' => 'TG-CNI-123456',
            'verified_at' => now(),
        ]);

        // Acheteur
        $buyer = User::create([
            'name' => 'Acheteur Demo',
            'email' => 'buyer@payxora.tg',
            'phone' => '+22890123458',
            'password' => Hash::make('password'),
            'role' => UserRole::BUYER,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        KycProfile::create([
            'user_id' => $buyer->id,
            'status' => KycStatus::APPROVED,
            'id_type' => 'cni',
            'id_number' => 'TG-CNI-789012',
            'verified_at' => now(),
        ]);

        // Utilisateur KYC pending
        $pending = User::create([
            'name' => 'Utilisateur KYC Pending',
            'email' => 'pending@payxora.tg',
            'phone' => '+22890123459',
            'password' => Hash::make('password'),
            'role' => UserRole::BUYER,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        KycProfile::create([
            'user_id' => $pending->id,
            'status' => KycStatus::PENDING,
        ]);
    }
}
