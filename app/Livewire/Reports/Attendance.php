<?php

namespace App\Livewire\Reports;

use App\Models\Attendance as AttendanceModel;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Attendance extends Component
{
    public $user;

    public $month;

    public $year;

    public $attendanceSummary = [];

    public $present = 0;

    public $absent = 0;

    public $onLeave = 0;

    public $weekends = 0;

    public $holidays = 0;

    public $lateArrivals = 0;

    public $earlyDepartures = 0;

    public $totalWorkHours = 0;

    public $averageWorkHours = 0;

    public function mount()
    {
        $this->user = Auth::user();
        $this->month = Carbon::now()->month;
        $this->year = Carbon::now()->year;
        $this->generateAttendanceSummary();
    }

    public function render()
    {
        return view('livewire.reports.attendance');
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

        $this->generateAttendanceSummary();
    }

    protected function generateAttendanceSummary()
    {
        // Reset counters
        $this->present = 0;
        $this->absent = 0;
        $this->onLeave = 0;
        $this->weekends = 0;
        $this->holidays = 0;
        $this->lateArrivals = 0;
        $this->earlyDepartures = 0;
        $this->totalWorkHours = 0;

        // Create a Carbon period for the month
        $startDate = Carbon::createFromDate($this->year, $this->month, 1);
        $endDate = $startDate->copy()->endOfMonth();

        // Get all attendance records for the month
        $attendanceRecords = AttendanceModel::where('user_id', $this->user->id)
            ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->get()
            ->keyBy(function ($item) {
                return $item->date->format('Y-m-d');
            });

        // Get all holidays for the month
        $holidays = \App\Models\Holiday::whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->get()
            ->keyBy(function ($item) {
                return $item->date->format('Y-m-d');
            });

        $period = CarbonPeriod::create($startDate, $endDate);
        $workDaysCount = 0;

        $this->attendanceSummary = [];

        foreach ($period as $date) {
            $dateString = $date->format('Y-m-d');
            $isWeekend = $date->isWeekend();
            $isHoliday = isset($holidays[$dateString]);

            if ($isWeekend) {
                $this->weekends++;
                $status = 'weekend';
            } elseif ($isHoliday) {
                $this->holidays++;
                $status = 'holiday';
            } else {
                $workDaysCount++;

                if (isset($attendanceRecords[$dateString])) {
                    $record = $attendanceRecords[$dateString];
                    $status = $record->status;

                    if ($status === 'present') {
                        $this->present++;
                        $this->totalWorkHours += $record->work_hours ?? 0;

                        // Check for late arrival (after 9:30 AM)
                        if ($record->check_in && $record->check_in->format('H:i') > '09:30') {
                            $this->lateArrivals++;
                        }

                        // Check for early departure (before 5:30 PM)
                        if ($record->check_out && $record->check_out->format('H:i') < '17:30') {
                            $this->earlyDepartures++;
                        }
                    } elseif ($status === 'absent') {
                        $this->absent++;
                    } elseif ($status === 'on_leave') {
                        $this->onLeave++;
                    }
                } else {
                    // For past dates with no record, mark as absent
                    if ($date->lt(Carbon::now())) {
                        $status = 'absent';
                        $this->absent++;
                    } else {
                        $status = 'upcoming';
                    }
                }
            }

            $this->attendanceSummary[$dateString] = [
                'date' => $date->format('d'),
                'day' => $date->format('D'),
                'status' => $status,
                'check_in' => isset($attendanceRecords[$dateString]) ? $attendanceRecords[$dateString]->check_in : null,
                'check_out' => isset($attendanceRecords[$dateString]) ? $attendanceRecords[$dateString]->check_out : null,
                'work_hours' => isset($attendanceRecords[$dateString]) ? $attendanceRecords[$dateString]->work_hours : null,
            ];
        }

        // Calculate average work hours
        $this->averageWorkHours = $this->present > 0 ? $this->totalWorkHours / $this->present : 0;
    }
}
