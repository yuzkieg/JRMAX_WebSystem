<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdminEmail = env('SUPERADMIN_EMAIL', 'masterman2005@gmail.com');

        // Check if superadmin already exists
        $user = User::where('email', $superAdminEmail)->first();

        if (!$user) {
            User::create([
                'name' => env('SUPERADMIN_NAME', 'Master'),
                'email' => $superAdminEmail,
                'password' => Hash::make(env('SUPERADMIN_PASSWORD', '619181517')),
                'role' => 'superadmin',

            ]);

            $this->command->info("Superadmin user created successfully!");
        } else {
            $this->command->info("Superadmin already exists.");
        }
    }
}
