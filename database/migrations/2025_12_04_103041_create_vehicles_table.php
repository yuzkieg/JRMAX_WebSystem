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
    Schema::create('vehicles', function (Blueprint $table) {
        $table->id('vehicle_id');

        $table->string('plate_num', 7)->unique();
        $table->string('brand', 20);
        $table->string('model', 20)->unique();
        $table->year('year');
        $table->string('body_type', 10);
        $table->integer('seat_cap');
        $table->string('transmission', 20);
        $table->string('fuel_type', 10);
        $table->string('color', 10);
        $table->decimal('price_rate', 8, 2);

        // Foreign keys
        $table->unsignedBigInteger('driver')->nullable();
        $table->foreign('driver')->references('id')->on('drivers')->nullOnDelete();

        $table->unsignedBigInteger('added_by')->nullable();
        $table->unsignedBigInteger('updated_by')->nullable();

        $table->foreign('added_by')->references('id')->on('users')->nullOnDelete();
        $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();



        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('vehicles');
}
};