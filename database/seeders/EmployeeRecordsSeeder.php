<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmployeeRecordsSeeder extends Seeder
{
    public function run()
    {
        $employeesrecord = [
            [
                'name' => 'Alice Johnson',
                'email' => 'alice.johnson@example.com',
                'position' => 'Booking Officer',
            ],
            [
                'name' => 'Bob Martinez',
                'email' => 'bob.martinez@example.com',
                'position' => 'Fleet Assistant',
            ],
            [
                'name' => 'Carol Smith',
                'email' => 'carol.smith@example.com',
                'position' => 'Booking Officer',
            ],
        ];

        foreach ($employeesrecord as $employee) {
            DB::table('employee_records')->insert([
                'name' => $employee['name'],
                'email' => $employee['email'],
                'position' => $employee['position'],
            ]);
        }
    }
}
