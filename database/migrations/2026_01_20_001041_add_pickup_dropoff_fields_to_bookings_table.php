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
            // Pick-up fields (for self-drive vehicles)
            $table->unsignedBigInteger('sent_by')->nullable()->after('pickup_type'); // Fleet Assistant User ID
            $table->string('received_by')->nullable()->after('sent_by'); // Client Name/Signature
            
            // Drop-off fields (for self-drive vehicles)
            $table->unsignedBigInteger('collected_by')->nullable()->after('received_by'); // Fleet Assistant User ID
            $table->string('returned_by')->nullable()->after('collected_by'); // Client Name/Signature
            
            // Foreign keys
            $table->foreign('sent_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('collected_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['sent_by']);
            $table->dropForeign(['collected_by']);
            $table->dropColumn(['sent_by', 'received_by', 'collected_by', 'returned_by']);
        });
    }
};
