<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use App\Models\TaskTimeEntry;
use Livewire\Component;

class Show extends Component
{
    public Project $project;

    public $monthlyHours = [];

    public $teamMembers = [];

    public function mount(Project $project)
    {
        $this->project = $project;
        $this->loadChartData();
        $this->loadTeamMembers();
    }

    public function render()
    {
        return view('livewire.projects.show', [
            'project' => $this->project,
        ]);
    }

    protected function loadChartData()
    {
        $entries = TaskTimeEntry::where('project_id', $this->project->id)
            ->whereYear('entry_date', now()->year)
            ->get();

        $monthlyData = [];

        // Initialize all months with zero hours
        for ($i = 1; $i <= 12; $i++) {
            $monthlyData[$i] = 0;
        }

        // Aggregate hours by month
        foreach ($entries as $entry) {
            $month = $entry->entry_date->month;
            $monthlyData[$month] += $entry->hours_spent;
        }

        $this->monthlyHours = array_values($monthlyData);
    }

    protected function loadTeamMembers()
    {
        // Get unique users who have logged time on this project
        $userIds = TaskTimeEntry::where('project_id', $this->project->id)
            ->selectRaw('user_id, SUM(hours_spent) as total_hours')
            ->groupBy('user_id')
            ->orderByDesc('total_hours')
            ->limit(10)
            ->pluck('user_id');

        // Get user details with their total hours on this project
        $this->teamMembers = TaskTimeEntry::where('project_id', $this->project->id)
            ->whereIn('user_id', $userIds)
            ->selectRaw('user_id, SUM(hours_spent) as total_hours')
            ->groupBy('user_id')
            ->with('user')
            ->orderByDesc('total_hours')
            ->get()
            ->map(function ($entry) {
                return [
                    'id' => $entry->user_id,
                    'name' => $entry->user->name,
                    'hours' => $entry->total_hours,
                ];
            });
    }
}
