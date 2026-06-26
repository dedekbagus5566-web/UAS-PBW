<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $accounts = [
            [
                'name' => 'Admin Demo',
                'email' => 'admin@example.com',
                'password' => 'admin12345',
            ],
            [
                'name' => 'Buyer Demo',
                'email' => 'buyer@example.com',
                'password' => 'buyer12345',
            ],
            [
                'name' => 'Seller Demo',
                'email' => 'seller@example.com',
                'password' => 'seller12345',
            ],
        ];

        foreach ($accounts as $account) {
            User::firstOrCreate(
                ['email' => $account['email']],
                [
                    'name' => $account['name'],
                    'password' => Hash::make($account['password']),
                    'api_token' => bin2hex(random_bytes(24)),
                ]
            );
        }
    }
}
