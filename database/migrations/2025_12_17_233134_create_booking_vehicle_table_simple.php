<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('BookingVehicle')) {
            Schema::create('BookingVehicle', function (Blueprint $table) {
                $table->id('booking_vehicle_id');
                $table->unsignedBigInteger('booking_id');
                $table->unsignedBigInteger('vehicle_id');
                $table->unsignedBigInteger('assigned_by')->nullable();
                $table->datetime('assigned_at')->nullable();
                $table->text('remarks')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('BookingVehicle');
    }
};