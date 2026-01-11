<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Booking', function (Blueprint $table) {
            $table->id('boarding_id');
            $table->unsignedBigInteger('client_id');
            $table->date('boarding_date');
            $table->datetime('start_datetime');
            $table->datetime('end_datetime');
            $table->string('pickup_location', 255);
            $table->string('dropoff_location', 255);
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->decimal('total_price', 10, 2);
            $table->unsignedBigInteger('status_id')->default(1); // 1 = pending
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->text('special_requests')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('client_id')->references('Editor_id')->on('Client');
            $table->foreign('driver_id')->references('Driver_id')->on('Driver');
            $table->foreign('status_id')->references('status_id')->on('BookingStatus');
            $table->foreign('created_by')->references('Next_id')->on('User');
            $table->foreign('updated_by')->references('Next_id')->on('User');
        });

        // Create BookingVehicle table
        Schema::create('BookingVehicle', function (Blueprint $table) {
            $table->id('booking_vehicle_id');
            $table->unsignedBigInteger('booking_id');
            $table->unsignedBigInteger('vehicle_id');
            $table->unsignedBigInteger('assigned_by')->nullable();
            $table->datetime('assigned_at')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->foreign('booking_id')->references('boarding_id')->on('bookings');
            $table->foreign('vehicle_id')->references('vehicle_id')->on('vehicles');
            $table->foreign('assigned_by')->references('Next_id')->on('User');
        });

        // Create BookingStatus table if not exists
        if (!Schema::hasTable('BookingStatus')) {
            Schema::create('BookingStatus', function (Blueprint $table) {
                $table->id('status_id');
                $table->string('status_name', 50);
                $table->string('color', 20)->default('#6B7280');
                $table->timestamps();
            });

            // Insert default statuses
            DB::table('BookingStatus')->insert([
                ['status_name' => 'Pending', 'color' => '#3B82F6'],
                ['status_name' => 'Confirmed', 'color' => '#10B981'],
                ['status_name' => 'Ongoing', 'color' => '#F59E0B'],
                ['status_name' => 'Completed', 'color' => '#6B7280'],
                ['status_name' => 'Cancelled', 'color' => '#EF4444'],
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('BookingVehicle');
        Schema::dropIfExists('Booking');
        Schema::dropIfExists('BookingStatus');
    }
};