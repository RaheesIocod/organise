<?php

namespace App\Livewire;

use App\Models\Attendance;
use App\Models\LeaveApplication;
use App\Models\Project;
use App\Models\TaskTimeEntry;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public $user;

    public $todayTaskHours = 0;

    public $todayTaskCount = 0;

    public $pendingLeaves = 0;

    public $approvedLeaves = 0;

    public $teamAttendance = [];

    public $projectsCount = 0;

    public $activeProjects = [];

    public $upcomingLeaves = [];

    public $recentTasks = [];

    public function mount()
    {
        $this->user = Auth::user();
        $today = Carbon::today();

        // Today's task hours and count
        $todayTasks = TaskTimeEntry::where('user_id', $this->user->id)
            ->where('entry_date', $today)
            ->get();

        $this->todayTaskHours = $todayTasks->sum('hours_spent');
        $this->todayTaskCount = $todayTasks->count();

        // Leave counts
        $this->pendingLeaves = LeaveApplication::where('user_id', $this->user->id)
            ->where('status', 'pending')
            ->count();

        $this->approvedLeaves = LeaveApplication::where('user_id', $this->user->id)
            ->where('status', 'approved')
            ->count();

        // For managers, get team attendance
        if ($this->user->hasRole('manager') || $this->user->hasRole('admin') || $this->user->hasRole('hr')) {
            $teamMembers = User::where('reported_to', $this->user->id)->get();
            $teamMemberIds = $teamMembers->pluck('id')->toArray();

            if ($this->user->hasRole('admin') || $this->user->hasRole('hr')) {
                $this->teamAttendance = User::with('attendances')
                    ->whereNotIn('id', [$this->user->id])
                    ->take(10)
                    ->get()
                    ->map(function ($user) use ($today) {
                        $attendance = Attendance::where('user_id', $user->id)
                            ->where('date', $today)
                            ->first();

                        return [
                            'id' => $user->id,
                            'name' => $user->name,
                            'status' => $attendance ? $attendance->status : 'present',
                        ];
                    });
            } else {
                $this->teamAttendance = User::with('attendances')
                    ->whereIn('id', $teamMemberIds)
                    ->get()
                    ->map(function ($user) use ($today) {
                        $attendance = Attendance::where('user_id', $user->id)
                            ->where('date', $today)
                            ->first();

                        return [
                            'id' => $user->id,
                            'name' => $user->name,
                            'status' => $attendance ? $attendance->status : 'present',
                        ];
                    });
            }
        }

        // Projects
        $this->projectsCount = Project::count();
        $this->activeProjects = Project::where('status', 'in_progress')
            ->orderBy('end_date')
            ->take(5)
            ->get();

        // Upcoming leaves
        $this->upcomingLeaves = LeaveApplication::where('user_id', $this->user->id)
            ->where('status', 'approved')
            ->where('from_date', '>=', $today)
            ->orderBy('from_date')
            ->take(3)
            ->get();

        // Recent tasks
        $this->recentTasks = TaskTimeEntry::where('user_id', $this->user->id)
            ->orderBy('entry_date', 'desc')
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
