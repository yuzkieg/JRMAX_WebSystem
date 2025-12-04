<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('vehicles')->insert([
            [
                'plate_num'     => 'ABC1234',
                'brand'         => 'Toyota',
                'model'         => 'Fortuner',
                'year'          => 2020,
                'body_type'     => 'SUV',
                'seat_cap'      => 7,
                'transmission'  => 'Automatic',
                'fuel_type'     => 'Diesel',
                'color'         => 'White',
                'price_rate'    => 3500.00,
                'driver'        => 1,   // If you want sample driver you can change this
                'added_by'      => 12,      // Must exist in users table
                'updated_by'    => 12,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'plate_num'     => 'DEF5678',
                'brand'         => 'Honda',
                'model'         => 'Civic',
                'year'          => 2019,
                'body_type'     => 'Sedan',
                'seat_cap'      => 5,
                'transmission'  => 'Manual',
                'fuel_type'     => 'Gasoline',
                'color'         => 'Black',
                'price_rate'    => 2500.00,
                'driver'        => 1,
                'added_by'      => 12,
                'updated_by'    => 12,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'plate_num'     => 'GHI9012',
                'brand'         => 'Nissan',
                'model'         => 'Urvan',
                'year'          => 2021,
                'body_type'     => 'Van',
                'seat_cap'      => 12,
                'transmission'  => 'Manual',
                'fuel_type'     => 'Diesel',
                'color'         => 'Silver',
                'price_rate'    => 4500.00,
                'driver'        => 1,
                'added_by'      => 12,
                'updated_by'    => 12,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ]);
    }
}
