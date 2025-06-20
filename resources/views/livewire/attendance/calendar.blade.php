<div>
    <div class="container mx-auto px-4 py-8">
        <div class="mb-6 flex justify-between items-center">
            <h1 class="text-2xl font-semibold text-gray-800">Attendance Calendar</h1>
            <a href="{{ route('attendance') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">
                Attendance Overview
            </a>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6 flex justify-between items-center border-b">
                <button wire:click="previousMonth" class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
                <h2 class="text-lg font-semibold text-gray-700">{{ $monthName }} {{ $year }}</h2>
                <button wire:click="nextMonth" class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            <!-- Legend -->
            <div class="p-4 bg-gray-50 grid grid-cols-2 md:grid-cols-5 gap-3">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-green-500 rounded-sm mr-2"></div>
                    <span class="text-sm text-gray-700">Present</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-red-500 rounded-sm mr-2"></div>
                    <span class="text-sm text-gray-700">Absent</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-blue-500 rounded-sm mr-2"></div>
                    <span class="text-sm text-gray-700">Leave</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-yellow-500 rounded-sm mr-2"></div>
                    <span class="text-sm text-gray-700">Holiday</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-gray-300 rounded-sm mr-2"></div>
                    <span class="text-sm text-gray-700">Weekend</span>
                </div>
            </div>

            <!-- FullCalendar Integration -->
            <div class="p-4">
                <div id="calendar" style="height: 600px;"></div>

                @push('scripts')
                    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />
                    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js'></script>

                    <script>
                        document.addEventListener('livewire:initialized', function() {
                            var calendarEl = document.getElementById('calendar');
                            var calendar = new FullCalendar.Calendar(calendarEl, {
                                initialView: 'dayGridMonth',
                                headerToolbar: {
                                    left: 'prev,next today',
                                    center: 'title',
                                    right: 'dayGridMonth,timeGridWeek'
                                },
                                events: @json($calendarEvents),
                                eventClick: function(info) {
                                    const event = info.event;
                                    const type = event.extendedProps.type;
                                    let content = '';

                                    if (type === 'attendance') {
                                        content = `
                                        <div class="p-4">
                                            <h3 class="font-bold text-lg mb-2">Attendance Details</h3>
                                            <p class="mb-2"><strong>Date:</strong> ${event.start.toLocaleDateString()}</p>
                                            <p class="mb-2"><strong>Check In:</strong> ${event.extendedProps.checkIn || 'N/A'}</p>
                                            <p class="mb-2"><strong>Check Out:</strong> ${event.extendedProps.checkOut || 'N/A'}</p>
                                            <p><strong>Work Hours:</strong> ${event.extendedProps.workHours || '0'} hours</p>
                                        </div>
                                    `;
                                    } else if (type === 'leave') {
                                        content = `
                                        <div class="p-4">
                                            <h3 class="font-bold text-lg mb-2">Leave Details</h3>
                                            <p class="mb-2"><strong>Date:</strong> ${event.start.toLocaleDateString()}</p>
                                            <p class="mb-2"><strong>Type:</strong> ${event.extendedProps.leaveType || 'N/A'}</p>
                                            <p><strong>Reason:</strong> ${event.extendedProps.reason || 'N/A'}</p>
                                        </div>
                                    `;
                                    } else if (type === 'holiday') {
                                        content = `
                                        <div class="p-4">
                                            <h3 class="font-bold text-lg mb-2">Holiday</h3>
                                            <p class="mb-2"><strong>Date:</strong> ${event.start.toLocaleDateString()}</p>
                                            <p class="mb-2"><strong>Holiday:</strong> ${event.title}</p>
                                            <p><strong>Description:</strong> ${event.extendedProps.description || 'N/A'}</p>
                                        </div>
                                    `;
                                    }

                                    if (content) {
                                        // Use a modal or toast notification library here
                                        alert(event.title + ' - ' + event.start.toLocaleDateString());
                                        console.log(content); // In a real app, replace with proper modal
                                    }
                                }
                            });
                            calendar.render();

                            // Connect to Livewire events
                            Livewire.on('calendarUpdated', function(events) {
                                calendar.removeAllEvents();
                                calendar.addEventSource(events);
                            });
                        });
                    </script>
                @endpush
                <div class="grid grid-cols-7 gap-1">
                    <!-- Days of the Week -->
                    @foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $dayOfWeek)
                        <div class="bg-gray-100 p-2 text-center rounded-sm">
                            <span class="font-medium text-sm text-gray-700">{{ $dayOfWeek }}</span>
                        </div>
                    @endforeach

                    <!-- Calendar Days -->
                    @foreach ($calendarData as $day)
                        <div
                            class="{{ $day['isToday'] ? 'border-2 border-blue-600' : '' }} 
                                {{ !$day['day']
                                    ? 'bg-white'
                                    : ($day['status'] === 'present'
                                        ? 'bg-green-100'
                                        : ($day['status'] === 'absent'
                                            ? 'bg-red-100'
                                            : ($day['status'] === 'leave'
                                                ? 'bg-blue-100'
                                                : ($day['status'] === 'holiday'
                                                    ? 'bg-yellow-100'
                                                    : ($day['status'] === 'weekend'
                                                        ? 'bg-gray-100'
                                                        : ($day['status'] === 'future'
                                                            ? 'bg-white'
                                                            : 'bg-white')))))) }} 
                                p-2 min-h-[80px] rounded-sm">

                            @if ($day['day'])
                                <div class="flex justify-between items-start">
                                    <span class="{{ $day['isToday'] ? 'font-bold' : '' }} 
                                          {{ $day['isWeekend'] ? 'text-gray-500' : 'text-gray-700' }}">
                                        {{ $day['day'] }}
                                    </span>

                                    @if ($day['status'] === 'present')
                                        <span class="inline-flex items-center justify-center bg-green-500 h-4 w-4 rounded-full">
                                            <svg class="h-2 w-2 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </span>
                                    @elseif($day['status'] === 'absent')
                                        <span class="inline-flex items-center justify-center bg-red-500 h-4 w-4 rounded-full">
                                            <svg class="h-2 w-2 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </span>
                                    @endif
                                </div>

                                @if ($day['info'])
                                    <div
                                        class="mt-1 text-xs {{ $day['isHoliday'] ? 'text-yellow-700' : ($day['isLeave'] ? 'text-blue-700' : 'text-gray-600') }}">
                                        {{ $day['info'] }}
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Monthly Summary</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Present Days:</span>
                        <span class="font-medium">{{ collect($calendarData)->where('status', 'present')->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Leaves:</span>
                        <span class="font-medium">{{ collect($calendarData)->where('status', 'leave')->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Holidays:</span>
                        <span class="font-medium">{{ collect($calendarData)->where('status', 'holiday')->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Weekends:</span>
                        <span class="font-medium">{{ collect($calendarData)->where('isWeekend', true)->count() }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 md:col-span-2">
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Upcoming Events</h3>
                <div class="space-y-4">
                    @php
                        $futureHolidays = collect($calendarData)->where('isHoliday', true)->where('date', '>=', now())->take(3);

                        $futureLeaves = collect($calendarData)->where('isLeave', true)->where('date', '>=', now())->take(3);
                    @endphp

                    @forelse(($futureHolidays->merge($futureLeaves)->sortBy('date')->take(3)) as $event)
                        <div class="flex items-start space-x-3">
                            <div class="bg-{{ $event['isHoliday'] ? 'yellow' : 'blue' }}-100 text-{{ $event['isHoliday'] ? 'yellow' : 'blue' }}-800 p-2 rounded-md">
                                <span class="font-medium">{{ $event['date']->format('d') }}</span>
                                <div class="text-xs">{{ $event['date']->format('M') }}</div>
                            </div>
                            <div>
                                <p class="font-medium">{{ $event['isHoliday'] ? 'Holiday' : 'Leave' }}: {{ $event['info'] }}</p>
                                <p class="text-sm text-gray-500">{{ $event['date']->format('l, F j, Y') }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500">No upcoming events in this month.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
