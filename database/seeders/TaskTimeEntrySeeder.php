<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\TaskTimeEntry;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TaskTimeEntrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users
        $users = User::all();
        $projects = Project::all();

        // Generate task time entries for the last 30 days
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();

        foreach ($users as $user) {
            // Skip creating entries for admin and HR roles (optional)
            if ($user->hasRole('admin') || $user->hasRole('hr')) {
                continue;
            }

            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                // Skip weekends (optional)
                if ($date->isWeekend()) {
                    continue;
                }

                // Random number of tasks per day (1-4)
                $tasksCount = rand(1, 4);

                // Total hours for the day
                $totalHours = 0;

                for ($i = 0; $i < $tasksCount && $totalHours < 8; $i++) {
                    // Random project
                    $project = $projects->random();

                    // Random hours (0.5-4)
                    $hours = min(rand(1, 8) / 2, 8 - $totalHours);
                    $totalHours += $hours;

                    // Create the task time entry
                    TaskTimeEntry::create([
                        'user_id' => $user->id,
                        'project_id' => $project->id,
                        'task_name' => 'Task '.($i + 1),
                        'entry_date' => $date->format('Y-m-d'),
                        'description' => 'Task '.($i + 1).' for '.$project->name,
                        'hours_spent' => $hours,
                    ]);
                }
            }
        }
    }
}
