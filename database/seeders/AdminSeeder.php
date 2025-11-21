<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Prevent duplicate seeding: create or update by email
        $admins = [
            [
                'name' => 'Admin One',
                'email' => 'admin1@example.com',
                'password' => Hash::make('password'), // change in production
                'role' => 'admin',
            ],
            [
                'name' => 'Admin Two',
                'email' => 'admin2@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ],
            [
                'name' => 'Admin Three',
                'email' => 'admin3@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ],
        ];

        foreach ($admins as $data) {
            // updateOrCreate ensures idempotent seeding
            User::updateOrCreate(
                ['email' => $data['email']],
                $data
            );
        }
    }
}
