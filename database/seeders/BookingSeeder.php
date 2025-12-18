<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, get client IDs and vehicle IDs
        $clientIds = DB::table('Client')->pluck('Editor_id')->toArray();
        $vehicleIds = DB::table('vehicles')->pluck('vehicle_id')->toArray();
        $driverIds = DB::table('drivers')->pluck('id')->toArray();
        
        if (empty($vehicleIds)) {
            $this->command->warn('⚠️ No vehicles found in database. Please seed vehicles first.');
            return;
        }
        
        if (empty($clientIds)) {
            $this->command->warn('⚠️ No clients found in database. Please seed clients first.');
            return;
        }

        $bookings = [];
        $now = now();

        // Create sample bookings for the next 30 days
        for ($i = 1; $i <= 20; $i++) {
            $startDate = Carbon::now()->addDays(rand(1, 30));
            $endDate = (clone $startDate)->addDays(rand(1, 7));
            
            $statusId = $this->getRandomStatusId($startDate);
            
            $bookings[] = [
                'client_id' => $clientIds[array_rand($clientIds)],
                'boarding_date' => $startDate->format('Y-m-d'),
                'start_datetime' => $startDate->format('Y-m-d H:i:s'),
                'end_datetime' => $endDate->format('Y-m-d H:i:s'),
                'pickup_location' => $this->getRandomLocation(),
                'dropoff_location' => $this->getRandomLocation(),
                'driver_id' => !empty($driverIds) ? $driverIds[array_rand($driverIds)] : null,
                'total_price' => $this->calculatePrice($startDate, $endDate),
                'status_id' => $statusId,
                'special_requests' => $this->getRandomSpecialRequest(),
                'created_by' => 1, // Assuming admin user ID 1
                'updated_by' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Insert bookings
        foreach ($bookings as $booking) {
            $bookingId = DB::table('bookings')->insertGetId($booking);
            
            // Assign 1-3 random vehicles to each booking
            $numVehicles = rand(1, min(3, count($vehicleIds)));
            $selectedVehicles = array_rand($vehicleIds, $numVehicles);
            
            if (!is_array($selectedVehicles)) {
                $selectedVehicles = [$selectedVehicles];
            }
            
            foreach ($selectedVehicles as $vehicleIndex) {
                DB::table('BookingVehicle')->insert([
                    'booking_id' => $bookingId,
                    'vehicle_id' => $vehicleIds[$vehicleIndex],
                    'assigned_by' => 1,
                    'assigned_at' => $now,
                    'remarks' => 'Auto-assigned by seeder',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                
                // Update vehicle availability if booking is confirmed or ongoing
                if (in_array($booking['status_id'], [2, 3])) {
                    DB::table('vehicles')
                        ->where('vehicle_id', $vehicleIds[$vehicleIndex])
                        ->update(['is_available' => false]);
                }
            }
        }

        $this->command->info('✅ Bookings seeded successfully!');
        $this->command->info('📅 Total bookings created: ' . count($bookings));
    }

    /**
     * Get random status ID based on date
     */
    private function getRandomStatusId($startDate): int
    {
        $now = Carbon::now();
        
        if ($startDate->isPast()) {
            // Past bookings are either completed or cancelled
            return rand(0, 1) ? 4 : 5; // 4 = Completed, 5 = Cancelled
        } elseif ($startDate->isToday()) {
            // Today's bookings could be ongoing or confirmed
            return rand(2, 3); // 2 = Confirmed, 3 = Ongoing
        } elseif ($startDate->diffInDays($now) <= 7) {
            // Next week's bookings are mostly confirmed
            return rand(1, 2); // 1 = Pending, 2 = Confirmed
        } else {
            // Future bookings are mostly pending
            return 1; // Pending
        }
    }

    /**
     * Get random location
     */
    private function getRandomLocation(): string
    {
        $locations = [
            'Ninoy Aquino International Airport (NAIA) Terminal 3',
            'SM Mall of Asia, Pasay City',
            'Bonifacio Global City, Taguig',
            'Makati Central Business District',
            'Greenbelt Shopping Center, Makati',
            'Robinson\'s Place Manila',
            'Megamall, Mandaluyong',
            'Quezon Memorial Circle',
            'Eastwood City, Quezon City',
            'Alabang Town Center, Muntinlupa',
            'Resorts World Manila',
            'Newport City, Pasay',
            'Uptown Mall, BGC',
            'Market! Market!, Taguig',
            'Power Plant Mall, Rockwell'
        ];
        
        return $locations[array_rand($locations)];
    }

    /**
     * Calculate price based on duration
     */
    private function calculatePrice($startDate, $endDate): float
    {
        $days = $startDate->diffInDays($endDate);
        $baseRate = rand(2000, 5000); // Base daily rate between 2000-5000
        return $baseRate * max(1, $days);
    }

    /**
     * Get random special request
     */
    private function getRandomSpecialRequest(): ?string
    {
        $requests = [
            null,
            'Please ensure the vehicle is clean and sanitized.',
            'Need child seat for 3-year-old.',
            'Driver must speak English.',
            'Please pick up 15 minutes earlier.',
            'Need extra space for luggage.',
            'Vehicle must have GPS navigation.',
            'Please provide water bottles.',
            'Need receipt for corporate reimbursement.',
            'Driver should wait at the arrival area with name sign.',
            'Prefer non-smoking vehicle.',
            'Need assistance with luggage.',
            'Please avoid toll roads if possible.',
            'Vehicle must have Bluetooth connectivity.',
        ];
        
        return $requests[array_rand($requests)];
    }
}