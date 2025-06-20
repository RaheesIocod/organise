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
            // Rename the columns to match our new model
            if (Schema::hasColumn('leave_applications', 'reviewed_by')) {
                $table->renameColumn('reviewed_by', 'approved_by');
            }

            if (Schema::hasColumn('leave_applications', 'reviewed_at')) {
                $table->renameColumn('reviewed_at', 'approved_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leave_applications', function (Blueprint $table) {
            if (Schema::hasColumn('leave_applications', 'approved_by')) {
                $table->renameColumn('approved_by', 'reviewed_by');
            }

            if (Schema::hasColumn('leave_applications', 'approved_at')) {
                $table->renameColumn('approved_at', 'reviewed_at');
            }
        });
    }
};
