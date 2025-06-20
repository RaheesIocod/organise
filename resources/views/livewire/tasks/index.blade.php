<div x-data="{ showEntryForm: false, chartType: 'bar' }" class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-100 transition-colors duration-500">
    <!-- Top Header -->
    <header class="flex flex-col sm:flex-row items-center justify-between px-6 py-6 bg-white/80 shadow rounded-b-2xl mb-8 animate-fade-in">
        <div class="flex items-center space-x-4">
            <img src="https://i.pravatar.cc/40?img=3" alt="User Avatar" class="rounded-full shadow-lg border-2 border-blue-400">
            <div>
                <div class="text-lg font-semibold text-gray-700">Welcome back, <span class="text-blue-600">{{ Auth::user()->name ?? 'User' }}</span>!</div>
                <div class="text-xs text-gray-400">Ready to track your time?</div>
            </div>
        </div>
        <h1 class="text-2xl font-bold text-gray-800 mt-4 sm:mt-0 flex items-center space-x-2">
            <svg class="w-7 h-7 text-purple-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0h6" />
            </svg>
            <span>Time Tracking</span>
        </h1>
    </header>

    <div class="flex flex-col lg:flex-row gap-8 px-4">
        <!-- Sidebar: Summary & Streaks -->
        <aside class="lg:w-1/4 flex-shrink-0">
            <div class="backdrop-blur-lg bg-white/70 shadow-2xl rounded-2xl border border-blue-100 mb-8 animate-fade-in-up">
                <div class="p-8">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center space-x-2">
                        <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m4 4h-1a2 2 0 01-2-2V7a2 2 0 012-2h1a2 2 0 012 2v7a2 2 0 01-2 2z" />
                        </svg>
                        <span>Summary</span>
                    </h2>
                    <dl class="grid grid-cols-1 gap-4">
                        <div class="p-3 bg-gradient-to-tr from-blue-100/80 to-blue-200/80 rounded-xl shadow flex flex-col">
                            <dt class="text-sm font-medium text-blue-800 flex items-center" x-tooltip="Total hours logged today">Today</dt>
                            <dd class="mt-1 flex items-baseline justify-between">
                                <span class="text-2xl font-semibold text-blue-600">{{ number_format($todayHours, 1) }}</span>
                                <span class="text-sm text-blue-600">hours</span>
                            </dd>
                            <div class="w-full bg-blue-200 rounded-full h-2 mt-2">
                                <div class="bg-blue-500 h-2 rounded-full transition-all duration-500" style="width: {{ min(100, ($todayHours / 8) * 100) }}%"></div>
                            </div>
                        </div>
                        <div class="p-3 bg-gradient-to-tr from-indigo-100/80 to-indigo-200/80 rounded-xl shadow flex flex-col">
                            <dt class="text-sm font-medium text-indigo-800 flex items-center" x-tooltip="Total hours logged this week">This Week</dt>
                            <dd class="mt-1 flex items-baseline justify-between">
                                <span class="text-2xl font-semibold text-indigo-600">{{ number_format($weekHours, 1) }}</span>
                                <span class="text-sm text-indigo-600">hours</span>
                            </dd>
                            <div class="w-full bg-indigo-200 rounded-full h-2 mt-2">
                                <div class="bg-indigo-500 h-2 rounded-full transition-all duration-500" style="width: {{ min(100, ($weekHours / 40) * 100) }}%"></div>
                            </div>
                        </div>
                        <div class="p-3 bg-gradient-to-tr from-purple-100/80 to-purple-200/80 rounded-xl shadow flex flex-col">
                            <dt class="text-sm font-medium text-purple-800 flex items-center" x-tooltip="Total hours logged this month">This Month</dt>
                            <dd class="mt-1 flex items-baseline justify-between">
                                <span class="text-2xl font-semibold text-purple-600">{{ number_format($monthHours, 1) }}</span>
                                <span class="text-sm text-purple-600">hours</span>
                            </dd>
                        </div>
                    </dl>
                    <!-- Streaks/Achievements -->
                    <div class="mt-6 flex items-center space-x-3 animate-fade-in">
                        <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.454a1 1 0 00-1.175 0l-3.38 2.454c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z" />
                        </svg>
                        <span class="text-sm font-semibold text-gray-700">Streak: <span class="text-blue-600">{{ $streak ?? 0 }}</span> days</span>
                        <span class="text-xs text-gray-400">Keep it up!</span>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col gap-8">
            <!-- Collapsible Time Entry Form -->
            <div class="backdrop-blur-lg bg-white/70 shadow-2xl rounded-2xl border border-blue-100 animate-fade-in-up">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h2 class="text-lg font-bold text-gray-800 flex items-center space-x-2">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Log Time</span>
                    </h2>
                    <button @click="showEntryForm = !showEntryForm" class="p-2 rounded-full bg-gradient-to-tr from-blue-100 to-purple-100 shadow hover:scale-110 transition-transform" x-tooltip="Toggle form">
                        <svg x-show="!showEntryForm" class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        <svg x-show="showEntryForm" class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4" />
                        </svg>
                    </button>
                </div>
                <div x-show="showEntryForm" x-transition class="p-8">
                    <form wire:submit.prevent="createTaskEntry" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="selectedDate" class="block text-sm font-medium text-gray-700">Date</label>
                                <input type="date" wire:model="selectedDate" id="selectedDate" class="mt-1 block w-full border-0 rounded-lg shadow-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-white/80" max="{{ Carbon\Carbon::now()->format('Y-m-d') }}">
                            </div>
                            <div>
                                <label for="selectedProject" class="block text-sm font-medium text-gray-700">Project</label>
                                <select wire:model="selectedProject" id="selectedProject" class="mt-1 block w-full border-0 rounded-lg shadow-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-white/80">
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                                    @endforeach
                                </select>
                                @error('selectedProject')
                                    <span class="text-red-600 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea wire:model="description" id="description" rows="3" class="mt-1 block w-full border-0 rounded-lg shadow-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-white/80" placeholder="What did you work on?"></textarea>
                            @error('description')
                                <span class="text-red-600 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label for="hoursSpent" class="block text-sm font-medium text-gray-700">Hours Spent</label>
                            <input type="number" wire:model="hoursSpent" id="hoursSpent" step="0.5" min="0.5" max="24" class="mt-1 block w-full border-0 rounded-lg shadow-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-white/80">
                            @error('hoursSpent')
                                <span class="text-red-600 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="flex items-center justify-end mt-6">
                            <button type="submit" class="inline-flex items-center px-6 py-2 border border-transparent text-base font-semibold rounded-lg shadow-md text-white bg-gradient-to-tr from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                Save Entry
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sticky Search/Filter Bar & Entries Table -->
            <div class="backdrop-blur-lg bg-white/70 shadow-2xl rounded-2xl border border-purple-100 animate-fade-in-up">
                <div class="sticky top-0 z-10 bg-white/80 rounded-t-2xl px-8 py-4 flex flex-col md:flex-row items-center justify-between gap-4 border-b border-gray-200">
                    <div class="flex items-center space-x-2 w-full md:w-auto">
                        <input type="date" wire:model.live="dateFilter" class="border-0 rounded-lg shadow-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-white/80" x-tooltip="Filter by date">
                        @if ($dateFilter)
                            <button wire:click="setDateFilter()" class="ml-1 text-gray-400 hover:text-gray-600">
                                <span class="sr-only">Clear date filter</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        @endif
                    </div>
                    <div class="relative w-full md:w-1/3">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" wire:model.live.debounce.300ms="search" class="pl-10 pr-3 py-2 border-0 rounded-lg shadow-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-white/80 w-full" placeholder="Search tasks" x-tooltip="Search tasks">
                    </div>
                </div>
                <div class="overflow-x-auto animate-fade-in">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-gray-50 to-blue-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Project</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Description</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Hours</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white/80 divide-y divide-gray-200">
                            @foreach ($taskEntries as $entry)
                                <tr class="hover:bg-blue-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $entry->entry_date->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $entry->project->name }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $entry->description }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($entry->hours_spent, 1) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button wire:click="deleteTaskEntry({{ $entry->id }})" class="text-red-600 hover:text-red-900 transition-colors" x-tooltip="Delete entry">
                                            <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 px-8 pb-6">
                    {{ $taskEntries->links() }}
                </div>
                @if (!$taskEntries->count())
                    <div class="text-center py-8 animate-fade-in">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No time entries found</h3>
                        @if ($search || $dateFilter || $selectedProject)
                            <p class="mt-1 text-sm text-gray-500">Try adjusting your search or filter criteria.</p>
                        @else
                            <p class="mt-1 text-sm text-gray-500">Get started by creating a new time entry.</p>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Charts Card -->
            <div class="backdrop-blur-lg bg-white/70 shadow-2xl rounded-2xl border border-blue-100 mt-2 animate-fade-in-up">
                <div class="p-8 border-b border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-800 flex items-center space-x-2">
                            <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0h6" />
                            </svg>
                            <span>Time Analysis</span>
                        </h2>
                        <div class="flex items-center space-x-2">
                            <button @click="chartType = chartType === 'bar' ? 'line' : 'bar'" class="p-2 rounded-lg bg-gradient-to-tr from-blue-200 to-purple-200 shadow hover:scale-110 transition-transform" x-tooltip="Toggle chart type">
                                <svg x-show="chartType === 'bar'" class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h4v11H3zM9 3h4v18H9zM15 6h4v15h-4z" />
                                </svg>
                                <svg x-show="chartType === 'line'" class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 17l6-6 4 4 8-8" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-md font-medium text-gray-700 mb-3 flex items-center space-x-2">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h4v11H3zM9 3h4v18H9zM15 6h4v15h-4z" />
                                </svg>
                                <span>This Week's Hours</span>
                            </h3>
                            <div class="bg-white/80 p-4 rounded-xl shadow">
                                <canvas id="weeklyChart" height="200"></canvas>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-md font-medium text-gray-700 mb-3 flex items-center space-x-2">
                                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 11V7a4 4 0 118 0v4m-4 4v4a4 4 0 11-8 0v-4" />
                                </svg>
                                <span>Monthly Project Distribution</span>
                            </h3>
                            <div class="bg-white/80 p-4 rounded-xl shadow">
                                <canvas id="monthlyChart" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Floating Add Button (Mobile Only) -->
    <button @click="showEntryForm = true" class="fixed z-50 bottom-8 right-8 bg-gradient-to-tr from-blue-600 to-purple-600 text-white p-4 rounded-full shadow-xl hover:scale-110 transition-transform focus:outline-none focus:ring-4 focus:ring-blue-300 animate-bounce block lg:hidden" x-tooltip="Quick Log Time">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
    </button>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://unpkg.com/@ryangjchandler/alpine-tooltip@2.x.x/dist/cdn.min.js"></script>
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.plugin(window.Tooltip);
            });
            document.addEventListener('livewire:initialized', function() {
                let chartType = 'bar';
                const weeklyCtx = document.getElementById('weeklyChart').getContext('2d');
                let weeklyChart = new Chart(weeklyCtx, {
                    type: chartType,
                    data: @json($this->weeklyChartData),
                    options: {
                        responsive: true,
                        animation: {
                            duration: 1000,
                            easing: 'easeOutQuart'
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                enabled: true
                            }
                        },
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
                const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
                let monthlyChart = new Chart(monthlyCtx, {
                    type: 'pie',
                    data: @json($this->monthlyChartData),
                    options: {
                        responsive: true,
                        animation: {
                            duration: 1000,
                            easing: 'easeOutQuart'
                        },
                        plugins: {
                            legend: {
                                position: 'bottom'
                            },
                            tooltip: {
                                enabled: true
                            }
                        }
                    }
                });
                Livewire.on('refreshCharts', function(weeklyData, monthlyData) {
                    weeklyChart.data = weeklyData;
                    weeklyChart.update();
                    monthlyChart.data = monthlyData;
                    monthlyChart.update();
                });
                // Chart type toggle
                document.querySelector('[x-data]').__x.$watch('chartType', function(value) {
                    weeklyChart.destroy();
                    weeklyChart = new Chart(weeklyCtx, {
                        type: value,
                        data: @json($this->weeklyChartData),
                        options: weeklyChart.options
                    });
                });
            });
        </script>
        <style>
            .animate-fade-in {
                animation: fadeIn 1s ease;
            }

            .animate-fade-in-up {
                animation: fadeInUp 1s ease;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                }

                to {
                    opacity: 1;
                }
            }

            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        </style>
    @endpush
</div>
