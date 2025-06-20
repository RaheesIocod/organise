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
            // Add half day support columns
            $table->boolean('is_half_day')->default(false)->after('days_count');
            $table->enum('half_day_type', ['morning', 'afternoon'])->nullable()->after('is_half_day');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leave_applications', function (Blueprint $table) {
            $table->dropColumn(['is_half_day', 'half_day_type']);
        });
    }
};
