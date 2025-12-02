<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('drivers', function (Blueprint $table) {
    $table->id();
    $table->string('name', 100);
    $table->string('email', 100)->unique();
    $table->string('license_num', 13)->unique();
    $table->date('dateadded'); 
    $table->timestamps();
});
    }

    public function down(): void {
        Schema::dropIfExists('drivers');
    }
};
