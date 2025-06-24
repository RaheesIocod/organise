<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $leaveTypes = [
            [
                'name' => 'Casual Leave',
                'annual_quota' => 12,
                'is_limited' => true,
                'advance_notice_days' => 7,
                'ignore_notice' => false,
                'description' => 'General purpose leave for personal matters',
                'days_allowed' => 12,
                'requires_approval' => true,
                'is_paid' => true,
                'color' => '#4F46E5',
            ],
            [
                'name' => 'Sick Leave',
                'annual_quota' => 10,
                'is_limited' => true,
                'advance_notice_days' => 0,
                'ignore_notice' => true,
                'description' => 'Leave for health-related issues',
                'days_allowed' => 10,
                'requires_approval' => true,
                'is_paid' => true,
                'color' => '#EF4444',
            ],
            [
                'name' => 'Special Holiday',
                'annual_quota' => 5,
                'is_limited' => true,
                'advance_notice_days' => 30,
                'ignore_notice' => false,
                'description' => 'Leave for special occasions',
                'days_allowed' => 5,
                'requires_approval' => true,
                'is_paid' => true,
                'color' => '#10B981',
            ],
            [
                'name' => 'Loss of Pay',
                'annual_quota' => 0,
                'is_limited' => false,
                'advance_notice_days' => 0,
                'ignore_notice' => true,
                'description' => 'Leave without pay',
                'days_allowed' => 0,
                'requires_approval' => true,
                'is_paid' => false,
                'color' => '#6B7280',
            ],
            [
                'name' => 'Compensation Leave',
                'annual_quota' => 0,
                'is_limited' => false,
                'advance_notice_days' => 0,
                'ignore_notice' => true,
                'description' => 'Leave for compensation of overtime work',
            ],
        ];

        foreach ($leaveTypes as $leaveType) {
            LeaveType::create($leaveType);
        }
    }
}
