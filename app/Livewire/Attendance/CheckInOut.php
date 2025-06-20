<?php

namespace App\Livewire\Attendance;

use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\LeaveApplication;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CheckInOut extends Component
{
    public $user;
    public $todayAttendance;
    public $checkedIn = false;
    public $checkedOut = false;
    public $checkInTime;
    public $checkOutTime;
    public $workHours;
    public $todayStatus;
    public $isHoliday = false;
    public $isOnLeave = false;
    public $holidayName;
    public $leaveType;

    public function mount()
    {
        $this->user = Auth::user();
        $this->loadAttendanceStatus();
    }

    public function render()
    {
        return view('livewire.attendance.check-in-out');
    }

    public function loadAttendanceStatus()
    {
        $today = Carbon::today();

        // Check if today is a holiday
        $holiday = Holiday::where('date', $today)->first();
        if ($holiday) {
            $this->isHoliday = true;
            $this->holidayName = $holiday->name;
            $this->todayStatus = 'holiday';
        }

        // Check if the user is on leave today
        $leaveApplication = LeaveApplication::where('user_id', $this->user->id)
            ->where('status', 'approved')
            ->where('from_date', '<=', $today)
            ->where('to_date', '>=', $today)
            ->with('leaveType')
            ->first();

        if ($leaveApplication) {
            $this->isOnLeave = true;
            $this->leaveType = $leaveApplication->leaveType->name;
            $this->todayStatus = 'leave';
        }

        // Get today's attendance record
        $attendance = Attendance::where('user_id', $this->user->id)
            ->where('date', $today)
            ->first();

        $this->todayAttendance = $attendance;

        if ($attendance) {
            $this->checkedIn = $attendance->check_in !== null;
            $this->checkedOut = $attendance->check_out !== null;
            $this->checkInTime = $attendance->check_in ? $attendance->check_in->format('H:i:s') : null;
            $this->checkOutTime = $attendance->check_out ? $attendance->check_out->format('H:i:s') : null;
            $this->workHours = $attendance->work_hours;
            $this->todayStatus = $attendance->status;
        } else {
            // If no attendance record and not a holiday or leave, set defaults
            if (!$this->isHoliday && !$this->isOnLeave) {
                $this->todayStatus = Carbon::today()->isWeekend() ? 'weekend' : 'absent';
            }
        }
    }

    public function checkIn()
    {
        // Don't allow check in on holidays or when on leave
        if ($this->isHoliday || $this->isOnLeave) {
            return;
        }

        $now = Carbon::now();

        // Create or update attendance record
        $attendance = Attendance::updateOrCreate(
            [
                'user_id' => $this->user->id,
                'date' => Carbon::today(),
            ],
            [
                'check_in' => $now,
                'status' => 'present',
            ]
        );

        $this->checkedIn = true;
        $this->checkInTime = $now->format('H:i:s');
        $this->todayStatus = 'present';
        $this->todayAttendance = $attendance;

        session()->flash('success', 'You have successfully checked in at ' . $now->format('H:i:s'));
    }

    public function checkOut()
    {
        if (!$this->checkedIn || $this->checkedOut) {
            return;
        }

        $now = Carbon::now();

        if ($this->todayAttendance) {
            // Calculate work hours
            $checkInTime = Carbon::parse($this->todayAttendance->check_in);
            $workHours = $now->diffInSeconds($checkInTime) / 3600; // Convert seconds to hours

            // Update attendance record
            $this->todayAttendance->check_out = $now;
            $this->todayAttendance->work_hours = round($workHours, 2);
            $this->todayAttendance->save();

            $this->checkedOut = true;
            $this->checkOutTime = $now->format('H:i:s');
            $this->workHours = round($workHours, 2);

            session()->flash('success', 'You have successfully checked out at ' . $now->format('H:i:s'));
        }
    }
}
