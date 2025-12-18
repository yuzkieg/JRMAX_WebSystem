<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // For SQL Server, you need to drop the index
        Schema::table('vehicles', function (Blueprint $table) {
            // Try dropping the unique constraint
            $table->dropUnique(['model']);
        });
    }

    public function down(): void
    {
        // Re-add the unique constraint if needed
        Schema::table('vehicles', function (Blueprint $table) {
            $table->unique('model');
        });
    }
};