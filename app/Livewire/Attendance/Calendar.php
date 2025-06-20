<?php

namespace App\Livewire\Attendance;

use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\LeaveApplication;
use Carbon\Carbon;
use Illuminate\View\View;
use Livewire\Component;

class Calendar extends Component
{
    public $year;
    public $month;

    public function mount(): void
    {
        $this->year = Carbon::now()->year;
        $this->month = Carbon::now()->month;
    }

    public function previousMonth(): void
    {
        $date = Carbon::createFromDate($this->year, $this->month, 1)->subMonth();
        $this->year = $date->year;
        $this->month = $date->month;
    }

    public function nextMonth(): void
    {
        $date = Carbon::createFromDate($this->year, $this->month, 1)->addMonth();
        $this->year = $date->year;
        $this->month = $date->month;
    }

    public function render(): View
    {
        $startOfMonth = Carbon::createFromDate($this->year, $this->month, 1)->startOfMonth();
        $endOfMonth = Carbon::createFromDate($this->year, $this->month, 1)->endOfMonth();

        // Get days in month
        $daysInMonth = $startOfMonth->daysInMonth;

        // Get the day of the week for the first day of the month (0 = Sunday, 6 = Saturday)
        $firstDayOfWeek = $startOfMonth->dayOfWeek;

        // Get attendance records for the current month for the logged-in user
        $attendances = Attendance::where('user_id', auth()->id())
            ->whereBetween('date', [$startOfMonth->format('Y-m-d'), $endOfMonth->format('Y-m-d')])
            ->get()
            ->keyBy(function ($attendance) {
                return $attendance->date->format('Y-m-d');
            });

        // Get leave applications for the current month
        $leaves = LeaveApplication::where('user_id', auth()->id())
            ->where('status', 'approved')
            ->where(function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('from_date', [$startOfMonth->format('Y-m-d'), $endOfMonth->format('Y-m-d')])
                    ->orWhereBetween('to_date', [$startOfMonth->format('Y-m-d'), $endOfMonth->format('Y-m-d')]);
            })
            ->get();

        // Get all leave dates
        $leaveDates = [];
        foreach ($leaves as $leave) {
            $fromDate = Carbon::parse($leave->from_date);
            $toDate = Carbon::parse($leave->to_date);

            $currentDate = $fromDate->copy();
            while ($currentDate->lte($toDate)) {
                $leaveDates[$currentDate->format('Y-m-d')] = $leave->leaveType->name;
                $currentDate->addDay();
            }
        }

        // Get holidays for the current month
        $holidays = Holiday::whereBetween('date', [$startOfMonth->format('Y-m-d'), $endOfMonth->format('Y-m-d')])
            ->get()
            ->keyBy(function ($holiday) {
                return $holiday->date->format('Y-m-d');
            });

        // Prepare calendar data
        $calendarData = [];

        // Add leading empty days for the first week
        for ($i = 0; $i < $firstDayOfWeek; $i++) {
            $calendarData[] = [
                'day' => '',
                'date' => null,
                'status' => null,
                'isToday' => false,
                'isWeekend' => false,
                'isHoliday' => false,
                'isLeave' => false,
                'info' => null,
            ];
        }

        // Add days of the month
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::createFromDate($this->year, $this->month, $day);
            $dateString = $date->format('Y-m-d');

            $isWeekend = $date->isWeekend();
            $isHoliday = isset($holidays[$dateString]);
            $isLeave = isset($leaveDates[$dateString]);
            $isToday = $date->isToday();

            $status = 'present'; // Default status
            $info = null;

            if ($isWeekend) {
                $status = 'weekend';
            } elseif ($isHoliday) {
                $status = 'holiday';
                $info = $holidays[$dateString]->name;
            } elseif ($isLeave) {
                $status = 'leave';
                $info = $leaveDates[$dateString];
            } elseif (isset($attendances[$dateString])) {
                $status = $attendances[$dateString]->status;
            } elseif ($date->isFuture()) {
                $status = 'future';
            }

            $calendarData[] = [
                'day' => $day,
                'date' => $date,
                'status' => $status,
                'isToday' => $isToday,
                'isWeekend' => $isWeekend,
                'isHoliday' => $isHoliday,
                'isLeave' => $isLeave,
                'info' => $info,
            ];
        }

        return view('livewire.attendance.calendar', [
            'calendarData' => $calendarData,
            'monthName' => Carbon::createFromDate($this->year, $this->month, 1)->format('F'),
            'year' => $this->year,
        ]);
    }
}
