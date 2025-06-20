<?php

namespace App\Livewire\Tasks;

use App\Models\Project;
use App\Models\TaskTimeEntry;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $user;
    public $projects = [];
    public $dateFilter;
    public $search;
    public $selectedProject = null;

    // Form fields
    public $selectedDate;
    public $description;
    public $hoursSpent;

    // Stats
    public $todayHours = 0;
    public $weekHours = 0;
    public $monthHours = 0;

    protected $rules = [
        'selectedProject' => 'required|exists:projects,id',
        'description' => 'required|string|max:255',
        'hoursSpent' => 'required|numeric|min:0.5|max:24',
        'selectedDate' => 'required|date|before_or_equal:today',
    ];

    public function mount()
    {
        $this->user = Auth::user();
        $this->projects = Project::orderBy('name')->get();
        $this->selectedDate = Carbon::now()->format('Y-m-d');

        // Calculate summary stats
        $this->calculateSummaryStats();
    }

    /**
     * Calculate summary statistics for today, week, and month
     */
    public function calculateSummaryStats()
    {
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Today's hours
        $this->todayHours = TaskTimeEntry::where('user_id', $this->user->id)
            ->where('entry_date', $today)
            ->sum('hours_spent');

        // Week's hours
        $this->weekHours = TaskTimeEntry::where('user_id', $this->user->id)
            ->whereBetween('entry_date', [$startOfWeek, $endOfWeek])
            ->sum('hours_spent');

        // Month's hours
        $this->monthHours = TaskTimeEntry::where('user_id', $this->user->id)
            ->whereBetween('entry_date', [$startOfMonth, $endOfMonth])
            ->sum('hours_spent');
    }

    public function render()
    {
        $query = TaskTimeEntry::where('user_id', $this->user->id);

        // Apply date filter
        if ($this->dateFilter) {
            $query->where('entry_date', $this->dateFilter);
        }

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('task_name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        // Apply project filter
        if ($this->selectedProject) {
            $query->where('project_id', $this->selectedProject);
        }

        $taskEntries = $query->orderBy('entry_date', 'desc')
            ->with('project')
            ->paginate(10);

        return view('livewire.tasks.index', [
            'taskEntries' => $taskEntries,
        ]);
    }

    /**
     * Set date filter or clear it
     */
    public function setDateFilter($date = null)
    {
        $this->dateFilter = $date;
    }

    /**
     * Create a new task time entry
     */
    public function createTaskEntry()
    {
        $this->validate();

        TaskTimeEntry::create([
            'user_id' => $this->user->id,
            'project_id' => $this->selectedProject,
            'task_name' => $this->description, // Using description field as the task name
            'description' => $this->description,
            'hours_spent' => $this->hoursSpent,
            'entry_date' => $this->selectedDate,
        ]);

        // Reset form fields
        $this->reset(['description', 'hoursSpent']);
        $this->selectedDate = Carbon::now()->format('Y-m-d');

        // Update summary stats
        $this->calculateSummaryStats();

        // Update charts
        $this->dispatch('refreshCharts', $this->weeklyChartData, $this->monthlyChartData);

        session()->flash('message', 'Task time entry added successfully!');
    }

    /**
     * Delete a task time entry
     */
    public function deleteTaskEntry($id)
    {
        $task = TaskTimeEntry::findOrFail($id);

        // Check if the task belongs to the user
        if ($task->user_id === $this->user->id) {
            $task->delete();

            // Update summary stats
            $this->calculateSummaryStats();

            // Update charts
            $this->dispatch('refreshCharts', $this->weeklyChartData, $this->monthlyChartData);

            session()->flash('message', 'Task time entry deleted successfully!');
        } else {
            session()->flash('error', 'You are not authorized to delete this task.');
        }
    }

    public function getWeeklyChartDataProperty()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        // Get all days of the current week
        $dates = [];
        $currentDate = $startOfWeek->copy();
        while ($currentDate->lte($endOfWeek)) {
            $dates[] = $currentDate->format('Y-m-d');
            $currentDate->addDay();
        }

        // Get task entries for the current week
        $entries = TaskTimeEntry::where('user_id', $this->user->id)
            ->whereBetween('entry_date', [$startOfWeek, $endOfWeek])
            ->get()
            ->groupBy(function ($entry) {
                return $entry->entry_date->format('Y-m-d');
            });

        $labels = [];
        $data = [];

        foreach ($dates as $date) {
            $labels[] = Carbon::parse($date)->format('D');
            $data[] = isset($entries[$date]) ? $entries[$date]->sum('hours_spent') : 0;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Hours',
                    'data' => $data,
                    'backgroundColor' => '#93C5FD',
                    'borderColor' => '#3B82F6',
                    'borderWidth' => 1
                ]
            ]
        ];
    }

    public function getMonthlyChartDataProperty()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Group by project for this month
        $projectsData = TaskTimeEntry::where('user_id', $this->user->id)
            ->whereBetween('entry_date', [$startOfMonth, $endOfMonth])
            ->with('project')
            ->get()
            ->groupBy('project_id');

        $labels = [];
        $data = [];
        $colors = [
            '#3B82F6',
            '#10B981',
            '#F59E0B',
            '#EF4444',
            '#8B5CF6',
            '#EC4899',
            '#6366F1',
            '#14B8A6'
        ];

        $i = 0;
        foreach ($projectsData as $projectId => $entries) {
            $project = $entries->first()->project;
            $labels[] = $project->name;
            $data[] = $entries->sum('hours_spent');
            $i++;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => array_slice($colors, 0, count($labels)),
                    'borderWidth' => 0
                ]
            ]
        ];
    }
}
