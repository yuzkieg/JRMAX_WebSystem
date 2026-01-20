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
                'color' => '#3B82F6',
                'description' => 'Booking is pending approval',
            ],
            [
                'status_name' => 'Confirmed',
                'color' => '#10B981',
                'description' => 'Booking has been confirmed',
            ],
            [
                'status_name' => 'Ongoing',
                'color' => '#F59E0B',
                'description' => 'Booking is currently active',
            ],
            [
                'status_name' => 'Completed',
                'color' => '#6B7280',
                'description' => 'Booking has been completed',
            ],
            [
                'status_name' => 'Cancelled',
                'color' => '#EF4444',
                'description' => 'Booking has been cancelled',
            ],
        ];

        foreach ($statuses as $status) {
            DB::table('bookingstatus')->updateOrInsert(
                ['status_name' => $status['status_name']], // unique key
                [
                    'color' => $status['color'],
                    'description' => $status['description'],
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        $this->command->info('✅ Booking statuses seeded successfully!');
    }
}
