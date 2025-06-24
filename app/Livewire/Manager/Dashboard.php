<?php

namespace App\Livewire\Manager;

use App\Models\Attendance;
use App\Models\LeaveApplication;
use App\Models\Project;
use App\Models\TaskTimeEntry;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;

    public $user;

    public $team = [];

    public $pendingLeaves = 0;

    public $teamPresent = 0;

    public $teamAbsent = 0;

    public $teamOnLeave = 0;

    public $projectsInProgress = 0;

    public function mount()
    {
        $this->user = Auth::user();

        if (! ($this->user->hasRole('manager') || $this->user->hasRole('admin') || $this->user->hasRole('hr'))) {
            return redirect()->route('dashboard');
        }

        $this->loadTeamData();
        $this->loadDashboardStats();
    }

    public function loadTeamData()
    {
        if ($this->user->hasRole('admin') || $this->user->hasRole('hr')) {
            // For admin/HR, get all users except themselves
            $this->team = User::where('id', '!=', $this->user->id)->get()->toArray();
        } else {
            // For managers, get only their direct reports
            $this->team = User::where('reported_to', $this->user->id)->get()->toArray();
        }
    }

    public function loadDashboardStats()
    {
        $today = Carbon::today();
        $teamIds = collect($this->team)->pluck('id')->toArray();

        // Count pending leave applications
        $this->pendingLeaves = LeaveApplication::whereIn('user_id', $teamIds)
            ->where('status', 'pending')
            ->count();

        // Count today's attendance
        $presentToday = Attendance::whereIn('user_id', $teamIds)
            ->where('date', $today)
            ->where('status', 'present')
            ->count();

        $leaveToday = Attendance::whereIn('user_id', $teamIds)
            ->where('date', $today)
            ->where('status', 'leave')
            ->count();

        $this->teamPresent = $presentToday;
        $this->teamOnLeave = $leaveToday;
        $this->teamAbsent = count($teamIds) - $presentToday - $leaveToday;

        // Count projects in progress
        $this->projectsInProgress = Project::where('status', 'in_progress')->count();
    }

    public function getTeamHoursProperty()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $teamIds = collect($this->team)->pluck('id')->toArray();

        // Get task entries for the team this week
        $entries = TaskTimeEntry::whereIn('user_id', $teamIds)
            ->whereBetween('entry_date', [$startOfWeek, $endOfWeek])
            ->with('user')
            ->get();

        // Group by user
        $userHours = [];
        foreach ($entries as $entry) {
            $userId = $entry->user_id;
            $userName = $entry->user->name;

            if (! isset($userHours[$userId])) {
                $userHours[$userId] = [
                    'name' => $userName,
                    'hours' => 0,
                ];
            }

            $userHours[$userId]['hours'] += $entry->hours_spent;
        }

        // Sort by hours (descending)
        usort($userHours, function ($a, $b) {
            return $b['hours'] <=> $a['hours'];
        });

        return array_slice($userHours, 0, 5); // Return top 5
    }

    public function getPendingLeavesListProperty()
    {
        $teamIds = collect($this->team)->pluck('id')->toArray();

        return LeaveApplication::whereIn('user_id', $teamIds)
            ->where('status', 'pending')
            ->with(['user', 'leaveType'])
            ->orderBy('from_date')
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.manager.dashboard');
    }
}
