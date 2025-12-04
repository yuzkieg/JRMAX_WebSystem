<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_maintenance', function (Blueprint $table) {
            $table->id('maintenance_ID');
            $table->unsignedBigInteger('vehicle_ID');
            $table->unsignedBigInteger('reported_by');
            $table->enum('maintenance_type', [
                'repair', 'check-up', 'oil change', 'tire replacement', 
                'engine service', 'cleaning', 'other'
            ]);
            $table->text('description')->nullable();
            $table->integer('odometer_reading')->nullable();
            $table->date('scheduled_date')->nullable();
            $table->dateTime('started_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->decimal('cost', 8, 2);
            $table->enum('status', [
                'scheduled', 'in progress', 'completed', 'cancelled'
            ])->default('scheduled');
            $table->timestamps();

            // Foreign keys
            $table->foreign('vehicle_ID')->references('vehicle_ID')->on('vehicles')->onDelete('cascade');
            $table->foreign('reported_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_maintenance');
    }
};