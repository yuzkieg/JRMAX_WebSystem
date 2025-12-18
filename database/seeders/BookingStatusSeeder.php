<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookingStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'status_name' => 'Pending',
                'color' => '#3B82F6', // Blue
                'description' => 'Booking is pending approval',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'status_name' => 'Confirmed',
                'color' => '#10B981', // Green
                'description' => 'Booking has been confirmed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'status_name' => 'Ongoing',
                'color' => '#F59E0B', // Yellow
                'description' => 'Booking is currently active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'status_name' => 'Completed',
                'color' => '#6B7280', // Gray
                'description' => 'Booking has been completed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'status_name' => 'Cancelled',
                'color' => '#EF4444', // Red
                'description' => 'Booking has been cancelled',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('BookingStatus')->insert($statuses);
        
        $this->command->info('✅ Booking statuses seeded successfully!');
    }
}