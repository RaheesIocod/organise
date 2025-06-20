<div>
    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Employee Profile</h1>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Profile Sidebar -->
            <div class="md:col-span-1">
                <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                    <div class="p-6 text-center border-b">
                        <div class="inline-block h-24 w-24 rounded-full overflow-hidden bg-gray-100">
                            <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <h2 class="mt-4 text-lg font-medium text-gray-900">{{ $user->name }}</h2>
                        <p class="text-sm text-gray-500">{{ $user->designation->name ?? 'No Designation' }}</p>
                    </div>

                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-500">Email</p>
                                <p class="text-sm font-medium text-gray-900">{{ $user->email }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Mobile</p>
                                <p class="text-sm font-medium text-gray-900">{{ $user->mobile ?? 'Not provided' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Date of Birth</p>
                                <p class="text-sm font-medium text-gray-900">{{ $user->dob ? $user->dob->format('M d, Y') : 'Not provided' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Date of Joining</p>
                                <p class="text-sm font-medium text-gray-900">{{ $user->doj ? $user->doj->format('M d, Y') : 'Not provided' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Manager</p>
                                <p class="text-sm font-medium text-gray-900">{{ $user->manager->name ?? 'Not Assigned' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-6 border-b">
                        <h3 class="font-medium text-gray-900">Experience</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between mb-1">
                                    <p class="text-sm text-gray-500">Previous Experience</p>
                                    <p class="text-sm font-medium text-gray-900">{{ number_format($user->joining_experience_years, 1) }} years</p>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min(100, $user->joining_experience_years * 10) }}%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between mb-1">
                                    <p class="text-sm text-gray-500">Company Experience</p>
                                    <p class="text-sm font-medium text-gray-900">{{ number_format($companyExperience, 1) }} years</p>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ min(100, $companyExperience * 10) }}%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between mb-1">
                                    <p class="text-sm text-gray-500">Total Experience</p>
                                    <p class="text-sm font-medium text-gray-900">{{ number_format($totalExperience, 1) }} years</p>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-purple-600 h-2 rounded-full" style="width: {{ min(100, $totalExperience * 5) }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Content -->
            <div class="md:col-span-3">
                <!-- Tabs -->
                <div class="mb-6 border-b">
                    <div class="flex flex-wrap -mb-px">
                        <button @class([
                            'mr-2 py-2 px-4 text-sm font-medium border-b-2 focus:outline-none',
                            'text-blue-600 border-blue-600' => $activeTab === 'overview',
                            'text-gray-500 border-transparent hover:text-gray-700 hover:border-gray-300' =>
                                $activeTab !== 'overview',
                        ]) wire:click="setActiveTab('overview')">
                            Overview
                        </button>
                        <button @class([
                            'mr-2 py-2 px-4 text-sm font-medium border-b-2 focus:outline-none',
                            'text-blue-600 border-blue-600' => $activeTab === 'attendance',
                            'text-gray-500 border-transparent hover:text-gray-700 hover:border-gray-300' =>
                                $activeTab !== 'attendance',
                        ]) wire:click="setActiveTab('attendance')">
                            Attendance
                        </button>
                        <button @class([
                            'mr-2 py-2 px-4 text-sm font-medium border-b-2 focus:outline-none',
                            'text-blue-600 border-blue-600' => $activeTab === 'leaves',
                            'text-gray-500 border-transparent hover:text-gray-700 hover:border-gray-300' =>
                                $activeTab !== 'leaves',
                        ]) wire:click="setActiveTab('leaves')">
                            Leave
                        </button>
                        <button @class([
                            'mr-2 py-2 px-4 text-sm font-medium border-b-2 focus:outline-none',
                            'text-blue-600 border-blue-600' => $activeTab === 'tasks',
                            'text-gray-500 border-transparent hover:text-gray-700 hover:border-gray-300' =>
                                $activeTab !== 'tasks',
                        ]) wire:click="setActiveTab('tasks')">
                            Tasks
                        </button>
                        <button @class([
                            'mr-2 py-2 px-4 text-sm font-medium border-b-2 focus:outline-none',
                            'text-blue-600 border-blue-600' => $activeTab === 'holidays',
                            'text-gray-500 border-transparent hover:text-gray-700 hover:border-gray-300' =>
                                $activeTab !== 'holidays',
                        ]) wire:click="setActiveTab('holidays')">
                            Holidays
                        </button>
                    </div>
                </div>

                <!-- Tab Content -->
                <div>
                    <!-- Overview Tab -->
                    @if ($activeTab === 'overview')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Leave Summary -->
                            <div class="bg-white rounded-lg shadow">
                                <div class="p-4 border-b">
                                    <h3 class="font-medium text-gray-900">Leave Summary</h3>
                                </div>
                                <div class="p-4">
                                    @if (count($leaveStatistics) > 0)
                                        <div class="h-64">
                                            <canvas id="leaveSummaryChart"></canvas>
                                        </div>
                                    @else
                                        <div class="flex items-center justify-center h-64 bg-gray-50 rounded">
                                            <p class="text-gray-500">No leave data available for {{ $year }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Task Summary -->
                            <div class="bg-white rounded-lg shadow">
                                <div class="p-4 border-b">
                                    <h3 class="font-medium text-gray-900">Task Distribution</h3>
                                </div>
                                <div class="p-4">
                                    @if (count($taskStatistics) > 0)
                                        <div class="h-64">
                                            <canvas id="taskSummaryChart"></canvas>
                                        </div>
                                    @else
                                        <div class="flex items-center justify-center h-64 bg-gray-50 rounded">
                                            <p class="text-gray-500">No task data available for {{ $year }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Recent Activity -->
                            <div class="bg-white rounded-lg shadow md:col-span-2">
                                <div class="p-4 border-b">
                                    <h3 class="font-medium text-gray-900">Recent Activity</h3>
                                </div>
                                <div class="p-4">
                                    <div class="flow-root">
                                        <ul role="list" class="-mb-8">
                                            <li>
                                                <div class="relative pb-8">
                                                    <span class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                                    <div class="relative flex items-start space-x-3">
                                                        <div class="relative">
                                                            <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                                                    <path fill-rule="evenodd"
                                                                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        <div class="min-w-0 flex-1">
                                                            <div>
                                                                <p class="text-sm font-medium text-gray-900">Applied for Casual Leave</p>
                                                                <p class="mt-0.5 text-sm text-gray-500">Jun 15, 2025</p>
                                                            </div>
                                                            <div class="mt-2 text-sm text-gray-700">
                                                                <p>Applied for 2 days leave from Jun 20, 2025 to Jun 21, 2025.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="relative pb-8">
                                                    <span class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                                    <div class="relative flex items-start space-x-3">
                                                        <div class="relative">
                                                            <div class="h-10 w-10 rounded-full bg-green-500 flex items-center justify-center">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                                                    <path fill-rule="evenodd"
                                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        <div class="min-w-0 flex-1">
                                                            <div>
                                                                <p class="text-sm font-medium text-gray-900">Task Completed</p>
                                                                <p class="mt-0.5 text-sm text-gray-500">Jun 10, 2025</p>
                                                            </div>
                                                            <div class="mt-2 text-sm text-gray-700">
                                                                <p>Completed task "UI Design for Dashboard" in Project Alpha.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="relative">
                                                    <div class="relative flex items-start space-x-3">
                                                        <div class="relative">
                                                            <div class="h-10 w-10 rounded-full bg-purple-500 flex items-center justify-center">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                                                    <path
                                                                        d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        <div class="min-w-0 flex-1">
                                                            <div>
                                                                <p class="text-sm font-medium text-gray-900">Added to Project Beta</p>
                                                                <p class="mt-0.5 text-sm text-gray-500">Jun 5, 2025</p>
                                                            </div>
                                                            <div class="mt-2 text-sm text-gray-700">
                                                                <p>You were added to Project Beta team.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Attendance Tab -->
                    @if ($activeTab === 'attendance')
                        <div class="bg-white rounded-lg shadow overflow-hidden">
                            <div class="p-4 border-b flex justify-between items-center">
                                <h3 class="font-medium text-gray-900">Attendance Calendar</h3>
                                <div class="flex space-x-2">
                                    <button wire:click="previousYear" class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded-md">
                                        <span class="sr-only">Previous Year</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <span class="text-gray-700">{{ $year }}</span>
                                    <button wire:click="nextYear" class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded-md">
                                        <span class="sr-only">Next Year</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="p-4">
                                <div id="attendanceCalendar"></div>
                            </div>
                        </div>
                    @endif

                    <!-- Leave Tab -->
                    @if ($activeTab === 'leaves')
                        <div>
                            <div class="bg-white rounded-lg shadow mb-6">
                                <div class="p-4 border-b flex justify-between items-center">
                                    <h3 class="font-medium text-gray-900">Leave Statistics</h3>
                                    <div class="flex space-x-2">
                                        <button wire:click="previousYear" class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded-md">
                                            <span class="sr-only">Previous Year</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                        <span class="text-gray-700">{{ $year }}</span>
                                        <button wire:click="nextYear" class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded-md">
                                            <span class="sr-only">Next Year</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="p-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            @if (count($leaveStatistics) > 0)
                                                <div class="h-64">
                                                    <canvas id="leaveStatisticsChart"></canvas>
                                                </div>
                                            @else
                                                <div class="flex items-center justify-center h-64 bg-gray-50 rounded">
                                                    <p class="text-gray-500">No leave data available for {{ $year }}</p>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-700 mb-3">Leave Breakdown</h4>
                                            <div class="space-y-3">
                                                @forelse($leaveStatistics as $stat)
                                                    <div>
                                                        <div class="flex justify-between items-center mb-1">
                                                            <span class="text-sm text-gray-600">{{ $stat['name'] }}</span>
                                                            <span class="text-sm font-medium text-gray-900">{{ $stat['count'] }} days</span>
                                                        </div>
                                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min(100, $stat['count'] * 5) }}%"></div>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <p class="text-gray-500">No leave data available</p>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-lg shadow">
                                <div class="p-4 border-b">
                                    <h3 class="font-medium text-gray-900">Leave Applications</h3>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">From</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">To</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Days</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applied On</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <!-- Sample data -->
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Casual Leave</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Jun 20, 2025</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Jun 21, 2025</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Jun 15, 2025</td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Sick Leave</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">May 10, 2025</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">May 11, 2025</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">May 8, 2025</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Tasks Tab -->
                    @if ($activeTab === 'tasks')
                        <div>
                            <div class="bg-white rounded-lg shadow mb-6">
                                <div class="p-4 border-b flex justify-between items-center">
                                    <h3 class="font-medium text-gray-900">Task Distribution</h3>
                                    <div class="flex space-x-2">
                                        <button wire:click="previousYear" class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded-md">
                                            <span class="sr-only">Previous Year</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                        <span class="text-gray-700">{{ $year }}</span>
                                        <button wire:click="nextYear" class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded-md">
                                            <span class="sr-only">Next Year</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="p-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            @if (count($taskStatistics) > 0)
                                                <div class="h-64">
                                                    <canvas id="taskDistributionChart"></canvas>
                                                </div>
                                            @else
                                                <div class="flex items-center justify-center h-64 bg-gray-50 rounded">
                                                    <p class="text-gray-500">No task data available for {{ $year }}</p>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-700 mb-3">Project Hours</h4>
                                            <div class="space-y-3">
                                                @forelse($taskStatistics as $stat)
                                                    <div>
                                                        <div class="flex justify-between items-center mb-1">
                                                            <span class="text-sm text-gray-600">{{ $stat['project'] }}</span>
                                                            <span class="text-sm font-medium text-gray-900">{{ $stat['hours'] }} hours</span>
                                                        </div>
                                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                                            <div class="bg-purple-600 h-2 rounded-full" style="width: {{ min(100, $stat['hours'] * 2) }}%"></div>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <p class="text-gray-500">No task data available</p>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-lg shadow">
                                <div class="p-4 border-b">
                                    <h3 class="font-medium text-gray-900">Recent Tasks</h3>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Task</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hours</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <!-- Sample data -->
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Project Alpha</td>
                                                <td class="px-6 py-4 text-sm text-gray-500">UI Design for Dashboard</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">4.5</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Jun 10, 2025</td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Project Alpha</td>
                                                <td class="px-6 py-4 text-sm text-gray-500">API Integration</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">6.0</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Jun 9, 2025</td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Project Beta</td>
                                                <td class="px-6 py-4 text-sm text-gray-500">Database Design</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">3.5</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Jun 8, 2025</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Holidays Tab -->
                    @if ($activeTab === 'holidays')
                        <div class="bg-white rounded-lg shadow overflow-hidden">
                            <div class="p-4 border-b">
                                <h3 class="font-medium text-gray-900">Company Holidays</h3>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Holiday</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Day</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <!-- Sample data -->
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">New Year's Day</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Jan 1, 2025</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Wednesday</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Public Holiday</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Foundation Day</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Jun 15, 2025</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Sunday</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Company Holiday</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="p-4">
                                <div id="holidaysCalendar" class="w-full"></div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('livewire:initialized', function() {
                initializeCharts();

                Livewire.hook('morph.updated', ({
                    el
                }) => {
                    initializeCharts();
                });

                function initializeCharts() {
                    // Leave Summary Chart
                    if (document.getElementById('leaveSummaryChart') && @js($activeTab) === 'overview') {
                        const leaveStats = @js($leaveStatistics);
                        if (leaveStats.length > 0) {
                            const ctx = document.getElementById('leaveSummaryChart').getContext('2d');
                            const leaveSummaryChart = new Chart(ctx, {
                                type: 'pie',
                                data: {
                                    labels: leaveStats.map(stat => stat.name),
                                    datasets: [{
                                        data: leaveStats.map(stat => stat.count),
                                        backgroundColor: [
                                            '#4F46E5', '#06B6D4', '#F59E0B', '#EC4899', '#10B981'
                                        ]
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false
                                }
                            });
                        }
                    }

                    // Task Summary Chart
                    if (document.getElementById('taskSummaryChart') && @js($activeTab) === 'overview') {
                        const taskStats = @js($taskStatistics);
                        if (taskStats.length > 0) {
                            const ctx = document.getElementById('taskSummaryChart').getContext('2d');
                            const taskSummaryChart = new Chart(ctx, {
                                type: 'doughnut',
                                data: {
                                    labels: taskStats.map(stat => stat.project),
                                    datasets: [{
                                        data: taskStats.map(stat => stat.hours),
                                        backgroundColor: [
                                            '#8B5CF6', '#3B82F6', '#06B6D4', '#10B981', '#F59E0B'
                                        ]
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false
                                }
                            });
                        }
                    }

                    // Leave Statistics Chart
                    if (document.getElementById('leaveStatisticsChart') && @js($activeTab) === 'leaves') {
                        const leaveStats = @js($leaveStatistics);
                        if (leaveStats.length > 0) {
                            const ctx = document.getElementById('leaveStatisticsChart').getContext('2d');
                            const leaveStatisticsChart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: leaveStats.map(stat => stat.name),
                                    datasets: [{
                                        label: 'Days',
                                        data: leaveStats.map(stat => stat.count),
                                        backgroundColor: '#4F46E5'
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    scales: {
                                        y: {
                                            beginAtZero: true
                                        }
                                    }
                                }
                            });
                        }
                    }

                    // Task Distribution Chart
                    if (document.getElementById('taskDistributionChart') && @js($activeTab) === 'tasks') {
                        const taskStats = @js($taskStatistics);
                        if (taskStats.length > 0) {
                            const ctx = document.getElementById('taskDistributionChart').getContext('2d');
                            const taskDistributionChart = new Chart(ctx, {
                                type: 'pie',
                                data: {
                                    labels: taskStats.map(stat => stat.project),
                                    datasets: [{
                                        data: taskStats.map(stat => stat.hours),
                                        backgroundColor: [
                                            '#8B5CF6', '#3B82F6', '#06B6D4', '#10B981', '#F59E0B'
                                        ]
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false
                                }
                            });
                        }
                    }

                    // Attendance Calendar
                    if (document.getElementById('attendanceCalendar') && @js($activeTab) === 'attendance') {
                        const calendarEl = document.getElementById('attendanceCalendar');
                        const calendar = new FullCalendar.Calendar(calendarEl, {
                            initialView: 'dayGridMonth',
                            headerToolbar: {
                                left: 'prev,next today',
                                center: 'title',
                                right: 'dayGridMonth,dayGridYear'
                            },
                            events: [
                                // Sample data - replace with actual data
                                {
                                    title: 'Present',
                                    start: '2025-06-01',
                                    end: '2025-06-14',
                                    display: 'background',
                                    color: '#D1FAE5'
                                },
                                {
                                    title: 'Leave',
                                    start: '2025-06-17',
                                    end: '2025-06-18',
                                    display: 'background',
                                    color: '#DBEAFE'
                                },
                                {
                                    title: 'Holiday',
                                    start: '2025-06-15',
                                    display: 'background',
                                    color: '#FEF3C7'
                                }
                            ]
                        });
                        calendar.render();
                    }

                    // Holidays Calendar
                    if (document.getElementById('holidaysCalendar') && @js($activeTab) === 'holidays') {
                        const calendarEl = document.getElementById('holidaysCalendar');
                        const calendar = new FullCalendar.Calendar(calendarEl, {
                            initialView: 'dayGridMonth',
                            headerToolbar: {
                                left: 'prev,next today',
                                center: 'title',
                                right: 'dayGridMonth,listYear'
                            },
                            events: [
                                // Sample data - replace with actual data
                                {
                                    title: "New Year's Day",
                                    start: '2025-01-01',
                                    allDay: true
                                },
                                {
                                    title: 'Foundation Day',
                                    start: '2025-06-15',
                                    allDay: true
                                }
                            ]
                        });
                        calendar.render();
                    }
                }
            });
        </script>
    @endpush
</div>
