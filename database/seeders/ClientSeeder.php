<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = [
            [
                'first_name' => 'John',
                'last_name' => 'Smith',
                'email' => 'john.smith@email.com',
                'contact_number' => '+639171234567',
                'license_number' => 'L123456789',
                'address' => '123 Main Street, Manila City',
                'status_id' => 1,
                'emergency_contact' => '+639181234567',
                'notes' => 'Corporate client, prefers SUVs',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Maria',
                'last_name' => 'Garcia',
                'email' => 'maria.garcia@email.com',
                'contact_number' => '+639172345678',
                'license_number' => 'L987654321',
                'address' => '456 Oak Avenue, Quezon City',
                'status_id' => 1,
                'emergency_contact' => '+639182345678',
                'notes' => 'Frequent traveler, requires child seats',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'David',
                'last_name' => 'Chen',
                'email' => 'david.chen@email.com',
                'contact_number' => '+639173456789',
                'license_number' => 'L456789123',
                'address' => '789 Pine Road, Makati City',
                'status_id' => 1,
                'emergency_contact' => '+639183456789',
                'notes' => 'Business trips, prefers luxury vehicles',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Tan',
                'email' => 'sarah.tan@email.com',
                'contact_number' => '+639174567890',
                'license_number' => 'L789123456',
                'address' => '321 Elm Street, Taguig City',
                'status_id' => 1,
                'emergency_contact' => '+639184567890',
                'notes' => 'Family trips, needs minivan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Michael',
                'last_name' => 'Santos',
                'email' => 'michael.santos@email.com',
                'contact_number' => '+639175678901',
                'license_number' => 'L321654987',
                'address' => '654 Maple Drive, Pasig City',
                'status_id' => 1,
                'emergency_contact' => '+639185678901',
                'notes' => 'Weekend getaways',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Anna',
                'last_name' => 'Lim',
                'email' => 'anna.lim@email.com',
                'contact_number' => '+639176789012',
                'license_number' => 'L654987321',
                'address' => '987 Cedar Lane, Mandaluyong City',
                'status_id' => 1,
                'emergency_contact' => '+639186789012',
                'notes' => 'Airport transfers',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Robert',
                'last_name' => 'Gonzales',
                'email' => 'robert.gonzales@email.com',
                'contact_number' => '+639177890123',
                'license_number' => 'L147258369',
                'address' => '147 Birch Court, Paranaque City',
                'status_id' => 1,
                'emergency_contact' => '+639187890123',
                'notes' => 'Long-term rental',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Lisa',
                'last_name' => 'Wang',
                'email' => 'lisa.wang@email.com',
                'contact_number' => '+639178901234',
                'license_number' => 'L258369147',
                'address' => '258 Spruce Way, Las Pinas City',
                'status_id' => 1,
                'emergency_contact' => '+639188901234',
                'notes' => 'Event transportation',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'James',
                'last_name' => 'Lee',
                'email' => 'james.lee@email.com',
                'contact_number' => '+639179012345',
                'license_number' => 'L369147258',
                'address' => '369 Fir Avenue, Muntinlupa City',
                'status_id' => 1,
                'emergency_contact' => '+639189012345',
                'notes' => 'Wedding service',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Michelle',
                'last_name' => 'Reyes',
                'email' => 'michelle.reyes@email.com',
                'contact_number' => '+639170123456',
                'license_number' => 'L852741963',
                'address' => '852 Redwood Blvd, Pasay City',
                'status_id' => 1,
                'emergency_contact' => '+639180123456',
                'notes' => 'Corporate events',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('Client')->insert($clients);
        
        $this->command->info('✅ Clients seeded successfully!');
        $this->command->info('👥 Total clients: ' . count($clients));
    }
}