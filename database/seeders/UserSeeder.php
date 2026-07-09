<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kyc;
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
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        Kyc::create([
            'user_id' => $admin->id,
            'full_name' => 'Administrateur PayXora',
            'birth_date' => '1990-01-01',
            'nationality' => 'Togolaise',
            'document_type' => 'passport',
            'document_number' => 'ADMIN001',
            'document_front' => 'kyc/admin_front.jpg',
            'selfie' => 'kyc/admin_selfie.jpg',
            'status' => 'verified',
            'verified_at' => now(),
        ]);

        // Vendeur
        $seller = User::create([
            'name' => 'Vendeur Demo',
            'email' => 'solutionneurs228@gmail.com',
            'phone' => '+22890123457',
            'password' => Hash::make('Solutionneurs23'),
            'role' => 'seller',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        Kyc::create([
            'user_id' => $seller->id,
            'full_name' => 'Vendeur Demo',
            'birth_date' => '1988-03-10',
            'nationality' => 'Togolaise',
            'document_type' => 'cni',
            'document_number' => 'TG-CNI-123456',
            'document_front' => 'kyc/seller_front.jpg',
            'selfie' => 'kyc/seller_selfie.jpg',
            'status' => 'verified',
            'verified_at' => now(),
        ]);

        // Acheteur
        $buyer = User::create([
            'name' => 'Acheteur Demo',
            'email' => 'mtcdigit@gmail.com',
            'phone' => '+22890123458',
            'password' => Hash::make('Mtcdigit@23'),
            'role' => 'buyer',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        Kyc::create([
            'user_id' => $buyer->id,
            'full_name' => 'Acheteur Demo',
            'birth_date' => '1995-07-22',
            'nationality' => 'Togolaise',
            'document_type' => 'cni',
            'document_number' => 'TG-CNI-789012',
            'document_front' => 'kyc/buyer_front.jpg',
            'selfie' => 'kyc/buyer_selfie.jpg',
            'status' => 'verified',
            'verified_at' => now(),
        ]);

        // Utilisateur KYC pending
        $pending = User::create([
            'name' => 'Utilisateur KYC Pending',
            'email' => 'pending@payxora.tg',
            'phone' => '+22890123459',
            'password' => Hash::make('password'),
            'role' => 'buyer',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        Kyc::create([
            'user_id' => $pending->id,
            'full_name' => 'Utilisateur KYC Pending',
            'birth_date' => '1998-12-05',
            'nationality' => 'Togolaise',
            'document_type' => 'cni',
            'document_number' => 'TG-CNI-PENDING',
            'document_front' => 'kyc/pending_front.jpg',
            'selfie' => 'kyc/pending_selfie.jpg',
            'status' => 'pending',
        ]);
    }
}
