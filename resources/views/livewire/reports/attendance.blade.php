<div>
    <x-slot name="header">Attendance Reports</x-slot>

    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('reports') }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Reports
        </a>
    </div>

    <!-- Month Selector -->
    <div class="flex justify-between items-center mb-6">
        <div class="inline-flex rounded-md shadow-sm">
            <button wire:click="changeMonth('prev')"
                class="py-2 px-4 border border-gray-300 bg-white text-sm font-medium rounded-l-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
            </button>
            <span class="py-2 px-4 border-t border-b border-gray-300 bg-white text-sm font-medium text-gray-700 min-w-[120px] text-center">
                {{ Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y') }}
            </span>
            <button wire:click="changeMonth('next')"
                class="py-2 px-4 border border-gray-300 bg-white text-sm font-medium rounded-r-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Attendance Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
            <div class="text-sm font-medium text-gray-500">Present</div>
            <div class="mt-1 text-3xl font-semibold text-green-600">{{ $present }}</div>
            <div class="text-xs text-gray-500">days</div>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
            <div class="text-sm font-medium text-gray-500">Absent</div>
            <div class="mt-1 text-3xl font-semibold text-red-600">{{ $absent }}</div>
            <div class="text-xs text-gray-500">days</div>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
            <div class="text-sm font-medium text-gray-500">On Leave</div>
            <div class="mt-1 text-3xl font-semibold text-blue-600">{{ $onLeave }}</div>
            <div class="text-xs text-gray-500">days</div>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
            <div class="text-sm font-medium text-gray-500">Weekend/Holiday</div>
            <div class="mt-1 text-3xl font-semibold text-gray-600">{{ $weekends + $holidays }}</div>
            <div class="text-xs text-gray-500">days</div>
        </div>
    </div>

    <!-- Additional Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
            <div class="text-sm font-medium text-gray-500">Total Work Hours</div>
            <div class="mt-1 text-3xl font-semibold text-indigo-600">{{ number_format($totalWorkHours, 1) }}</div>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
            <div class="text-sm font-medium text-gray-500">Avg. Hours/Day</div>
            <div class="mt-1 text-3xl font-semibold text-indigo-600">{{ number_format($averageWorkHours, 1) }}</div>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
            <div class="text-sm font-medium text-gray-500">Late Arrivals</div>
            <div class="mt-1 text-3xl font-semibold text-amber-600">{{ $lateArrivals }}</div>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
            <div class="text-sm font-medium text-gray-500">Early Departures</div>
            <div class="mt-1 text-3xl font-semibold text-amber-600">{{ $earlyDepartures }}</div>
        </div>
    </div>

    <!-- Attendance Records Table -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Daily Attendance</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Day</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-in</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-out</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hours</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($attendanceSummary as $date => $attendance)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $attendance['day'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($attendance['status'] === 'present')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Present</span>
                                    @elseif($attendance['status'] === 'absent')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Absent</span>
                                    @elseif($attendance['status'] === 'on_leave')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">On Leave</span>
                                    @elseif($attendance['status'] === 'weekend')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Weekend</span>
                                    @elseif($attendance['status'] === 'holiday')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">Holiday</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-50 text-gray-500">Upcoming</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $attendance['check_in'] ? $attendance['check_in']->format('h:i A') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $attendance['check_out'] ? $attendance['check_out']->format('h:i A') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $attendance['work_hours'] ? number_format($attendance['work_hours'], 1) : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Attendance Overview</h2>
            <div class="h-80">
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('livewire:initialized', () => {
                const ctx = document.getElementById('attendanceChart').getContext('2d');

                const attendanceChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: ['Present', 'Absent', 'On Leave', 'Weekend/Holiday'],
                        datasets: [{
                            data: [{{ $present }}, {{ $absent }}, {{ $onLeave }}, {{ $weekends + $holidays }}],
                            backgroundColor: [
                                'rgba(72, 187, 120, 0.7)', // green
                                'rgba(237, 100, 100, 0.7)', // red
                                'rgba(66, 153, 225, 0.7)', // blue
                                'rgba(160, 174, 192, 0.7)' // gray
                            ],
                            borderColor: [
                                'rgba(72, 187, 120, 1)',
                                'rgba(237, 100, 100, 1)',
                                'rgba(66, 153, 225, 1)',
                                'rgba(160, 174, 192, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                            }
                        }
                    }
                });

                // Update chart when Livewire refreshes the component
                Livewire.hook('message.processed', (message, component) => {
                    if (component.fingerprint.name === 'reports.attendance' && attendanceChart) {
                        attendanceChart.data.datasets[0].data = [
                            {{ $present }}, {{ $absent }}, {{ $onLeave }}, {{ $weekends + $holidays }}
                        ];
                        attendanceChart.update();
                    }
                });
            });
        </script>
    @endpush
</div>
