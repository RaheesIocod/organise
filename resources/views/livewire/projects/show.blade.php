<div>
    <x-slot name="header">Project Details</x-slot>

    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('projects') }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Projects
        </a>
    </div>

    <!-- Project Information and Time Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Project Information -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">{{ $project->name }}</h2>
                    @if ($project->status === 'not_started')
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                            Not Started
                        </span>
                    @elseif($project->status === 'in_progress')
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            In Progress
                        </span>
                    @elseif($project->status === 'completed')
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Completed
                        </span>
                    @endif
                </div>

                <div class="mb-6">
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Description</h3>
                    <p class="text-gray-800">{{ $project->description }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Start Date</h3>
                        <p class="text-gray-800">{{ $project->start_date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">End Date</h3>
                        <p class="text-gray-800">{{ $project->end_date->format('M d, Y') }}</p>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500">Duration</h3>
                    <p class="text-gray-800">{{ $project->start_date->diffInDays($project->end_date) + 1 }} days</p>
                </div>

                @if ($project->status === 'in_progress')
                    <div class="mt-4">
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Progress</h3>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            @php
                                $totalDays = max(1, $project->start_date->diffInDays($project->end_date) + 1);
                                $daysPassed = min($totalDays, $project->start_date->diffInDays(now()) + 1);
                                $progress = min(100, round(($daysPassed / $totalDays) * 100));
                            @endphp
                            <div class="bg-blue-500 rounded-full h-2" style="width: {{ $progress }}%"></div>
                        </div>
                        <div class="flex justify-between text-xs mt-1">
                            <span>{{ $progress }}% complete</span>
                            <span>{{ $project->end_date->diffForHumans() }}</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Time Tracking Chart -->
        <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Monthly Hours</h2>
                <div class="h-80">
                    <canvas id="monthlyHoursChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Team Members and Recent Time Entries -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
        <!-- Team Members -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Team Members</h2>
                @if (count($teamMembers) > 0)
                    <ul class="divide-y divide-gray-200">
                        @foreach ($teamMembers as $member)
                            <li class="py-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="ml-0">
                                            <p class="text-sm font-medium text-gray-900">{{ $member['name'] }}</p>
                                        </div>
                                    </div>
                                    <div class="text-sm text-gray-500">{{ number_format($member['hours'], 1) }} hours</div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500">No team members have logged hours on this project yet.</p>
                @endif
            </div>
        </div>

        <!-- Recent Time Entries -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Recent Time Entries</h2>
                @php
                    $recentEntries = \App\Models\TaskTimeEntry::where('project_id', $project->id)->with('user')->orderBy('entry_date', 'desc')->limit(10)->get();
                @endphp

                @if (count($recentEntries) > 0)
                    <ul class="divide-y divide-gray-200">
                        @foreach ($recentEntries as $entry)
                            <li class="py-4">
                                <div class="flex justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $entry->description }}</p>
                                        <p class="text-sm text-gray-500">By {{ $entry->user->name }} on {{ $entry->entry_date->format('M d, Y') }}</p>
                                    </div>
                                    <div class="text-sm text-gray-500">{{ number_format($entry->hours_spent, 1) }} hours</div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500">No time entries for this project yet.</p>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('livewire:initialized', () => {
                const ctx = document.getElementById('monthlyHoursChart').getContext('2d');

                const monthlyHoursChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                        datasets: [{
                            label: 'Hours Logged',
                            data: @json($monthlyHours),
                            backgroundColor: 'rgba(59, 130, 246, 0.5)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Hours'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Month'
                                }
                            }
                        }
                    }
                });

                // Update chart when Livewire refreshes the component
                Livewire.hook('message.processed', (message, component) => {
                    if (component.fingerprint.name === 'projects.show' && monthlyHoursChart) {
                        monthlyHoursChart.data.datasets[0].data = @json($monthlyHours);
                        monthlyHoursChart.update();
                    }
                });
            });
        </script>
    @endpush
</div>
