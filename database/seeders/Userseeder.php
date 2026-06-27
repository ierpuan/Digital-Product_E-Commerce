<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name'              => 'Administrator',
            'email'             => 'admin@digitalstore.com',
            'password'          => Hash::make('password'),
            'role'              => 'admin',
            'email_verified_at' => now(),
        ]);

        // Customer dummy
        $customers = [
            ['name' => 'Budi Santoso',   'email' => 'budi@gmail.com'],
            ['name' => 'Siti Rahayu',    'email' => 'siti@gmail.com'],
            ['name' => 'Ahmad Fauzi',    'email' => 'ahmad@gmail.com'],
            ['name' => 'Dewi Lestari',   'email' => 'dewi@gmail.com'],
            ['name' => 'Rizky Pratama',  'email' => 'rizky@gmail.com'],
        ];

        foreach ($customers as $customer) {
            User::create([
                'name'              => $customer['name'],
                'email'             => $customer['email'],
                'password'          => Hash::make('password'),
                'role'              => 'customer',
                'email_verified_at' => now(),
            ]);
        }
    }
}
