<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First check if renaming is required
        if (Schema::hasColumn('leave_applications', 'reviewed_by') && !Schema::hasColumn('leave_applications', 'approved_by')) {
            // Drop the foreign key constraint first
            Schema::table('leave_applications', function (Blueprint $table) {
                $foreignKeys = $this->listTableForeignKeys('leave_applications');
                if (in_array('leave_applications_reviewed_by_foreign', $foreignKeys)) {
                    $table->dropForeign('leave_applications_reviewed_by_foreign');
                }
            });

            // Rename columns
            DB::statement('ALTER TABLE leave_applications RENAME COLUMN reviewed_by TO approved_by');
            DB::statement('ALTER TABLE leave_applications RENAME COLUMN reviewed_at TO approved_at');

            // Add the foreign key constraint back
            Schema::table('leave_applications', function (Blueprint $table) {
                $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            });
        }
        // If neither column exists, add the approved_by column
        else if (!Schema::hasColumn('leave_applications', 'reviewed_by') && !Schema::hasColumn('leave_applications', 'approved_by')) {
            Schema::table('leave_applications', function (Blueprint $table) {
                $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamp('approved_at')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is a data correction migration, so down would typically not be needed
        // But for completeness, reverse the process
        if (Schema::hasColumn('leave_applications', 'approved_by') && !Schema::hasColumn('leave_applications', 'reviewed_by')) {
            // Drop the foreign key constraint first
            Schema::table('leave_applications', function (Blueprint $table) {
                $foreignKeys = $this->listTableForeignKeys('leave_applications');
                if (in_array('leave_applications_approved_by_foreign', $foreignKeys)) {
                    $table->dropForeign('leave_applications_approved_by_foreign');
                }
            });

            // Rename columns
            DB::statement('ALTER TABLE leave_applications RENAME COLUMN approved_by TO reviewed_by');
            DB::statement('ALTER TABLE leave_applications RENAME COLUMN approved_at TO reviewed_at');

            // Add the foreign key constraint back
            Schema::table('leave_applications', function (Blueprint $table) {
                $table->foreign('reviewed_by')->references('id')->on('users')->onDelete('set null');
            });
        }
    }

    /**
     * Get a list of foreign keys for a table
     */
    protected function listTableForeignKeys($table)
    {
        $conn = Schema::getConnection()->getDoctrineSchemaManager();

        return array_map(
            function ($key) {
                return $key->getName();
            },
            $conn->listTableForeignKeys($table)
        );
    }
};
