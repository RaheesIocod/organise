<?php

namespace App\Livewire\Reports;

use App\Models\Project;
use App\Models\TaskTimeEntry;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Tasks extends Component
{
    public $user;
    public $year;
    public $month;
    public $selectedProject = null;
    public $projects = [];
    public $monthlyHours = [];
    public $dailyHours = [];
    public $projectHours = [];
    public $totalHours = 0;

    public function mount()
    {
        $this->user = Auth::user();
        $this->year = Carbon::now()->year;
        $this->month = Carbon::now()->month;
        $this->loadProjects();
        $this->loadTaskData();
    }

    public function render()
    {
        return view('livewire.reports.tasks');
    }

    public function loadProjects()
    {
        $this->projects = Project::orderBy('name')->get();
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

        $this->loadTaskData();
    }

    public function updatedSelectedProject()
    {
        $this->loadTaskData();
    }

    protected function loadTaskData()
    {
        // Build the base query
        $query = TaskTimeEntry::where('user_id', $this->user->id)
            ->whereYear('entry_date', $this->year)
            ->whereMonth('entry_date', $this->month);

        if ($this->selectedProject) {
            $query->where('project_id', $this->selectedProject);
        }

        $entries = $query->get();

        // Calculate total hours
        $this->totalHours = $entries->sum('hours_spent');

        // Calculate project hours
        $projectData = [];
        $entries->groupBy('project_id')->each(function ($items, $projectId) use (&$projectData) {
            $project = Project::find($projectId);
            $projectData[] = [
                'id' => $projectId,
                'name' => $project ? $project->name : 'Unknown Project',
                'hours' => $items->sum('hours_spent'),
                'percentage' => $this->totalHours > 0 ? ($items->sum('hours_spent') / $this->totalHours) * 100 : 0
            ];
        });

        $this->projectHours = collect($projectData)->sortByDesc('hours')->values()->all();

        // Calculate daily hours for the month
        $startDate = Carbon::createFromDate($this->year, $this->month, 1);
        $endDate = $startDate->copy()->endOfMonth();
        $daysInMonth = $startDate->daysInMonth;

        $dailyEntries = $entries->groupBy(function ($entry) {
            return $entry->entry_date->format('j'); // Day of the month without leading zeros
        });

        $this->dailyHours = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $this->dailyHours[$day] = isset($dailyEntries[$day]) ? $dailyEntries[$day]->sum('hours_spent') : 0;
        }

        // Calculate monthly hours for the year for chart
        $monthlyData = [];

        // Initialize all months with zero hours
        for ($i = 1; $i <= 12; $i++) {
            $monthlyData[$i] = 0;
        }

        // Get entries for the whole year
        $yearEntries = TaskTimeEntry::where('user_id', $this->user->id)
            ->whereYear('entry_date', $this->year);

        if ($this->selectedProject) {
            $yearEntries->where('project_id', $this->selectedProject);
        }

        $yearEntries = $yearEntries->get();

        // Aggregate hours by month
        foreach ($yearEntries as $entry) {
            $month = $entry->entry_date->month;
            $monthlyData[$month] += $entry->hours_spent;
        }

        $this->monthlyHours = array_values($monthlyData);
    }
}
