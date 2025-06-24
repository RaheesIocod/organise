<?php

namespace App\Livewire\Manager\Team;

use App\Models\Attendance;
use App\Models\LeaveApplication;
use App\Models\TaskTimeEntry;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;

class Show extends Component
{
    public User $user;

    public $selectedTab = 'overview';

    public $attendanceMonth;

    public $attendanceYear;

    public $attendanceData = [];

    public $taskMonth;

    public $taskYear;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->attendanceMonth = Carbon::now()->month;
        $this->attendanceYear = Carbon::now()->year;
        $this->taskMonth = Carbon::now()->month;
        $this->taskYear = Carbon::now()->year;

        $this->loadAttendanceData();
    }

    public function changeTab($tab)
    {
        $this->selectedTab = $tab;

        if ($tab === 'attendance') {
            $this->loadAttendanceData();
        } elseif ($tab === 'tasks') {
            $this->loadTaskData();
        }
    }

    public function previousAttendanceMonth()
    {
        $date = Carbon::createFromDate($this->attendanceYear, $this->attendanceMonth, 1)->subMonth();
        $this->attendanceYear = $date->year;
        $this->attendanceMonth = $date->month;
        $this->loadAttendanceData();
    }

    public function nextAttendanceMonth()
    {
        $date = Carbon::createFromDate($this->attendanceYear, $this->attendanceMonth, 1)->addMonth();
        $this->attendanceYear = $date->year;
        $this->attendanceMonth = $date->month;
        $this->loadAttendanceData();
    }

    public function loadAttendanceData()
    {
        $startDate = Carbon::createFromDate($this->attendanceYear, $this->attendanceMonth, 1);
        $endDate = $startDate->copy()->endOfMonth();

        // Get attendance records
        $records = Attendance::where('user_id', $this->user->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->get()
            ->keyBy(function ($item) {
                return $item->date->format('Y-m-d');
            });

        // Generate calendar data
        $this->attendanceData = [];

        // Create a calendar array for the entire month
        $daysInMonth = $startDate->daysInMonth;
        $firstDayOfMonth = $startDate->copy()->firstOfMonth();
        $startDayOfWeek = $firstDayOfMonth->dayOfWeek; // 0 (Sunday) to 6 (Saturday)

        // Adjust for startDayOfWeek (if needed)
        $startDayOfWeek = $startDayOfWeek === 0 ? 6 : $startDayOfWeek - 1; // Convert to 0 (Monday) to 6 (Sunday)

        // Calculate calendar cells
        $cells = [];

        // Add empty cells for days before the 1st
        for ($i = 0; $i < $startDayOfWeek; $i++) {
            $cells[] = [
                'date' => null,
                'day' => null,
                'status' => null,
                'data' => null,
                'isCurrentMonth' => false,
            ];
        }

        // Add days of the current month
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = $startDate->copy()->setDay($day);
            $dateKey = $date->format('Y-m-d');
            $isWeekend = $date->isWeekend();

            $status = 'absent';
            $attendanceData = null;

            if (isset($records[$dateKey])) {
                $record = $records[$dateKey];
                $status = $record->status;
                $attendanceData = $record;
            } elseif ($isWeekend) {
                $status = 'weekend';
            } elseif ($date->isFuture()) {
                $status = 'upcoming';
            }

            $cells[] = [
                'date' => $date,
                'day' => $day,
                'status' => $status,
                'data' => $attendanceData,
                'isCurrentMonth' => true,
            ];
        }

        // Fill remaining cells to complete the calendar grid
        $totalCells = ceil(count($cells) / 7) * 7;
        $remainingCells = $totalCells - count($cells);

        for ($i = 1; $i <= $remainingCells; $i++) {
            $cells[] = [
                'date' => null,
                'day' => null,
                'status' => null,
                'data' => null,
                'isCurrentMonth' => false,
            ];
        }

        $this->attendanceData = $cells;
    }

    public function render()
    {
        // Get pending leave applications
        $pendingLeaves = LeaveApplication::where('user_id', $this->user->id)
            ->where('status', 'pending')
            ->count();

        // Get approved leave applications for this year
        $approvedLeaves = LeaveApplication::where('user_id', $this->user->id)
            ->where('status', 'approved')
            ->whereYear('from_date', Carbon::now()->year)
            ->get();

        $leaveDaysTaken = $approvedLeaves->sum('days_count');

        // Get recent task entries
        $recentTasks = TaskTimeEntry::where('user_id', $this->user->id)
            ->orderBy('entry_date', 'desc')
            ->with('project')
            ->take(5)
            ->get();

        // Get weekly task hours for chart
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $weeklyTaskHours = [];
        $currentDate = $startOfWeek->copy();

        while ($currentDate->lte($endOfWeek)) {
            $dateKey = $currentDate->format('Y-m-d');
            $dayTasks = TaskTimeEntry::where('user_id', $this->user->id)
                ->where('entry_date', $dateKey)
                ->get();

            $weeklyTaskHours[$currentDate->format('D')] = $dayTasks->sum('hours_spent');
            $currentDate->addDay();
        }

        return view('livewire.manager.team.show', [
            'pendingLeaves' => $pendingLeaves,
            'leaveDaysTaken' => $leaveDaysTaken,
            'recentTasks' => $recentTasks,
            'weeklyTaskHours' => $weeklyTaskHours,
            'monthName' => Carbon::createFromDate($this->attendanceYear, $this->attendanceMonth, 1)->format('F Y'),
        ]);
    }
}
