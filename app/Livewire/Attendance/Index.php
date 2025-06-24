<?php

namespace App\Livewire\Attendance;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public $user;

    public $month;

    public $year;

    public $attendanceData = [];

    public $teamMembers = [];

    public $selectedUser = null;

    public function mount()
    {
        $this->user = Auth::user();
        $this->month = Carbon::now()->month;
        $this->year = Carbon::now()->year;

        // For managers, get team members
        if ($this->user->hasRole('manager') || $this->user->hasRole('admin') || $this->user->hasRole('hr')) {
            if ($this->user->hasRole('admin') || $this->user->hasRole('hr')) {
                $this->teamMembers = User::where('id', '!=', $this->user->id)->get();
            } else {
                $this->teamMembers = User::where('reported_to', $this->user->id)->get();
            }
        }

        $this->loadAttendanceData();
    }

    public function loadAttendanceData()
    {
        $userId = $this->selectedUser ?? $this->user->id;

        // Create a Carbon period for the month
        $startDate = Carbon::createFromDate($this->year, $this->month, 1);
        $endDate = $startDate->copy()->endOfMonth();

        $period = CarbonPeriod::create($startDate, $endDate);

        // Get attendance records for the month
        $attendanceRecords = Attendance::where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate])
            ->get()
            ->keyBy(function ($item) {
                return $item->date->format('Y-m-d');
            });

        $this->attendanceData = [];

        // Fill attendance data
        foreach ($period as $date) {
            $dateString = $date->format('Y-m-d');

            if (isset($attendanceRecords[$dateString])) {
                $record = $attendanceRecords[$dateString];
                $this->attendanceData[$dateString] = [
                    'date' => $date->format('d'),
                    'day' => $date->format('D'),
                    'is_weekend' => ($date->isWeekend()),
                    'status' => $record->status,
                    'check_in' => $record->check_in ? $record->check_in->format('H:i') : null,
                    'check_out' => $record->check_out ? $record->check_out->format('H:i') : null,
                    'work_hours' => $record->work_hours,
                ];
            } else {
                $this->attendanceData[$dateString] = [
                    'date' => $date->format('d'),
                    'day' => $date->format('D'),
                    'is_weekend' => ($date->isWeekend()),
                    'status' => $date->isWeekend() ? 'weekend' : 'present', // Default to present for non-weekend days
                    'check_in' => null,
                    'check_out' => null,
                    'work_hours' => null,
                ];
            }
        }
    }

    public function changeMonth($direction)
    {
        $date = Carbon::createFromDate($this->year, $this->month, 1);

        if ($direction === 'prev') {
            $date->subMonth();
        } else {
            $date->addMonth();
        }

        $this->month = $date->month;
        $this->year = $date->year;

        $this->loadAttendanceData();
    }

    public function selectUser($userId)
    {
        $this->selectedUser = $userId;
        $this->loadAttendanceData();
    }

    public function render()
    {
        $monthName = Carbon::createFromDate($this->year, $this->month, 1)->format('F Y');

        return view('livewire.attendance.index', [
            'monthName' => $monthName,
        ]);
    }
}
