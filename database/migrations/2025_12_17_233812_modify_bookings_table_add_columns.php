<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Rename 'id' to 'boarding_id' if needed
            // $table->renameColumn('id', 'boarding_id');
            
            // Add all the missing columns from your schema
            $table->unsignedBigInteger('client_id')->after('id');
            $table->date('boarding_date')->after('client_id');
            $table->datetime('start_datetime')->after('boarding_date');
            $table->datetime('end_datetime')->after('start_datetime');
            $table->string('pickup_location', 255)->after('end_datetime');
            $table->string('dropoff_location', 255)->after('pickup_location');
            $table->unsignedBigInteger('driver_id')->nullable()->after('dropoff_location');
            $table->decimal('total_price', 10, 2)->after('driver_id');
            $table->unsignedBigInteger('status_id')->default(1)->after('total_price');
            $table->unsignedBigInteger('created_by')->nullable()->after('status_id');
            $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
            $table->text('special_requests')->nullable()->after('updated_by');
            
            // Rename if you want to match your schema exactly
            $table->renameColumn('id', 'boarding_id');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Reverse the changes
            $table->renameColumn('boarding_id', 'id');
            
            $table->dropColumn([
                'client_id',
                'boarding_date',
                'start_datetime',
                'end_datetime',
                'pickup_location',
                'dropoff_location',
                'driver_id',
                'total_price',
                'status_id',
                'created_by',
                'updated_by',
                'special_requests'
            ]);
        });
    }
};