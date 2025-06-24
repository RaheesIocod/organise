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
        Schema::table('leave_applications', function (Blueprint $table) {
            // Add time part columns for start and end dates
            $table->enum('start_time_part', ['morning', 'afternoon'])->default('morning')->after('half_day_type');
            $table->enum('end_time_part', ['morning', 'end_of_day'])->default('end_of_day')->after('start_time_part');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leave_applications', function (Blueprint $table) {
            $table->dropColumn(['start_time_part', 'end_time_part']);
        });
    }
};
