<?php

namespace Database\Seeders;

use App\Models\Designation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin user
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@iocod.com',
            'mobile' => '9876543210',
            'password' => Hash::make('password'),
            'dob' => Carbon::now()->subYears(35),
            'doj' => Carbon::now()->subYears(5),
            'designation_id' => Designation::where('name', 'CTO')->first()->id,
            'joining_experience_years' => 10,
        ]);
        $adminUser->assignRole('admin');

        // Create HR user
        $hrUser = User::create([
            'name' => 'HR Manager',
            'email' => 'hr@iocod.com',
            'mobile' => '9876543211',
            'password' => Hash::make('password'),
            'dob' => Carbon::now()->subYears(32),
            'doj' => Carbon::now()->subYears(3),
            'designation_id' => Designation::where('name', 'HR Manager')->first()->id,
            'joining_experience_years' => 5,
        ]);
        $hrUser->assignRole('hr');

        // Create Manager user
        $managerUser = User::create([
            'name' => 'Project Manager',
            'email' => 'manager@iocod.com',
            'mobile' => '9876543212',
            'password' => Hash::make('password'),
            'dob' => Carbon::now()->subYears(30),
            'doj' => Carbon::now()->subYears(2),
            'designation_id' => Designation::where('name', 'Project Manager')->first()->id,
            'joining_experience_years' => 8,
        ]);
        $managerUser->assignRole('manager');

        // Create Team Lead
        $teamLeadUser = User::create([
            'name' => 'Team Lead',
            'email' => 'lead@iocod.com',
            'mobile' => '9876543213',
            'password' => Hash::make('password'),
            'dob' => Carbon::now()->subYears(28),
            'doj' => Carbon::now()->subYears(1)->subMonths(6),
            'designation_id' => Designation::where('name', 'Team Lead')->first()->id,
            'reported_to' => $managerUser->id,
            'joining_experience_years' => 6,
        ]);
        $teamLeadUser->assignRole('manager');

        // Create some employees
        $designations = [
            'Senior Developer',
            'Developer',
            'Junior Developer',
            'QA Engineer',
            'UI/UX Designer',
        ];

        foreach ($designations as $index => $designation) {
            $employee = User::create([
                'name' => 'Employee '.($index + 1),
                'email' => 'employee'.($index + 1).'@iocod.com',
                'mobile' => '98765432'.(14 + $index),
                'password' => Hash::make('password'),
                'dob' => Carbon::now()->subYears(25 + $index),
                'doj' => Carbon::now()->subMonths($index * 2),
                'designation_id' => Designation::where('name', $designation)->first()->id,
                'reported_to' => $index < 3 ? $teamLeadUser->id : $managerUser->id,
                'joining_experience_years' => $index + 1,
            ]);
            $employee->assignRole('employee');
        }
    }
}
