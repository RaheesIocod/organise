<?php

namespace Database\Seeders;

use App\Models\LeaveApplication;
use App\Models\LeaveType;
use App\Models\User;
use App\Models\UserLeaveBalance;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeaveApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users and leave types
        $users = User::all();
        $leaveTypes = LeaveType::all();
        $currentYear = Carbon::now()->year;

        // Create some past, current, and future leaves
        foreach ($users as $user) {
            // Skip creating entries for admin role (optional)
            if ($user->hasRole('admin')) {
                continue;
            }

            // Create 1-3 past approved leaves
            $pastLeavesCount = rand(1, 3);
            for ($i = 0; $i < $pastLeavesCount; $i++) {
                $leaveType = $leaveTypes->random();
                $startDate = Carbon::now()->subMonths(rand(1, 6))->subDays(rand(1, 15));
                $daysCount = rand(1, 3);
                $endDate = $startDate->copy()->addDays($daysCount - 1);

                // Skip if the dates are on weekend
                if ($startDate->isWeekend() || $endDate->isWeekend()) {
                    continue;
                }

                $leave = LeaveApplication::create([
                    'user_id' => $user->id,
                    'leave_type_id' => $leaveType->id,
                    'from_date' => $startDate,
                    'to_date' => $endDate,
                    'days_count' => $daysCount,
                    'reason' => 'Sample past leave reason #' . ($i + 1),
                    'status' => 'approved',
                    'approved_by' => User::role('manager')->first()->id,
                    'approved_at' => $startDate->copy()->subDays(rand(5, 10)),
                    'comments' => 'Approved automatically by seeder',
                ]);

                // Update leave balance if it's a limited leave type
                if ($leaveType->is_limited) {
                    $balance = UserLeaveBalance::where('user_id', $user->id)
                        ->where('leave_type_id', $leaveType->id)
                        ->where('year', $currentYear)
                        ->first();

                    if ($balance) {
                        $balance->used += $daysCount;
                        $balance->save();
                    }
                }
            }

            // Create 0-2 current pending leaves
            if (rand(0, 2) > 0) {
                $leaveType = $leaveTypes->random();
                $startDate = Carbon::now()->addDays(rand(3, 15));
                $daysCount = rand(1, 5);
                $endDate = $startDate->copy()->addDays($daysCount - 1);

                // Skip if the dates are on weekend
                if ($startDate->isWeekend() || $endDate->isWeekend()) {
                    continue;
                }

                $leave = LeaveApplication::create([
                    'user_id' => $user->id,
                    'leave_type_id' => $leaveType->id,
                    'from_date' => $startDate,
                    'to_date' => $endDate,
                    'days_count' => $daysCount,
                    'reason' => 'Sample pending leave reason',
                    'status' => 'pending',
                ]);

                // Update leave balance if it's a limited leave type
                if ($leaveType->is_limited) {
                    $balance = UserLeaveBalance::where('user_id', $user->id)
                        ->where('leave_type_id', $leaveType->id)
                        ->where('year', $currentYear)
                        ->first();
                }
            }

            // Create 0-1 rejected leaves
            if (rand(0, 2) > 1) {
                $leaveType = $leaveTypes->random();
                $startDate = Carbon::now()->subDays(rand(5, 30));
                $daysCount = rand(1, 7);
                $endDate = $startDate->copy()->addDays($daysCount - 1);

                LeaveApplication::create([
                    'user_id' => $user->id,
                    'leave_type_id' => $leaveType->id,
                    'from_date' => $startDate,
                    'to_date' => $endDate,
                    'days_count' => $daysCount,
                    'reason' => 'Sample rejected leave reason',
                    'status' => 'rejected',
                    'approved_by' => User::role('manager')->first()->id,
                    'approved_at' => $startDate->copy()->subDays(rand(1, 3)),
                    'comments' => 'Rejected due to work priorities',
                ]);
            }
        }
    }
}
