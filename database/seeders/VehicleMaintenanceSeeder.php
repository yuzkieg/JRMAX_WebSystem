<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehicle;
use App\Models\User;
use App\Models\VehicleMaintenance;
use Carbon\Carbon;

class VehicleMaintenanceSeeder extends Seeder
{
    public function run(): void
    {
        // Get some vehicles and users
        $vehicles = Vehicle::take(3)->get();
        $users = User::whereIn('role', ['admin', 'fleet_assistant'])->take(2)->get();

        if ($vehicles->isEmpty() || $users->isEmpty()) {
            $this->command->info('No vehicles or users found. Please seed vehicles and users first.');
            return;
        }

        $maintenanceTypes = ['repair', 'check-up', 'oil change', 'tire replacement', 'engine service', 'cleaning', 'other'];
        $statuses = ['scheduled', 'in progress', 'completed', 'cancelled'];

        $maintenanceRecords = [
            [
                'vehicle_ID' => $vehicles[0]->vehicle_id,
                'reported_by' => $users[0]->id,
                'maintenance_type' => 'oil change',
                'description' => 'Regular oil change and filter replacement',
                'odometer_reading' => 15000,
                'scheduled_date' => Carbon::now()->subDays(5),
                'started_at' => Carbon::now()->subDays(5),
                'completed_at' => Carbon::now()->subDays(4),
                'cost' => 89.99,
                'status' => 'completed',
            ],
            [
                'vehicle_ID' => $vehicles[0]->vehicle_id,
                'reported_by' => $users[0]->id,
                'maintenance_type' => 'tire replacement',
                'description' => 'Replace all 4 tires with new ones',
                'odometer_reading' => 30000,
                'scheduled_date' => Carbon::now()->addDays(7),
                'started_at' => null,
                'completed_at' => null,
                'cost' => 450.00,
                'status' => 'scheduled',
            ],
            [
                'vehicle_ID' => $vehicles[1]->vehicle_id,
                'reported_by' => $users[1]->id,
                'maintenance_type' => 'repair',
                'description' => 'Brake pad replacement and brake fluid flush',
                'odometer_reading' => 25000,
                'scheduled_date' => Carbon::now()->subDays(2),
                'started_at' => Carbon::now()->subDays(1),
                'completed_at' => null,
                'cost' => 220.50,
                'status' => 'in progress',
            ],
            [
                'vehicle_ID' => $vehicles[2]->vehicle_id,
                'reported_by' => $users[0]->id,
                'maintenance_type' => 'check-up',
                'description' => 'Regular 6-month check-up',
                'odometer_reading' => 18000,
                'scheduled_date' => Carbon::now()->addDays(3),
                'started_at' => null,
                'completed_at' => null,
                'cost' => 75.00,
                'status' => 'scheduled',
            ],
            [
                'vehicle_ID' => $vehicles[1]->vehicle_id,
                'reported_by' => $users[1]->id,
                'maintenance_type' => 'engine service',
                'description' => 'Major engine service including timing belt',
                'odometer_reading' => 60000,
                'scheduled_date' => Carbon::now()->subDays(10),
                'started_at' => Carbon::now()->subDays(10),
                'completed_at' => Carbon::now()->subDays(8),
                'cost' => 850.00,
                'status' => 'completed',
            ],
        ];

        foreach ($maintenanceRecords as $record) {
            VehicleMaintenance::create($record);
        }

        $this->command->info('Vehicle maintenance records seeded successfully!');
    }
}