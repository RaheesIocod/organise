<?php

namespace Database\Seeders;

use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $holidays = [
            [
                'name' => 'New Year\'s Day',
                'date' => Carbon::createFromDate(2025, 1, 1),
                'description' => 'First day of the year',
                'is_recurring' => true,
            ],
            [
                'name' => 'Independence Day',
                'date' => Carbon::createFromDate(2025, 7, 4),
                'description' => 'National holiday',
                'is_recurring' => true,
            ],
            [
                'name' => 'Labor Day',
                'date' => Carbon::createFromDate(2025, 9, 1),
                'description' => 'Labor Day holiday',
                'is_recurring' => true,
            ],
            [
                'name' => 'Thanksgiving Day',
                'date' => Carbon::createFromDate(2025, 11, 27),
                'description' => 'Thanksgiving holiday',
                'is_recurring' => true,
            ],
            [
                'name' => 'Christmas Day',
                'date' => Carbon::createFromDate(2025, 12, 25),
                'description' => 'Christmas holiday',
                'is_recurring' => true,
            ],
            [
                'name' => 'Company Anniversary',
                'date' => Carbon::createFromDate(2025, 3, 15),
                'description' => 'Company foundation day',
                'is_recurring' => true,
            ],
            [
                'name' => 'Company Outing',
                'date' => Carbon::now()->addMonths(1),
                'description' => 'Annual company outing',
                'is_recurring' => false,
            ],
        ];

        foreach ($holidays as $holiday) {
            Holiday::create($holiday);
        }
    }
}
