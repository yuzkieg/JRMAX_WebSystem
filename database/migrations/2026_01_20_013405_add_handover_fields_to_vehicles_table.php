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
        Schema::table('vehicles', function (Blueprint $table) {
            // Optional: Add quick reference fields for current rental status
            // Primary tracking is in selfdriver_rented_vehicles table
            $table->unsignedBigInteger('released_by')->nullable()->after('is_available');
            $table->unsignedBigInteger('received_by')->nullable()->after('released_by');
            $table->unsignedBigInteger('picked_up_by_client_id')->nullable()->after('received_by');
            $table->unsignedBigInteger('dropped_off_by_client_id')->nullable()->after('picked_up_by_client_id');
            $table->timestamp('released_at')->nullable()->after('dropped_off_by_client_id');
            $table->timestamp('returned_at')->nullable()->after('released_at');
            
            // Foreign keys
            $table->foreign('released_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('received_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('picked_up_by_client_id')->references('Editor_id')->on('Client')->onDelete('set null');
            $table->foreign('dropped_off_by_client_id')->references('Editor_id')->on('Client')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropForeign(['released_by']);
            $table->dropForeign(['received_by']);
            $table->dropForeign(['picked_up_by_client_id']);
            $table->dropForeign(['dropped_off_by_client_id']);
            $table->dropColumn([
                'released_by',
                'received_by',
                'picked_up_by_client_id',
                'dropped_off_by_client_id',
                'released_at',
                'returned_at'
            ]);
        });
    }
};
