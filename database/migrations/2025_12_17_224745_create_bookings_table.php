<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create Client table if not exists
        if (!Schema::hasTable('Client')) {
            Schema::create('Client', function (Blueprint $table) {
                $table->id('Editor_id');
                $table->string('first_name', 100);
                $table->string('last_name', 100);
                $table->string('contact_number', 20);
                $table->string('email')->unique();
                $table->string('license_number', 50)->unique();
                $table->text('address')->nullable();
                $table->unsignedBigInteger('status_id')->default(1);
                $table->string('emergency_contact', 20)->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 2. Create BookingStatus table if not exists
        if (!Schema::hasTable('BookingStatus')) {
            Schema::create('BookingStatus', function (Blueprint $table) {
                $table->id('status_id');
                $table->string('status_name', 50);
                $table->string('color', 20)->default('#6B7280');
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // 3. Create Booking table if not exists
        if (!Schema::hasTable('Booking')) {
            Schema::create('bookings', function (Blueprint $table) {
                $table->id('boarding_id');
                $table->unsignedBigInteger('client_id'); // Add here
                $table->date('boarding_date');
                $table->datetime('start_datetime');
                $table->datetime('end_datetime');
                $table->string('pickup_location', 255);
                $table->string('dropoff_location', 255);
                $table->unsignedBigInteger('driver_id')->nullable();
                $table->decimal('total_price', 10, 2);
                $table->unsignedBigInteger('status_id')->default(1);
                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->text('special_requests')->nullable();
                $table->timestamps();
            });
        }

        // 4. Create BookingVehicle table if not exists
        if (!Schema::hasTable('BookingVehicle')) {
            Schema::create('BookingVehicle', function (Blueprint $table) {
                $table->id('booking_vehicle_id');
                $table->unsignedBigInteger('booking_id');
                $table->unsignedBigInteger('vehicle_id');
                $table->unsignedBigInteger('assigned_by')->nullable();
                $table->datetime('assigned_at')->nullable();
                $table->text('remarks')->nullable();
                $table->timestamps();

                // Foreign keys will be added separately
            });
        }

        // 5. Add foreign keys (check if columns exist first)
        $this->addForeignKeys();
    }

    public function down(): void
    {
        // Drop in reverse order
        Schema::dropIfExists('BookingVehicle');
        Schema::dropIfExists('Booking');
        Schema::dropIfExists('BookingStatus');
        Schema::dropIfExists('Client');
    }

    /**
     * Add foreign keys to tables
     */
    private function addForeignKeys(): void
    {
        // Add foreign keys to Booking table
        if (Schema::hasTable('Booking') && Schema::hasTable('Client')) {
            Schema::table('Booking', function (Blueprint $table) {
                if (!Schema::hasColumn('Booking', 'client_id_foreign')) {
                    $table->foreign('client_id')
                          ->references('Editor_id')
                          ->on('Client')
                          ->onDelete('cascade');
                }
            });
        }

        if (Schema::hasTable('Booking') && Schema::hasTable('Driver')) {
            Schema::table('Booking', function (Blueprint $table) {
                if (!Schema::hasColumn('Booking', 'driver_id_foreign')) {
                    $table->foreign('driver_id')
                          ->references('Driver_id')
                          ->on('Driver')
                          ->onDelete('set null');
                }
            });
        }

        if (Schema::hasTable('Booking') && Schema::hasTable('BookingStatus')) {
            Schema::table('Booking', function (Blueprint $table) {
                if (!Schema::hasColumn('Booking', 'status_id_foreign')) {
                    $table->foreign('status_id')
                          ->references('status_id')
                          ->on('BookingStatus');
                }
            });
        }

        if (Schema::hasTable('Booking') && Schema::hasTable('User')) {
            Schema::table('Booking', function (Blueprint $table) {
                if (!Schema::hasColumn('Booking', 'created_by_foreign')) {
                    $table->foreign('created_by')
                          ->references('Next_id')
                          ->on('User')
                          ->onDelete('set null');
                }
                
                if (!Schema::hasColumn('Booking', 'updated_by_foreign')) {
                    $table->foreign('updated_by')
                          ->references('Next_id')
                          ->on('User')
                          ->onDelete('set null');
                }
            });
        }

        // Add foreign keys to BookingVehicle table
        if (Schema::hasTable('BookingVehicle') && Schema::hasTable('Booking')) {
            Schema::table('BookingVehicle', function (Blueprint $table) {
                if (!Schema::hasColumn('BookingVehicle', 'booking_id_foreign')) {
                    $table->foreign('booking_id')
                          ->references('boarding_id')
                          ->on('Booking')
                          ->onDelete('cascade');
                }
            });
        }

        if (Schema::hasTable('BookingVehicle') && Schema::hasTable('Vehicle')) {
            Schema::table('BookingVehicle', function (Blueprint $table) {
                if (!Schema::hasColumn('BookingVehicle', 'vehicle_id_foreign')) {
                    $table->foreign('vehicle_id')
                          ->references('vehicle_id')
                          ->on('vehicles')
                          ->onDelete('cascade');
                }
            });
        }

        if (Schema::hasTable('BookingVehicle') && Schema::hasTable('User')) {
            Schema::table('BookingVehicle', function (Blueprint $table) {
                if (!Schema::hasColumn('BookingVehicle', 'assigned_by_foreign')) {
                    $table->foreign('assigned_by')
                          ->references('Next_id')
                          ->on('User')
                          ->onDelete('set null');
                }
            });
        }
    }
};