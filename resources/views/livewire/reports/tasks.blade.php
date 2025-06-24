<div class="space-y-6">
    <style>
        /* Chart styling */
        .tooltip {
            transition: opacity 0.2s ease-in-out;
            pointer-events: none;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
        }

        /* Chart container styling */
        .h-64 {
            height: 16rem;
            position: relative;
        }

        canvas {
            display: block !important;
        }

        /* Fix for Chart.js rendering */
        #dailyHoursChart {
            max-height: 100%;
        }
    </style>
    <!-- Month/Year Selector and Project Filter -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Month/Year Selector -->
        <div class="bg-white p-4 rounded-lg shadow-sm flex items-center justify-between">
            <button wire:click="changeMonth('prev')" class="p-2 rounded-full hover:bg-gray-100">
                <span class="material-icons-outlined">chevron_left</span>
            </button>
            <h2 class="text-xl font-semibold text-gray-800">{{ Carbon\Carbon::createFromDate($year, $month)->format('F Y') }}</h2>
            <button wire:click="changeMonth('next')" class="p-2 rounded-full hover:bg-gray-100">
                <span class="material-icons-outlined">chevron_right</span>
            </button>
        </div>

        <!-- Project Filter Dropdown -->
        <div class="bg-white p-4 rounded-lg shadow-sm">
            <label for="project-filter" class="block text-sm font-medium text-gray-700 mb-1">Filter by Project</label>
            <select id="project-filter" wire:model.live="selectedProject"
                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="">All Projects</option>
                @foreach ($projects as $project)
                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Total Hours Summary -->
        <div class="bg-white p-4 rounded-lg shadow-sm flex flex-col justify-center">
            <div class="text-sm text-gray-500">Total Hours This Month</div>
            <div class="text-2xl font-bold text-indigo-600">{{ number_format($totalHours, 1) }}</div>
            <div class="text-xs text-gray-500">across {{ count($projectHours) }} projects</div>
        </div>
    </div>

    <!-- Charts and Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Daily Hours Chart -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-4 border-b">
                <h3 class="text-lg font-medium text-gray-800">Daily Hours</h3>
                <p class="text-sm text-gray-500">Hours tracked each day of {{ Carbon\Carbon::createFromDate($year, $month)->format('F Y') }}</p>
            </div>
            <div class="p-4">
                @php
                    $totalDailyHours = array_sum($dailyHours);
                    $daysInMonth = Carbon\Carbon::createFromDate($year, $month, 1)->daysInMonth;

                    // Count days with tracked hours
                    $daysWithHours = count(
                        array_filter($dailyHours, function ($hours) {
                            return $hours > 0;
                        }),
                    );

                    // Format the data for the daily hours chart
                    $chartDays = [];
                    $chartHours = [];
                    $chartBackgrounds = [];
                    $chartBorderColors = [];

                    for ($day = 1; $day <= $daysInMonth; $day++) {
                        $key = (string) $day;
                        $chartDays[] = $day;
                        $chartHours[] = isset($dailyHours[$key]) ? (float) $dailyHours[$key] : 0;

                        // Highlight the current day
                        $isToday = $day == date('j') && $month == date('n') && $year == date('Y');
                        $chartBackgrounds[] = $isToday ? 'rgba(99, 102, 241, 0.9)' : 'rgba(99, 102, 241, 0.6)';
                        $chartBorderColors[] = $isToday ? 'rgb(79, 70, 229)' : 'rgba(79, 70, 229, 0.8)';
                    }
                @endphp

                @if ($totalDailyHours == 0)
                    <div class="h-64 flex items-center justify-center">
                        <div class="text-gray-400 text-center">
                            <span class="material-icons-outlined text-5xl">bar_chart</span>
                            <p class="mt-2">No hours tracked for {{ Carbon\Carbon::createFromDate($year, $month)->format('F Y') }}</p>
                            <p class="text-sm mt-1">Track time to see your daily activity</p>
                        </div>
                    </div>
                @else
                    <div class="h-64">
                        <canvas id="dailyHoursChart"></canvas>
                    </div>
                    <div class="text-xs text-gray-500 mt-3 flex justify-between items-center">
                        <div>
                            <span class="font-medium text-indigo-600">{{ $daysWithHours }}</span> {{ Str::plural('day', $daysWithHours) }} with tracked hours
                        </div>
                        <div>
                            <span class="font-medium text-indigo-600">{{ number_format($totalDailyHours, 1) }}</span> {{ Str::plural('hour', $totalDailyHours) }} total
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Project Distribution Chart -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-4 border-b">
                <h3 class="text-lg font-medium text-gray-800">Project Distribution</h3>
                <p class="text-sm text-gray-500">Hours tracked per project in {{ Carbon\Carbon::createFromDate($year, $month)->format('F Y') }}</p>
            </div>
            <div class="p-4">
                @if (empty($projectHours))
                    <div class="text-center py-4 text-gray-500">No project hours tracked this month</div>
                @else
                    <div class="space-y-4">
                        @foreach ($projectHours as $projectId => $projectData)
                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-sm font-medium text-gray-700">{{ $projectData['name'] }}</span>
                                    <span class="text-sm text-gray-500">{{ $projectData['hours'] }} hrs</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ ($projectData['hours'] / $totalHours) * 100 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Yearly Hours Chart -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-4 border-b">
            <h3 class="text-lg font-medium text-gray-800">Monthly Hours for {{ $year }}</h3>
            <p class="text-sm text-gray-500">Hours tracked each month of the year</p>
        </div>
        <div class="p-4">
            <div class="h-64">
                <canvas id="yearlyHoursChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Detailed Task Entries -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-4 border-b">
            <h3 class="text-lg font-medium text-gray-800">Detailed Time Entries</h3>
            <p class="text-sm text-gray-500">All time entries for {{ Carbon\Carbon::createFromDate($year, $month)->format('F Y') }}</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hours</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($taskEntries as $entry)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($entry['date'])->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $entry['project_name'] }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">
                                {{ $entry['description'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $entry['hours'] }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                No time entries found for this month
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        // Initialize Yearly Hours Chart
        const yearlyCtx = document.getElementById('yearlyHoursChart').getContext('2d');
        const yearlyHoursChart = new Chart(yearlyCtx, {
            type: 'bar',
            data: {
                labels: monthNames,
                datasets: [{
                    label: 'Hours',
                    data: @json($monthlyHours),
                    backgroundColor: 'rgba(79, 70, 229, 0.6)',
                    borderColor: 'rgba(79, 70, 229, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            title: function(context) {
                                return monthNames[context[0].dataIndex];
                            },
                            label: function(context) {
                                let label = '';
                                if (context.parsed.y !== null) {
                                    label += context.parsed.y + ' hours';
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value + ' hrs';
                            }
                        }
                    }
                }
            }
        });

        // Initialize Daily Hours Chart if it exists (data is not empty)
        function initDailyHoursChart() {
            const dailyHoursElement = document.getElementById('dailyHoursChart');
            if (!dailyHoursElement) {
                console.log('Daily Hours Chart element not found');
                return null;
            }

            try {
                const dailyCtx = dailyHoursElement.getContext('2d');
                const days = @json($chartDays ?? []);
                const hours = @json($chartHours ?? []);
                const bgColors = @json($chartBackgrounds ?? []);
                const borderColors = @json($chartBorderColors ?? []);

                console.log('Initializing Daily Hours Chart with:', {
                    days,
                    hours
                });

                return new Chart(dailyCtx, {
                    type: 'bar',
                    data: {
                        labels: days,
                        datasets: [{
                            label: 'Hours Tracked',
                            data: hours,
                            backgroundColor: bgColors,
                            borderColor: borderColors,
                            borderWidth: 1,
                            borderRadius: 3,
                            barPercentage: 0.8,
                            categoryPercentage: 0.9
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(30, 41, 59, 0.9)',
                                titleColor: '#fff',
                                bodyColor: '#fff',
                                padding: 10,
                                cornerRadius: 4,
                                displayColors: false,
                                callbacks: {
                                    title: function(context) {
                                        const day = context[0].label;
                                        return '{{ Carbon\Carbon::createFromDate($year, $month, 1)->format('F') }} ' + day + ', {{ $year }}';
                                    },
                                    label: function(context) {
                                        const hours = context.parsed.y;
                                        if (hours === 0) return 'No hours tracked';
                                        return hours.toFixed(1) + (hours === 1 ? ' hour' : ' hours') + ' tracked';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    color: '#6B7280',
                                    font: {
                                        size: 11
                                    },
                                    callback: function(value) {
                                        return value + ' hrs';
                                    }
                                },
                                grid: {
                                    color: 'rgba(226, 232, 240, 0.6)'
                                },
                                border: {
                                    dash: [4, 4]
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: '#6B7280',
                                    font: {
                                        size: 11
                                    },
                                    maxRotation: 0,
                                    autoSkip: true,
                                    maxTicksLimit: window.innerWidth < 640 ? 7 : (window.innerWidth < 1024 ? 15 : 31)
                                }
                            }
                        },
                        animation: {
                            duration: 500
                        }
                    }
                });
            } catch (error) {
                console.error('Error initializing Daily Hours Chart:', error);
                return null;
            }
        }

        // Initialize the chart
        let dailyHoursChart = initDailyHoursChart();

        // Handle Livewire navigation and updates
        document.addEventListener('livewire:navigated', function() {
            console.log('Livewire navigated, updating charts');

            // Update yearly hours chart
            if (yearlyHoursChart) {
                yearlyHoursChart.data.datasets[0].data = @json($monthlyHours);
                yearlyHoursChart.update();
            }

            // Re-initialize daily hours chart
            if (dailyHoursChart) {
                dailyHoursChart.destroy();
            }

            // Wait a moment for the DOM to update
            setTimeout(() => {
                dailyHoursChart = initDailyHoursChart();
            }, 100);
        });

        // Handle Livewire updates
        Livewire.on('taskDataUpdated', function() {
            console.log('Task data updated, refreshing charts');

            // Update yearly hours chart
            if (yearlyHoursChart) {
                yearlyHoursChart.data.datasets[0].data = @json($monthlyHours);
                yearlyHoursChart.update();
            }

            // For daily hours, we need to handle a full refresh
            if (dailyHoursChart) {
                dailyHoursChart.destroy();
            }

            // Delay slightly to allow DOM updates
            setTimeout(() => {
                dailyHoursChart = initDailyHoursChart();
            }, 100);
        });
    });
</script>
