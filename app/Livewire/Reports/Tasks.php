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

    public $taskEntries = [];

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
        // Ensure the dailyHours array is properly formatted for the view
        // Make sure keys are strings for consistent handling in the blade template
        $formattedDailyHours = [];
        foreach ($this->dailyHours as $day => $hours) {
            $formattedDailyHours[(string)$day] = (float)$hours;
        }
        $this->dailyHours = $formattedDailyHours;

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

        $entries = $query->with('project')->get();

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
                'percentage' => $this->totalHours > 0 ? ($items->sum('hours_spent') / $this->totalHours) * 100 : 0,
            ];
        });

        $this->projectHours = collect($projectData)->sortByDesc('hours')->values()->all();

        // Calculate daily hours for the month
        $startDate = Carbon::createFromDate($this->year, $this->month, 1);
        $endDate = $startDate->copy()->endOfMonth();
        $daysInMonth = $startDate->daysInMonth;

        // Initialize daily hours with proper keys (strictly use strings for consistent access in blade)
        $this->dailyHours = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $this->dailyHours[(string)$day] = 0;
        }

        // Manually calculate daily hours
        foreach ($entries as $entry) {
            $entryDate = $entry->entry_date;

            // Get the day of the month using the appropriate method
            try {
                if ($entryDate instanceof \DateTime || $entryDate instanceof \Carbon\Carbon) {
                    $day = (int)$entryDate->format('j');
                } else {
                    // Try to convert to string first
                    $day = (int)date('j', strtotime((string)$entryDate));
                }
            } catch (\Exception $e) {
                // If any error occurs, skip this entry
                continue;
            }

            // Convert day to string for consistent array access
            $dayKey = (string)$day;
            if (isset($this->dailyHours[$dayKey])) {
                // Ensure we're adding numeric values
                $hours = (float)$entry->hours_spent;
                $this->dailyHours[$dayKey] += $hours;
            }
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

        // Aggregate hours by month using a more direct approach
        foreach ($yearEntries as $entry) {
            // Convert the date to a month number
            $entryDate = $entry->entry_date;

            // Get the month number using the appropriate method
            try {
                if ($entryDate instanceof \DateTime || $entryDate instanceof \Carbon\Carbon) {
                    $month = (int)$entryDate->format('n');
                } else {
                    // Try to convert to string first
                    $month = (int)date('n', strtotime((string)$entryDate));
                }
            } catch (\Exception $e) {
                // If any error occurs, skip this entry
                continue;
            }

            if (isset($monthlyData[$month])) {
                $monthlyData[$month] += $entry->hours_spent;
            }
        }

        $this->monthlyHours = array_values($monthlyData);

        // Store task entries for the details table
        $this->taskEntries = $entries->map(function ($entry) {
            return [
                'date' => $entry->entry_date,
                'project_name' => $entry->project ? $entry->project->name : 'Unknown Project',
                'description' => $entry->description,
                'hours' => $entry->hours_spent
            ];
        })->sortByDesc('date')->values()->all();

        // Dispatch event to update charts
        $this->dispatch('taskDataUpdated');
    }
}
