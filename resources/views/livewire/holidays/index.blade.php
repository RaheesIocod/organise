<div>
    <div class="container mx-auto px-4 py-8">
        <div class="mb-6 flex justify-between items-center">
            <h1 class="text-2xl font-semibold text-gray-800">Holidays</h1>
            <div class="flex space-x-2">
                <button @class([
                    'px-3 py-1 rounded focus:outline-none',
                    'bg-blue-600 text-white' => $viewMode === 'table',
                    'bg-gray-200 hover:bg-gray-300 text-gray-800' => $viewMode !== 'table',
                ]) wire:click="setViewMode('table')">
                    Table View
                </button>
                <button @class([
                    'px-3 py-1 rounded focus:outline-none',
                    'bg-blue-600 text-white' => $viewMode === 'calendar',
                    'bg-gray-200 hover:bg-gray-300 text-gray-800' => $viewMode !== 'calendar',
                ]) wire:click="setViewMode('calendar')">
                    Calendar View
                </button>
            </div>
        </div>

        @if ($viewMode === 'table')
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recurring</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($holidays as $holiday)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $holiday->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $holiday->date->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $holiday->description }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $holiday->is_recurring ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $holiday->is_recurring ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">No holidays found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4">
                    {{ $holidays->links() }}
                </div>
            </div>
        @else
            <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
                <div class="p-6">
                    <div id="holidaysCalendar" class="w-full"></div>
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            document.addEventListener('livewire:initialized', function() {
                if (@js($viewMode) === 'calendar') {
                    initializeCalendar();
                }

                Livewire.hook('morph.updated', ({
                    el
                }) => {
                    if (@js($viewMode) === 'calendar') {
                        initializeCalendar();
                    }
                });

                function initializeCalendar() {
                    const calendarEl = document.getElementById('holidaysCalendar');
                    if (!calendarEl) return;

                    const holidays = @js(
    $holidays->map(function ($holiday) {
        return [
            'id' => $holiday->id,
            'title' => $holiday->name,
            'start' => $holiday->date->format('Y-m-d'),
            'description' => $holiday->description,
            'recurring' => $holiday->is_recurring,
        ];
    }),
);

                    const calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'dayGridMonth',
                        headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,listYear'
                        },
                        events: holidays.map(holiday => ({
                            id: holiday.id,
                            title: holiday.title,
                            start: holiday.start,
                            allDay: true,
                            backgroundColor: '#FCD34D',
                            borderColor: '#F59E0B',
                            textColor: '#78350F',
                            extendedProps: {
                                description: holiday.description,
                                recurring: holiday.recurring
                            }
                        })),
                        eventClick: function(info) {
                            const event = info.event;
                            const props = event.extendedProps;

                            alert(`
                            Holiday: ${event.title}
                            Date: ${new Date(event.start).toLocaleDateString()}
                            ${props.description ? `Description: ${props.description}` : ''}
                            ${props.recurring ? 'This is a recurring holiday.' : ''}
                        `);
                        }
                    });

                    calendar.render();
                }
            });
        </script>
    @endpush
</div>
