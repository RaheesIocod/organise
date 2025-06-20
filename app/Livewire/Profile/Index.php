<?php

namespace App\Livewire\Profile;

use App\Models\Attendance;
use App\Models\LeaveApplication;
use App\Models\TaskTimeEntry;
use Carbon\Carbon;
use Illuminate\View\View;
use Livewire\Component;

class Index extends Component
{
    public $activeTab = 'overview';
    public $year;

    public function mount(): void
    {
        $this->year = Carbon::now()->year;
    }

    public function setActiveTab($tab): void
    {
        $this->activeTab = $tab;
    }

    public function previousYear(): void
    {
        $this->year--;
    }

    public function nextYear(): void
    {
        $this->year++;
    }

    public function render(): View
    {
        $user = auth()->user();

        // Calculated experience values
        $companyExperience = $user->company_experience;
        $totalExperience = $user->total_experience;

        // Get attendance data
        $attendanceData = [];

        // Get leave statistics
        $leaveStatistics = [];
        if ($this->activeTab === 'leaves' || $this->activeTab === 'overview') {
            $leaveApplications = LeaveApplication::where('user_id', $user->id)
                ->whereYear('from_date', $this->year)
                ->with('leaveType')
                ->get();

            $leavesByType = $leaveApplications
                ->where('status', 'approved')
                ->groupBy(function ($item) {
                    return $item->leaveType->name;
                });

            foreach ($leavesByType as $type => $leaves) {
                $leaveStatistics[] = [
                    'name' => $type,
                    'count' => $leaves->sum('days_count'),
                ];
            }
        }

        // Get project task statistics
        $taskStatistics = [];
        if ($this->activeTab === 'tasks' || $this->activeTab === 'overview') {
            $taskEntries = TaskTimeEntry::where('user_id', $user->id)
                ->whereYear('entry_date', $this->year)
                ->with('project')
                ->get();

            $tasksByProject = $taskEntries->groupBy(function ($item) {
                return $item->project->name;
            });

            foreach ($tasksByProject as $project => $tasks) {
                $taskStatistics[] = [
                    'project' => $project,
                    'hours' => $tasks->sum('hours_spent'),
                ];
            }
        }

        return view('livewire.profile.index', [
            'user' => $user,
            'companyExperience' => $companyExperience,
            'totalExperience' => $totalExperience,
            'leaveStatistics' => $leaveStatistics,
            'taskStatistics' => $taskStatistics
        ]);
    }
}
