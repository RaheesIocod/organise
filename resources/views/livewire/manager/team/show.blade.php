<div>
    <x-slot name="header">Team Member Detail</x-slot>

    <!-- Employee Profile Header -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                <div class="flex items-center">
                    <div class="h-20 w-20 rounded-full bg-gray-200 flex items-center justify-center text-2xl text-gray-600 font-bold">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div class="ml-6">
                        <h1 class="text-2xl font-semibold text-gray-800">{{ $user->name }}</h1>
                        <p class="text-gray-600">{{ $user->designation->name ?? 'No Designation' }}</p>
                        <div class="mt-2 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span class="ml-2 text-gray-600">{{ $user->email }}</span>
                        </div>
                    </div>
                </div>
                <div class="mt-4 md:mt-0">
                    <a href="{{ route('manager.team') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        &larr; Back to Team
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="mb-6 border-b border-gray-200">
        <div class="flex -mb-px">
            <button wire:click="changeTab('overview')"
                class="py-4 px-6 {{ $selectedTab === 'overview' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-500 hover:text-gray-700' }} font-medium">
                Overview
            </button>
            <button wire:click="changeTab('attendance')"
                class="py-4 px-6 {{ $selectedTab === 'attendance' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-500 hover:text-gray-700' }} font-medium">
                Attendance
            </button>
            <button wire:click="changeTab('leaves')" class="py-4 px-6 {{ $selectedTab === 'leaves' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-500 hover:text-gray-700' }} font-medium">
                Leaves
            </button>
            <button wire:click="changeTab('tasks')" class="py-4 px-6 {{ $selectedTab === 'tasks' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-500 hover:text-gray-700' }} font-medium">
                Tasks
            </button>
        </div>
    </div>

    <!-- Overview Tab -->
    <div x-show="$wire.selectedTab === 'overview'" x-transition>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Company Experience -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="font-semibold text-gray-700">Company Experience</h2>
                            <p class="text-2xl font-bold text-blue-600">{{ $user->company_experience }} years</p>
                            <p class="text-sm text-gray-500">Joined {{ $user->doj->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Experience -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="font-semibold text-gray-700">Total Experience</h2>
                            <p class="text-2xl font-bold text-purple-600">{{ $user->total_experience }} years</p>
                            <p class="text-sm text-gray-500">{{ $user->joining_experience_years }} yrs at joining</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Leave Status -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="font-semibold text-gray-700">Leave Status</h2>
                            <p class="text-2xl font-bold text-green-600">{{ $leaveDaysTaken }} days</p>
                            <p class="text-sm text-gray-500">{{ $pendingLeaves }} pending requests</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Weekly Task Hours -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="font-semibold text-lg text-gray-700">Weekly Task Hours</h2>
                </div>
                <div class="p-6">
                    <canvas id="weeklyHoursChart" height="200"></canvas>
                </div>
            </div>

            <!-- Recent Tasks -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="font-semibold text-lg text-gray-700">Recent Tasks</h2>
                </div>
                <div class="p-6">
                    @if (count($recentTasks) > 0)
                        <div class="space-y-4">
                            @foreach ($recentTasks as $task)
                                <div class="flex items-start justify-between border-b pb-4 last:border-b-0 last:pb-0">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $task->description }}</p>
                                        <p class="text-xs text-gray-500">{{ $task->project->name }} &bull; {{ $task->entry_date->format('M d, Y') }}</p>
                                    </div>
                                    <span class="text-xs font-medium">{{ $task->hours_spent }} hrs</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-gray-500">No recent tasks found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Tab -->
    <div x-show="$wire.selectedTab === 'attendance'" x-transition>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="font-semibold text-lg text-gray-700">Attendance Calendar</h2>
                <div class="flex items-center space-x-2">
                    <button wire:click="previousAttendanceMonth" class="p-2 rounded-full bg-gray-100 hover:bg-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <span class="text-gray-700">{{ $monthName }}</span>
                    <button wire:click="nextAttendanceMonth" class="p-2 rounded-full bg-gray-100 hover:bg-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <!-- Legend -->
                <div class="mb-4 flex flex-wrap gap-4">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-green-500 rounded-sm mr-2"></div>
                        <span class="text-sm">Present</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-red-500 rounded-sm mr-2"></div>
                        <span class="text-sm">Absent</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-yellow-500 rounded-sm mr-2"></div>
                        <span class="text-sm">Leave</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-gray-300 rounded-sm mr-2"></div>
                        <span class="text-sm">Weekend</span>
                    </div>
                </div>

                <!-- Calendar -->
                <div class="grid grid-cols-7 gap-px bg-gray-200">
                    <!-- Day Headers -->
                    <div class="bg-gray-100 p-2 text-center font-medium">Mon</div>
                    <div class="bg-gray-100 p-2 text-center font-medium">Tue</div>
                    <div class="bg-gray-100 p-2 text-center font-medium">Wed</div>
                    <div class="bg-gray-100 p-2 text-center font-medium">Thu</div>
                    <div class="bg-gray-100 p-2 text-center font-medium">Fri</div>
                    <div class="bg-gray-100 p-2 text-center font-medium">Sat</div>
                    <div class="bg-gray-100 p-2 text-center font-medium">Sun</div>

                    <!-- Calendar Cells -->
                    @foreach ($attendanceData as $cell)
                        <div class="bg-white p-3 h-24 {{ !$cell['isCurrentMonth'] ? 'bg-gray-50' : '' }}">
                            @if ($cell['day'])
                                <div class="font-medium mb-1">{{ $cell['day'] }}</div>

                                @if ($cell['status'] === 'present')
                                    <div class="bg-green-100 text-green-800 text-xs p-1 rounded">
                                        Present
                                        @if ($cell['data'] && $cell['data']->check_in)
                                            <div class="mt-1 text-xs">
                                                In: {{ $cell['data']->check_in->format('H:i') }}
                                                @if ($cell['data']->check_out)
                                                    <br>Out: {{ $cell['data']->check_out->format('H:i') }}
                                                    <br>{{ $cell['data']->work_hours }} hrs
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @elseif($cell['status'] === 'leave')
                                    <div class="bg-yellow-100 text-yellow-800 text-xs p-1 rounded">
                                        On Leave
                                    </div>
                                @elseif($cell['status'] === 'absent' && $cell['date'] && $cell['date']->isPast())
                                    <div class="bg-red-100 text-red-800 text-xs p-1 rounded">
                                        Absent
                                    </div>
                                @elseif($cell['status'] === 'weekend')
                                    <div class="bg-gray-100 text-gray-600 text-xs p-1 rounded">
                                        Weekend
                                    </div>
                                @elseif($cell['status'] === 'holiday')
                                    <div class="bg-blue-100 text-blue-800 text-xs p-1 rounded">
                                        Holiday
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('livewire:initialized', function() {
                const ctx = document.getElementById('weeklyHoursChart').getContext('2d');
                const weeklyData = @json($weeklyTaskHours);

                const labels = Object.keys(weeklyData);
                const data = Object.values(weeklyData);

                const chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Hours',
                            data: data,
                            backgroundColor: '#93C5FD',
                            borderColor: '#3B82F6',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Hours'
                                }
                            }
                        }
                    }
                });

                // Update chart when tab changes
                Livewire.on('tabChanged', function() {
                    chart.update();
                });
            });
        </script>
    @endpush
</div>
