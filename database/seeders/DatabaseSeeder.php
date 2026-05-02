<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Administrateur PayXora',
            'email' => 'admin@payxora.tg',
            'phone' => '+22890123456',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Demo seller
        User::create([
            'name' => 'Vendeur Demo',
            'email' => 'seller@payxora.tg',
            'phone' => '+22890123457',
            'password' => Hash::make('password'),
            'role' => 'user',
            'is_active' => true,
        ]);

        // Demo buyer
        User::create([
            'name' => 'Acheteur Demo',
            'email' => 'buyer@payxora.tg',
            'phone' => '+22890123458',
            'password' => Hash::make('password'),
            'role' => 'user',
            'is_active' => true,
        ]);

        $this->command->info('Users seeded successfully!');
        $this->command->info('Admin: admin@payxora.tg / password');
        $this->command->info('Seller: seller@payxora.tg / password');
        $this->command->info('Buyer: buyer@payxora.tg / password');
    }
}
