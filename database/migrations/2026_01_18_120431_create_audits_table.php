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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_name')->nullable(); // Store name for deleted users
            $table->string('action'); // create, update, delete, login, logout
            $table->string('module'); // Employees, Bookings, Vehicles, etc.
            $table->string('description');
            $table->text('old_values')->nullable(); // JSON of old values
            $table->text('new_values')->nullable(); // JSON of new values
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->unsignedBigInteger('related_id')->nullable(); // ID of affected record
            $table->string('related_type')->nullable(); // Model class name
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['action', 'created_at']);
            $table->index(['module', 'created_at']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};