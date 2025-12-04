<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('employee_records', function (Blueprint $table) {
    $table->id();
    $table->string('name', 100);
    $table->string('email', 100)->unique();
    $table->string('position', 50); // use string instead of enum for SQL Server
    $table->timestamps();
});
    }

    public function down(): void {
        Schema::dropIfExists('employee_records');
    }
};
