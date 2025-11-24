<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    public function run()
    {
        // Define employees array inside the run() method
        $employees = [
            [
                'name' => 'John Doe',
                'email' => 'johndoe@example.com',
                'password' => Hash::make('password123'), // change in production
                'role' => 'employee',
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'janesmith@example.com',
                'password' => Hash::make('password123'),
                'role' => 'employee',
            ],
            [
                'name' => 'Mark Johnson',
                'email' => 'markjohnson@example.com',
                'password' => Hash::make('password123'),
                'role' => 'employee',
            ],
        ];

        // Loop through employees and create or update
        foreach ($employees as $data) {
            User::updateOrCreate(
                ['email' => $data['email']], // unique key
                $data
            );
        }

        $this->command->info('Employees seeded successfully!');
    }
}
