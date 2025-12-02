<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DriversSeeder extends Seeder
{
    public function run()
    {
        $drivers = [
            [
                'name' => 'Daniel Driver',
                'email' => 'daniel.driver@example.com',
                'license_num' => 'DL-2025-001',
                'dateadded' => now(),
            ],
            [
                'name' => 'Eva Wheels',
                'email' => 'eva.wheels@example.com',
                'license_num' => 'DL-2025-002',
                'dateadded' => now(),
            ],
        ];

        foreach ($drivers as $driver) {
            DB::table('drivers')->insert([
                'name' => $driver['name'],
                'email' => $driver['email'],
                'license_num' => $driver['license_num'],
                'dateadded' => $driver['dateadded'],
            ]);
        }
    }
}
