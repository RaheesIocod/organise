<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\LeaveApplication;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users
        $users = User::all();

        // Get all approved leave applications
        $leaveApplications = LeaveApplication::where('status', 'approved')->get();

        // Create a mapping of user leaves by date
        $userLeaves = [];
        foreach ($leaveApplications as $leave) {
            $fromDate = Carbon::parse($leave->from_date);
            $toDate = Carbon::parse($leave->to_date);
            $period = $fromDate->toPeriod($toDate);

            foreach ($period as $date) {
                $dateString = $date->format('Y-m-d');
                if (! isset($userLeaves[$leave->user_id])) {
                    $userLeaves[$leave->user_id] = [];
                }
                $userLeaves[$leave->user_id][$dateString] = true;
            }
        }

        // Generate attendance for the last 30 days
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();

        foreach ($users as $user) {
            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                // Skip weekends
                if ($date->isWeekend()) {
                    continue;
                }

                $dateString = $date->format('Y-m-d');

                // Check if the user is on leave
                if (isset($userLeaves[$user->id]) && isset($userLeaves[$user->id][$dateString])) {
                    // Create an attendance entry with 'on_leave' status
                    Attendance::create([
                        'user_id' => $user->id,
                        'date' => $dateString,
                        'status' => 'on_leave',
                        'check_in' => null,
                        'check_out' => null,
                        'work_hours' => 0,
                    ]);
                } else {
                    // Create a regular attendance entry with random check-in and check-out times
                    $checkInHour = rand(8, 10);
                    $checkInMinute = rand(0, 59);
                    $checkIn = $date->copy()->setTime($checkInHour, $checkInMinute);

                    $workHours = rand(7, 9);
                    $checkOut = $checkIn->copy()->addHours($workHours)->addMinutes(rand(0, 30));

                    Attendance::create([
                        'user_id' => $user->id,
                        'date' => $dateString,
                        'status' => 'present',
                        'check_in' => $checkIn,
                        'check_out' => $checkOut,
                        'work_hours' => $workHours,
                    ]);
                }
            }
        }
    }
}
