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
        Schema::table('users', function (Blueprint $table) {
            $table->string('mobile')->nullable()->after('email');
            $table->date('dob')->nullable()->after('mobile');
            $table->date('doj')->nullable()->comment('Date of Joining')->after('dob');
            $table->unsignedBigInteger('designation_id')->nullable()->after('doj');
            $table->unsignedBigInteger('reported_to')->nullable()->after('designation_id');
            $table->decimal('joining_experience_years', 5, 2)->default(0)->after('reported_to');

            $table->foreign('reported_to')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['reported_to']);
            $table->dropColumn(['mobile', 'dob', 'doj', 'designation_id', 'reported_to', 'joining_experience_years']);
        });
    }
};
