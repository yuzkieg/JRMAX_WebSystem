<?php

// database/seeders/UserSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'booking@example.com'],
            [
                'name' => 'Booking Officer',
                'password' => Hash::make('booking123'),
                'role' => 'booking_officer',
            ]
        );

        User::updateOrCreate(
            ['email' => 'fleet@example.com'],
            [
                'name' => 'Fleet Assistant',
                'password' => Hash::make('fleet123'),
                'role' => 'fleet_assistant',
            ]
        );
    }
}
