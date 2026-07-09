<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kyc;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::create([
            'name' => 'Admin PayXora',
            'email' => 'admin@payxora.tg',
            'phone' => '+22890000000',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'kyc_status' => 'verified',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        Kyc::create([
            'user_id' => $admin->id,
            'full_name' => 'Admin PayXora',
            'birth_date' => '1990-01-01',
            'nationality' => 'Togolaise',
            'document_type' => 'passport',
            'document_number' => 'ADMIN001',
            'document_front' => 'kyc/admin_front.jpg',
            'selfie' => 'kyc/admin_selfie.jpg',
            'status' => 'verified',
            'verified_at' => now(),
        ]);

        // Vendeur test
        $seller = User::create([
            'name' => 'Vendeur Demo',
            'email' => 'solutionneurs228@gmail.com',
            'phone' => '+22891111111',
            'password' => Hash::make('Solutionneurs23'),
            'role' => 'seller',
            'kyc_status' => 'verified',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        Kyc::create([
            'user_id' => $seller->id,
            'full_name' => 'Vendeur Demo',
            'birth_date' => '1985-05-15',
            'nationality' => 'Togolaise',
            'document_type' => 'cni',
            'document_number' => 'TG-CNI-123456',
            'document_front' => 'kyc/seller_front.jpg',
            'selfie' => 'kyc/seller_selfie.jpg',
            'status' => 'verified',
            'verified_at' => now(),
        ]);

        // Acheteur test
        $buyer = User::create([
            'name' => 'Acheteur Demo',
            'email' => 'mtcdigit@gmail.com',
            'phone' => '+22892222222',
            'password' => Hash::make('Mtcdigit@23'),
            'role' => 'buyer',
            'kyc_status' => 'verified',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        Kyc::create([
            'user_id' => $buyer->id,
            'full_name' => 'Acheteur Demo',
            'birth_date' => '1992-08-20',
            'nationality' => 'Togolaise',
            'document_type' => 'cni',
            'document_number' => 'TG-CNI-789012',
            'document_front' => 'kyc/buyer_front.jpg',
            'selfie' => 'kyc/buyer_selfie.jpg',
            'status' => 'verified',
            'verified_at' => now(),
        ]);
    }
}
