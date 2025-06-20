<?php

namespace Database\Seeders;

use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = [
            [
                'name' => 'Website Redesign',
                'description' => 'Redesigning the company website',
                'start_date' => Carbon::now()->subMonths(2),
                'end_date' => Carbon::now()->addMonths(1),
                'status' => 'in_progress',
            ],
            [
                'name' => 'Mobile App Development',
                'description' => 'Developing a mobile app for customers',
                'start_date' => Carbon::now()->subMonths(3),
                'end_date' => Carbon::now()->addMonths(2),
                'status' => 'in_progress',
            ],
            [
                'name' => 'E-commerce Integration',
                'description' => 'Integrating e-commerce functionality',
                'start_date' => Carbon::now()->addWeeks(2),
                'end_date' => Carbon::now()->addMonths(4),
                'status' => 'not_started',
            ],
            [
                'name' => 'CRM System',
                'description' => 'Implementing a custom CRM system',
                'start_date' => Carbon::now()->subMonths(6),
                'end_date' => Carbon::now()->subWeeks(2),
                'status' => 'completed',
            ],
            [
                'name' => 'Data Migration',
                'description' => 'Migrating data to new database system',
                'start_date' => Carbon::now()->subMonths(1),
                'end_date' => Carbon::now()->addWeeks(3),
                'status' => 'in_progress',
            ],
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }
    }
}
