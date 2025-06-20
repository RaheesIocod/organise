<?php

namespace Database\Seeders;

use App\Models\Designation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $designations = [
            [
                'name' => 'CEO',
                'department' => 'Executive',
                'description' => 'Chief Executive Officer',
            ],
            [
                'name' => 'CTO',
                'department' => 'Technology',
                'description' => 'Chief Technology Officer',
            ],
            [
                'name' => 'HR Manager',
                'department' => 'Human Resources',
                'description' => 'Human Resources Manager',
            ],
            [
                'name' => 'Project Manager',
                'department' => 'Operations',
                'description' => 'Project Manager',
            ],
            [
                'name' => 'Team Lead',
                'department' => 'Technology',
                'description' => 'Team Leader',
            ],
            [
                'name' => 'Senior Developer',
                'department' => 'Technology',
                'description' => 'Senior Software Developer',
            ],
            [
                'name' => 'Developer',
                'department' => 'Technology',
                'description' => 'Software Developer',
            ],
            [
                'name' => 'Junior Developer',
                'department' => 'Technology',
                'description' => 'Junior Software Developer',
            ],
            [
                'name' => 'QA Engineer',
                'department' => 'Quality Assurance',
                'description' => 'Quality Assurance Engineer',
            ],
            [
                'name' => 'UI/UX Designer',
                'department' => 'Design',
                'description' => 'User Interface / User Experience Designer',
            ],
        ];

        foreach ($designations as $designation) {
            Designation::create($designation);
        }
    }
}
