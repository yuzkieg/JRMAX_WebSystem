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
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('boarding_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Re-add the column if the migration is rolled back
            // You might want to adjust its position if it was critical
            // For simplicity, we'll add it at the end here.
            $table->date('boarding_date')->after('client_id'); // Assuming its original position was after client_id
        });
    }
};
