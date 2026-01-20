<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('selfdriver_rented_vehicles', function (Blueprint $table) {
            $table->id('rental_id');
            $table->unsignedBigInteger('vehicle_id');
            $table->unsignedBigInteger('booking_id')->nullable(); // Link to booking if applicable
            $table->unsignedBigInteger('released_by')->nullable(); // Fleet Assistant User ID who released the vehicle
            $table->unsignedBigInteger('received_by')->nullable(); // Fleet Assistant User ID who received the vehicle back
            $table->unsignedBigInteger('picked_up_by_client_id')->nullable(); // Client ID who picked up
            $table->unsignedBigInteger('dropped_off_by_client_id')->nullable(); // Client ID who dropped off
            $table->timestamp('released_at')->nullable();
            $table->timestamp('returned_at')->nullable();
            $table->enum('status', ['on_client', 'available', 'maintenance'])->default('available');
            $table->text('release_notes')->nullable();
            $table->text('return_notes')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('vehicle_id')->references('vehicle_id')->on('vehicles')->onDelete('cascade');
            $table->foreign('booking_id')->references('boarding_id')->on('bookings')->onDelete('set null');
            $table->foreign('released_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('received_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('picked_up_by_client_id')->references('Editor_id')->on('Client')->onDelete('set null');
            $table->foreign('dropped_off_by_client_id')->references('Editor_id')->on('Client')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('selfdriver_rented_vehicles');
    }
};
