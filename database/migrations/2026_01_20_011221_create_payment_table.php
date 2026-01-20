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
        Schema::create('Payment', function (Blueprint $table) {
            $table->id('payment_id');
            $table->unsignedBigInteger('booking_id');
            $table->decimal('amount', 10, 2);
            $table->unsignedBigInteger('payment_method_id')->nullable();
            $table->unsignedBigInteger('payment_status_id')->nullable();
            $table->string('reference_number')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->unsignedBigInteger('issued_by')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('booking_id')->references('boarding_id')->on('bookings')->onDelete('cascade');
            $table->foreign('issued_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Payment');
    }
};
