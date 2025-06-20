<?php

namespace App\Livewire\Manager;

use App\Models\TaskTimeEntry;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class TeamTasks extends Component
{
    use WithPagination;

    public $user;
    public $teamMembers = [];
    public $fromDate;
    public $toDate;
    public $selectedTeamMember = null;
    public $search = '';

    public function mount()
    {
        $this->user = Auth::user();
        $this->fromDate = Carbon::now()->startOfWeek()->format('Y-m-d');
        $this->toDate = Carbon::now()->format('Y-m-d');

        // Get team members
        if ($this->user->hasRole('admin') || $this->user->hasRole('hr')) {
            $this->teamMembers = User::whereNotIn('id', [$this->user->id])
                ->orderBy('name')
                ->get();
        } else {
            $this->teamMembers = User::where('reported_to', $this->user->id)
                ->orderBy('name')
                ->get();
        }
    }

    public function render()
    {
        $query = TaskTimeEntry::query();

        // Apply team member filter
        if ($this->selectedTeamMember) {
            $query->where('user_id', $this->selectedTeamMember);
        } else {
            // Filter by team members
            $teamMemberIds = $this->teamMembers->pluck('id')->toArray();
            $query->whereIn('user_id', $teamMemberIds);
        }

        // Apply date filters
        if ($this->fromDate) {
            $query->where('entry_date', '>=', $this->fromDate);
        }

        if ($this->toDate) {
            $query->where('entry_date', '<=', $this->toDate);
        }

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('task_name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        $taskEntries = $query->orderBy('entry_date', 'desc')
            ->with(['user', 'project'])
            ->paginate(10);

        return view('livewire.manager.team-tasks', [
            'taskEntries' => $taskEntries,
        ]);
    }

    public function getTeamSummaryProperty()
    {
        $teamMemberIds = $this->teamMembers->pluck('id')->toArray();

        // Summary by team member for the selected period
        $summaryByMember = TaskTimeEntry::whereIn('user_id', $teamMemberIds)
            ->whereBetween('entry_date', [$this->fromDate, $this->toDate])
            ->with('user')
            ->get()
            ->groupBy('user_id')
            ->map(function ($entries) {
                $user = $entries->first()->user;
                return [
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'hours' => $entries->sum('hours_spent'),
                    'entries' => $entries->count()
                ];
            })
            ->sortByDesc('hours')
            ->values()
            ->toArray();

        return $summaryByMember;
    }

    public function getDailyChartDataProperty()
    {
        $teamMemberIds = $this->teamMembers->pluck('id')->toArray();
        $startDate = Carbon::parse($this->fromDate);
        $endDate = Carbon::parse($this->toDate);
        $days = $endDate->diffInDays($startDate) + 1;

        // Limit to 14 days max for readability
        if ($days > 14) {
            $startDate = $endDate->copy()->subDays(13);
            $this->fromDate = $startDate->format('Y-m-d');
            $days = 14;
        }

        // Get all dates in range
        $dates = [];
        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            $dates[] = $currentDate->format('Y-m-d');
            $currentDate->addDay();
        }

        // Get task entries grouped by date
        $entries = TaskTimeEntry::whereIn('user_id', $teamMemberIds)
            ->whereBetween('entry_date', [$startDate, $endDate])
            ->get()
            ->groupBy(function ($entry) {
                return $entry->entry_date->format('Y-m-d');
            });

        $labels = [];
        $data = [];

        foreach ($dates as $date) {
            $labels[] = Carbon::parse($date)->format('M d');
            $data[] = isset($entries[$date]) ? $entries[$date]->sum('hours_spent') : 0;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Total Hours',
                    'data' => $data,
                    'backgroundColor' => '#93C5FD',
                    'borderColor' => '#3B82F6',
                    'borderWidth' => 1
                ]
            ]
        ];
    }
}
