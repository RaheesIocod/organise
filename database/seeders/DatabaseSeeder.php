<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run all the seeders
        $this->call([
            RolePermissionSeeder::class,
            DesignationSeeder::class,
            LeaveTypeSeeder::class,
            UserSeeder::class,
            ProjectSeeder::class,
            HolidaySeeder::class,
            UserLeaveBalanceSeeder::class,
            LeaveApplicationSeeder::class,
            TaskTimeEntrySeeder::class,
            AttendanceSeeder::class,
        ]);
    }
}
