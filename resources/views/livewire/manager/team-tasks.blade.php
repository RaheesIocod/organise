<div>
    <x-slot name="header">Team Tasks</x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex flex-col md:flex-row gap-4">
                    <!-- Date Range Filter -->
                    <div class="flex items-center space-x-2">
                        <label class="text-sm text-gray-600">From:</label>
                        <input type="date" wire:model.live="fromDate" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            max="{{ $toDate }}">
                    </div>

                    <div class="flex items-center space-x-2">
                        <label class="text-sm text-gray-600">To:</label>
                        <input type="date" wire:model.live="toDate" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            min="{{ $fromDate }}">
                    </div>

                    <!-- Team Member Filter -->
                    <div class="flex items-center space-x-2">
                        <label class="text-sm text-gray-600">Team Member:</label>
                        <select wire:model.live="selectedTeamMember" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">All Team Members</option>
                            @foreach ($teamMembers as $member)
                                <option value="{{ $member->id }}">{{ $member->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Search -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search tasks..."
                        class="pl-10 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Team Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="font-semibold text-lg text-gray-800">Team Summary</h2>
                </div>
                <div class="p-6">
                    @if (count($this->teamSummary) > 0)
                        <ul class="space-y-4">
                            @foreach ($this->teamSummary as $summary)
                                <li class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-800">{{ $summary['name'] }}</span>
                                    <div class="text-right">
                                        <div class="text-sm font-semibold text-blue-600">{{ number_format($summary['hours'], 1) }} hrs</div>
                                        <div class="text-xs text-gray-500">{{ $summary['entries'] }} tasks</div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center py-4">
                            <p class="text-gray-500">No task data available for the selected period.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Task List -->
        <div class="lg:col-span-3">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="font-semibold text-lg text-gray-800">Team Tasks</h2>
                </div>
                <div class="p-6">
                    @if ($taskEntries->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Team Member</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Task</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hours</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($taskEntries as $entry)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $entry->user->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $entry->entry_date->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $entry->project->name }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                {{ $entry->task_name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ number_format($entry->hours_spent, 1) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $taskEntries->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No task entries found</h3>
                            <p class="mt-1 text-sm text-gray-500">Adjust your filters to see more results.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Hours Chart -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="font-semibold text-lg text-gray-800">Daily Task Hours</h2>
        </div>
        <div class="p-6">
            <canvas id="dailyChart" height="100"></canvas>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('livewire:initialized', function() {
                // Daily Chart
                const dailyCtx = document.getElementById('dailyChart').getContext('2d');
                const dailyChart = new Chart(dailyCtx, {
                    type: 'bar',
                    data: @json($this->dailyChartData),
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

                // Listen for filter changes to update chart
                Livewire.on('refreshChart', function(chartData) {
                    dailyChart.data = chartData;
                    dailyChart.update();
                });
            });
        </script>
    @endpush
</div>
