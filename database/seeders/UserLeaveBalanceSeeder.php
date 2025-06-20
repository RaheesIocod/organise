<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use App\Models\User;
use App\Models\UserLeaveBalance;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserLeaveBalanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users
        $users = User::all();
        $leaveTypes = LeaveType::where('is_limited', true)->get();
        $currentYear = Carbon::now()->year;

        foreach ($users as $user) {
            // Calculate how many months the user has been with the company this year
            $joinDate = $user->doj ?? Carbon::now(); // Use doj field, fallback to today
            $startOfYear = Carbon::createFromDate($currentYear, 1, 1);

            // If user joined before this year, use start of year
            $effectiveStartDate = $joinDate->lt($startOfYear) ? $startOfYear : $joinDate;

            // Calculate months from effective start date until end of year
            $monthsInCompanyThisYear = $effectiveStartDate->diffInMonths(Carbon::createFromDate($currentYear, 12, 31)) + 1;

            // Pro-rate the leave quota based on months worked in this year
            foreach ($leaveTypes as $leaveType) {
                // Pro-rate the annual quota
                $proRatedQuota = ceil(($leaveType->annual_quota / 12) * $monthsInCompanyThisYear);

                // Create the leave balance
                UserLeaveBalance::create([
                    'user_id' => $user->id,
                    'leave_type_id' => $leaveType->id,
                    'year' => $currentYear,
                    'total_allocated' => $proRatedQuota,
                    'used' => 0,
                ]);
            }
        }
    }
}
