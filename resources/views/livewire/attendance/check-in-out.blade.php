<div class="bg-white shadow-sm rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="font-semibold text-lg text-gray-800">Today's Attendance</h2>
    </div>
    <div class="p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <div class="mr-4">
                    <span class="text-sm text-gray-500">Date:</span>
                    <span class="text-lg font-semibold">{{ now()->format('d M Y') }}</span>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Status:</span>
                    <span class="ml-2">
                        @if ($todayStatus === 'present')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Present</span>
                        @elseif($todayStatus === 'absent')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Absent</span>
                        @elseif($todayStatus === 'leave')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">On Leave ({{ $leaveType }})</span>
                        @elseif($todayStatus === 'holiday')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Holiday ({{ $holidayName }})</span>
                        @elseif($todayStatus === 'weekend')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Weekend</span>
                        @endif
                    </span>
                </div>
            </div>

            <div class="text-right">
                <span class="block text-sm text-gray-500">Current Time</span>
                <span class="text-lg font-mono" x-data="{ time: new Date().toLocaleTimeString() }" x-init="setInterval(() => { time = new Date().toLocaleTimeString() }, 1000)" x-text="time"></span>
            </div>
        </div>

        @if (session()->has('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gray-50 p-4 rounded-lg">
                <span class="block text-sm text-gray-500">Check In</span>
                <span class="text-lg font-semibold">{{ $checkInTime ?? 'Not checked in' }}</span>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg">
                <span class="block text-sm text-gray-500">Check Out</span>
                <span class="text-lg font-semibold">{{ $checkOutTime ?? 'Not checked out' }}</span>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg">
                <span class="block text-sm text-gray-500">Work Hours</span>
                <span class="text-lg font-semibold">{{ $workHours ?? '0.00' }} hrs</span>
            </div>
        </div>

        <div class="mt-6 flex justify-center space-x-6">
            @if (!$isHoliday && !$isOnLeave && !now()->isWeekend())
                @if (!$checkedIn)
                    <button type="button" wire:click="checkIn"
                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                        </svg>
                        Check In
                    </button>
                @elseif(!$checkedOut)
                    <button type="button" wire:click="checkOut"
                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8 7a1 1 0 00-1 1v4a1 1 0 001 1h4a1 1 0 001-1V8a1 1 0 00-1-1H8z" clip-rule="evenodd" />
                        </svg>
                        Check Out
                    </button>
                @else
                    <p class="text-gray-500">You've completed your work for today.</p>
                @endif
            @else
                @if ($isHoliday)
                    <p class="text-blue-600">Today is a holiday: {{ $holidayName }}</p>
                @elseif($isOnLeave)
                    <p class="text-yellow-600">You're on {{ $leaveType }} leave today</p>
                @else
                    <p class="text-gray-600">It's the weekend. Enjoy your day off!</p>
                @endif
            @endif
        </div>
    </div>
</div>
