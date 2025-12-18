<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('BookingStatus', function (Blueprint $table) {
            $table->id('status_id');
            $table->string('status_name', 50);
            $table->string('color', 20)->default('#6B7280');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('BookingStatus');
    }
};