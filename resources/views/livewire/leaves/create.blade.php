<div x-data="{
    currentDate: new Date(),
    selectedFromDate: '{{ $fromDate }}',
    selectedToDate: '{{ $toDate }}',
    selectionMode: 'start', // 'start' or 'end'
    daysInMonth: [],
    currentMonth: new Date().getMonth(),
    currentYear: new Date().getFullYear(),
    leaveTypeDetails: null,

    // Computed property to get all dates in the selected range
    get dateRange() {
        if (!this.selectedFromDate || !this.selectedToDate) return [];

        const start = new Date(this.selectedFromDate);
        const end = new Date(this.selectedToDate);
        const dates = [];

        // Generate all dates between start and end
        const current = new Date(start);
        while (current <= end) {
            dates.push(current.toISOString().split('T')[0]);
            current.setDate(current.getDate() + 1);
        }

        return dates;
    },

    initCalendar() {
        this.generateDaysInMonth(this.currentMonth, this.currentYear);
        this.$watch('selectedFromDate', value => {
            @this.set('fromDate', value);
        });
        this.$watch('selectedToDate', value => {
            @this.set('toDate', value);
        });
    },

    generateDaysInMonth(month, year) {
        this.daysInMonth = [];
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        // Add empty slots for days from previous month
        for (let i = 0; i < firstDay; i++) {
            this.daysInMonth.push({ day: '', isCurrentMonth: false });
        }

        // Add days of current month
        for (let i = 1; i <= daysInMonth; i++) {
            const date = new Date(year, month, i);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const isToday = date.toDateString() === today.toDateString();
            const dateString = `${year}-${String(month + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;

            this.daysInMonth.push({
                day: i,
                isCurrentMonth: true,
                date: dateString,
                isToday: isToday,
                isPast: date < today
            });
        }
    },

    prevMonth() {
        if (this.currentMonth === 0) {
            this.currentMonth = 11;
            this.currentYear--;
        } else {
            this.currentMonth--;
        }
        this.generateDaysInMonth(this.currentMonth, this.currentYear);
    },

    nextMonth() {
        if (this.currentMonth === 11) {
            this.currentMonth = 0;
            this.currentYear++;
        } else {
            this.currentMonth++;
        }
        this.generateDaysInMonth(this.currentMonth, this.currentYear);
    },

    formatDate(date) {
        if (!date) return '';
        const d = new Date(date);
        return d.toLocaleDateString('en-US', { day: 'numeric', month: 'short', year: 'numeric' });
    },

    selectDate(date) {
        try {
            if (!date) return; // Guard clause for undefined date

            // Always use string comparison to avoid date object issues
            const compareDates = (date1, date2) => {
                const d1 = typeof date1 === 'string' ? date1 : '';
                const d2 = typeof date2 === 'string' ? date2 : '';
                return d1.localeCompare(d2);
            };

            if (this.selectionMode === 'start') {
                // Set start date
                this.selectedFromDate = date;

                // If end date is before or equal to start date, reset it to start date
                if (compareDates(this.selectedToDate, date) < 0 || !this.selectedToDate) {
                    this.selectedToDate = date;
                }

                // Switch to end date selection
                this.selectionMode = 'end';
            } else {
                // Don't allow end date before start date
                if (compareDates(date, this.selectedFromDate) >= 0) {
                    this.selectedToDate = date;
                    // Reset for next selection
                    this.selectionMode = 'start';
                } else {
                    // If trying to select an end date before start date,
                    // set it as the new start date and keep in end selection mode
                    this.selectedFromDate = date;
                    this.selectedToDate = date;
                }
            }

            // Ensure the from-to dates are always valid
            if (compareDates(this.selectedToDate, this.selectedFromDate) < 0) {
                const temp = this.selectedFromDate;
                this.selectedFromDate = this.selectedToDate;
                this.selectedToDate = temp;
            }

            // Regenerate calendar days to update visual state
            this.generateDaysInMonth(this.currentMonth, this.currentYear);
        } catch (error) {
            console.error('Error in date selection:', error);
        }
    },

    // Check if date is in the selected range
    isInRange(date) {
        try {
            if (!date || !this.selectedFromDate || !this.selectedToDate) return false;

            // Don't proceed if it's the exact start or end date
            if (date === this.selectedFromDate || date === this.selectedToDate) return false;

            // Parse dates safely, always using the date string part only
            let d, start, end;
            try {
                d = date.includes('T') ? date.split('T')[0] : date;
                start = this.selectedFromDate.includes('T') ? this.selectedFromDate.split('T')[0] : this.selectedFromDate;
                end = this.selectedToDate.includes('T') ? this.selectedToDate.split('T')[0] : this.selectedToDate;
            } catch (e) {
                return false; // If any parsing fails, we can't compare
            }

            // Direct string comparison for YYYY-MM-DD formatted dates
            return d > start && d < end;

        } catch (error) {
            console.error('Error checking date range:', error);
            return false;
        }
    },

    // Set selection mode
    setSelectionMode(mode) {
        this.selectionMode = mode;
    },

    showLeaveTypeDetails(typeId) {
        if (!typeId) {
            this.leaveTypeDetails = null;
            return;
        }

        const leaveType = {{ Illuminate\Support\Js::from($leaveTypes) }}.find(type => type.id == typeId);
        if (leaveType) {
            this.leaveTypeDetails = leaveType;
        }
    },

    // Helper function to get calendar day classes
    getCalendarDayClass(day) {
        try {
            if (!day) return '';
            if (!day.isCurrentMonth) return '';

            const classes = [];

            // Selected start date
            if (day.date === this.selectedFromDate) {
                classes.push('bg-gradient-to-br from-indigo-600 to-purple-700 text-white shadow-md ring-2 ring-indigo-100');
            }

            // Selected end date
            else if (day.date === this.selectedToDate) {
                classes.push('bg-gradient-to-br from-purple-500 to-indigo-600 text-white shadow-md');
            }

            // Date in range
            else if (this.isInRange(day.date)) {
                classes.push('bg-indigo-50 text-indigo-900 border border-indigo-100 hover:border-indigo-300');
            }

            // Today's date
            else if (day.isToday) {
                classes.push('bg-emerald-500 text-white font-bold');
            }

            // Past dates
            else if (day.isPast) {
                classes.push('text-gray-400 hover:text-gray-900');
            }

            // Hover effects for all interactive days
            if (!day.isPast || this.selectionMode === 'end') {
                classes.push('hover:bg-indigo-50 hover:shadow-sm');
            }

            // Border radius classes for range selection
            if (day.date === this.selectedFromDate && this.selectedFromDate !== this.selectedToDate) {
                classes.push('rounded-l-lg');
            }

            if (day.date === this.selectedToDate && this.selectedFromDate !== this.selectedToDate) {
                classes.push('rounded-r-lg');
            }

            // Single date selection or dates outside range
            if ((day.date === this.selectedFromDate && day.date === this.selectedToDate) ||
                (!this.isInRange(day.date) && day.date !== this.selectedFromDate && day.date !== this.selectedToDate)) {
                classes.push('rounded-lg');
            }

            // Hover animation for interactive dates
            if (!day.isPast || this.selectionMode === 'end') {
                classes.push('transform hover:scale-110 hover:z-10');
            }

            // Disabled state
            if (day.isPast && this.selectionMode === 'start') {
                classes.push('cursor-not-allowed opacity-60');
            }

            return classes.join(' ');
        } catch (error) {
            console.error('Error getting calendar day class:', error);
            return '';
        }
    }
}" x-init="initCalendar">

    <!-- Embedded Calendar, no modal needed -->

    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row items-center justify-between mb-8">
            <div class="flex items-center mb-4 md:mb-0">
                <div class="bg-indigo-100 p-3 rounded-full mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-indigo-900">Apply for Leave</h1>
                    <p class="text-gray-500">Request time off from work</p>
                </div>
            </div>
            <a href="{{ route('leaves') }}" class="group flex items-center text-indigo-600 hover:text-indigo-800 transform transition duration-200 ease-in-out hover:-translate-x-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 transform transition duration-200 ease-in-out group-hover:-translate-x-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z"
                        clip-rule="evenodd" />
                </svg>
                Back to Leave Applications
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="md:col-span-2 order-2 md:order-1">
                <!-- Apply for Leave Card -->
                <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100 hover:shadow-xl transition-all duration-300">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-800 mb-2 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Leave Application Form
                        </h2>
                        <p class="text-gray-500">Please complete the form below to submit your leave request</p>
                    </div>

                    @if (session()->has('error'))
                        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-md flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500 mr-3 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <h3 class="text-sm font-medium text-red-800">Attention Required</h3>
                                <p class="mt-1 text-sm text-red-700">{{ session('error') }}</p>
                            </div>
                        </div>
                    @endif

                    <form wire:submit.prevent="submit" class="space-y-6">
                        <!-- Enhanced Leave Type Selector with Visual Indicators -->
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-all duration-300">
                            <div class="p-4 border-b border-gray-100">
                                <label for="leaveTypeId" class="flex items-center text-sm font-semibold text-gray-800 mb-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                    </svg>
                                    Leave Type
                                </label>
                                <div x-data="{ open: false, selected: '' }" class="relative mt-1">
                                    <!-- Custom styled dropdown trigger -->
                                    <button @click="open = !open" type="button"
                                        class="flex items-center justify-between w-full bg-gray-50 border border-gray-200 px-4 py-3 rounded-lg text-left focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-base transition-all duration-200">
                                        <span x-text="$wire.leaveTypeId ? {{ Illuminate\Support\Js::from($leaveTypes) }}.find(type => type.id == $wire.leaveTypeId)?.name : 'Select Your Leave Type'"
                                            :class="$wire.leaveTypeId ? 'text-gray-900 font-medium' : 'text-gray-500'"></span>
                                        <div class="flex items-center">
                                            <!-- Show colored indicator based on selected leave type -->
                                            <div x-show="$wire.leaveTypeId" class="mr-2">
                                                <div x-data="{
                                                    get leaveColor() {
                                                        const type = {{ Illuminate\Support\Js::from($leaveTypes) }}.find(t => t.id == $wire.leaveTypeId)?.name.toLowerCase() || '';
                                                        if (type.includes('sick')) return 'bg-red-500';
                                                        if (type.includes('casual')) return 'bg-blue-500';
                                                        if (type.includes('vacation') || type.includes('annual')) return 'bg-emerald-500';
                                                        if (type.includes('unpaid') || type.includes('lop')) return 'bg-yellow-500';
                                                        if (type.includes('comp')) return 'bg-purple-500';
                                                        return 'bg-indigo-500';
                                                    }
                                                }" class="w-3 h-3 rounded-full" :class="leaveColor"></div>
                                            </div>
                                            <!-- Dropdown arrow with animation -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </button>

                                    <!-- Enhanced dropdown menu with visual indicators -->
                                    <div x-show="open" @click.outside="open = false" x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0"
                                        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-y-0"
                                        x-transition:leave-end="opacity-0 transform -translate-y-2"
                                        class="absolute mt-1 z-10 w-full bg-white rounded-lg shadow-lg border border-gray-200 py-1 max-h-60 overflow-auto">

                                        <div class="p-2 border-b border-gray-100">
                                            <div class="text-xs text-gray-500 font-medium">Select leave type</div>
                                        </div>

                                        <!-- Empty option -->
                                        <button @click="open = false; $wire.leaveTypeId = ''; $dispatch('input', ''); showLeaveTypeDetails('')"
                                            class="flex items-center px-4 py-2.5 w-full text-left hover:bg-gray-50 cursor-pointer">
                                            <div class="w-3 h-3 rounded-full border border-gray-300 mr-3"></div>
                                            <span class="text-gray-500">Select Your Leave Type</span>
                                        </button>

                                        <!-- Leave type options -->
                                        @foreach ($leaveTypes as $leaveType)
                                            <button wire:key="leave-type-{{ $leaveType->id }}"
                                                @click="open = false; $wire.leaveTypeId = '{{ $leaveType->id }}'; $dispatch('input', '{{ $leaveType->id }}'); showLeaveTypeDetails('{{ $leaveType->id }}')"
                                                class="flex items-center px-4 py-2.5 w-full text-left hover:bg-gray-50 cursor-pointer"
                                                :class="{ 'bg-indigo-50': $wire.leaveTypeId == '{{ $leaveType->id }}' }">
                                                <div class="flex-shrink-0">
                                                    @php
                                                        $colorClass = 'bg-indigo-500';
                                                        $name = strtolower($leaveType->name);
                                                        if (strpos($name, 'sick') !== false) {
                                                            $colorClass = 'bg-red-500';
                                                        } elseif (strpos($name, 'casual') !== false) {
                                                            $colorClass = 'bg-blue-500';
                                                        } elseif (strpos($name, 'vacation') !== false || strpos($name, 'annual') !== false) {
                                                            $colorClass = 'bg-emerald-500';
                                                        } elseif (strpos($name, 'unpaid') !== false || strpos($name, 'lop') !== false) {
                                                            $colorClass = 'bg-yellow-500';
                                                        } elseif (strpos($name, 'comp') !== false) {
                                                            $colorClass = 'bg-purple-500';
                                                        }
                                                    @endphp
                                                    <div class="w-3 h-3 rounded-full {{ $colorClass }} mr-3"></div>
                                                </div>
                                                <div class="flex-1">
                                                    <div class="text-gray-900">{{ $leaveType->name }}</div>
                                                    <div class="text-xs text-gray-500">
                                                        @if ($leaveType->is_limited)
                                                            Limited (subject to balance)
                                                        @else
                                                            Unlimited
                                                        @endif
                                                        @if ($leaveType->advance_notice_days > 0)
                                                            • {{ $leaveType->advance_notice_days }} days notice required
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="flex items-center" x-show="$wire.leaveTypeId == '{{ $leaveType->id }}'">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </div>
                                            </button>
                                        @endforeach
                                    </div>

                                    <!-- Hidden select for Livewire -->
                                    <select id="leaveTypeId" wire:model="leaveTypeId" @change="showLeaveTypeDetails($event.target.value)" class="hidden">
                                        <option value="">Select Your Leave Type</option>
                                        @foreach ($leaveTypes as $leaveType)
                                            <option value="{{ $leaveType->id }}">{{ $leaveType->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('leaveTypeId')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Leave type details with enhanced animation -->
                            <div x-show="leaveTypeDetails" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2"
                                x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2"
                                class="p-4 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-b-xl">

                                <div class="flex items-center text-indigo-700 font-medium mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span x-text="leaveTypeDetails ? leaveTypeDetails.name + ' Information' : ''"></span>
                                </div>

                                <!-- Enhanced styled info pills -->
                                <div class="mt-2 flex flex-wrap gap-2">
                                    <div x-show="leaveTypeDetails && leaveTypeDetails.is_limited"
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Limited (Subject to balance)
                                    </div>
                                    <div x-show="leaveTypeDetails && !leaveTypeDetails.is_limited"
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Unlimited
                                    </div>
                                    <div x-show="leaveTypeDetails && leaveTypeDetails.advance_notice_days > 0"
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span x-text="leaveTypeDetails.advance_notice_days + ' days notice required'"></span>
                                    </div>
                                    <div x-show="leaveTypeDetails && leaveTypeDetails.advance_notice_days === 0"
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        No advance notice needed
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="col-span-2 bg-white p-4 rounded-xl border border-gray-200 transition-all duration-300 hover:border-indigo-300 hover:shadow-lg">
                                <!-- Improved date selection header with interactive mode toggle -->
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center">
                                        <div class="bg-indigo-100 p-1.5 rounded-full mr-2.5">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <label class="font-medium text-gray-700">Date Selection</label>
                                    </div>

                                    <!-- Interactive selection mode toggle -->
                                    <div class="flex items-center space-x-1 bg-gray-50 rounded-full px-3 py-1 border border-gray-200">
                                        <button @click="selectionMode = 'start'" class="text-xs px-2 py-1 rounded-full transition-all duration-200"
                                            :class="selectionMode === 'start' ? 'bg-indigo-100 text-indigo-800 font-medium shadow-sm' : 'text-gray-500 hover:text-indigo-600'">
                                            Start
                                        </button>
                                        <div class="text-gray-300">|</div>
                                        <button @click="selectionMode = 'end'" class="text-xs px-2 py-1 rounded-full transition-all duration-200"
                                            :class="selectionMode === 'end' ? 'bg-indigo-100 text-indigo-800 font-medium shadow-sm' : 'text-gray-500 hover:text-indigo-600'">
                                            End
                                        </button>
                                    </div>
                                </div>

                                <!-- Compact date range display card -->
                                <div class="flex items-center p-3 mb-3 bg-gradient-to-r from-indigo-50 via-purple-50 to-indigo-50 rounded-lg border border-indigo-100 shadow-sm">
                                    <div class="bg-white bg-opacity-80 p-2 rounded-md shadow-sm mr-3 backdrop-blur-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>

                                    <div class="flex-1">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-1">
                                                <span class="font-medium text-gray-800" x-text="formatDate(selectedFromDate)"></span>
                                                <span x-show="selectedFromDate !== selectedToDate" class="text-indigo-400 px-1">→</span>
                                                <span class="font-medium text-gray-800" x-show="selectedFromDate !== selectedToDate" x-text="formatDate(selectedToDate)"></span>
                                            </div>

                                            <!-- Days count badge -->
                                            <div>
                                                <span x-show="selectedFromDate === selectedToDate" class="inline-flex items-center">
                                                    <span x-show="!$wire.isHalfDay"
                                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 shadow-sm">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                        1 day
                                                    </span>
                                                    <span x-show="$wire.isHalfDay"
                                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 shadow-sm">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                        </svg>
                                                        Half-day
                                                    </span>
                                                </span>
                                                <span x-show="selectedFromDate !== selectedToDate"
                                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 shadow-sm">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    <span x-text="Math.round((new Date(selectedToDate) - new Date(selectedFromDate)) / (1000 * 60 * 60 * 24)) + 1"></span> days
                                                </span>
                                            </div>
                                        </div>

                                        <div class="text-xs text-gray-600 mt-1">
                                            <span x-show="selectionMode === 'start'" class="text-indigo-700 font-medium">Select start date</span>
                                            <span x-show="selectionMode === 'end'" class="text-indigo-700 font-medium">Select end date</span>
                                            <span class="text-gray-500 ml-1">(Same day for half-day leave)</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Ultra Modern Calendar Design -->
                                <div class="bg-white rounded-xl border border-gray-200 p-3 shadow-md overflow-hidden">
                                    <!-- Calendar Header with Animated Month Navigation -->
                                    <div class="flex justify-between items-center mb-3 px-1">
                                        <!-- Previous Month Button -->
                                        <button @click="prevMonth" class="group p-1.5 hover:bg-indigo-50 rounded-full transition-all duration-200 text-gray-500 hover:text-indigo-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform duration-300 group-hover:-translate-x-0.5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                            </svg>
                                        </button>

                                        <!-- Month/Year Display with Animation -->
                                        <div class="relative px-4 py-1.5 rounded-full bg-gradient-to-r from-indigo-50 to-purple-50 border border-indigo-100">
                                            <h4 class="text-base font-bold bg-gradient-to-r from-indigo-700 via-purple-700 to-indigo-700 bg-clip-text text-transparent"
                                                x-text="new Date(currentYear, currentMonth).toLocaleDateString('en-US', {month: 'long', year: 'numeric'})">
                                            </h4>
                                        </div>

                                        <!-- Next Month Button -->
                                        <button @click="nextMonth" class="group p-1.5 hover:bg-indigo-50 rounded-full transition-all duration-200 text-gray-500 hover:text-indigo-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform duration-300 group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Calendar Days of Week Header with Modern Styling -->
                                    <div class="grid grid-cols-7 text-center mb-1">
                                        <div class="font-semibold text-xs text-indigo-700">S</div>
                                        <div class="font-semibold text-xs text-indigo-700">M</div>
                                        <div class="font-semibold text-xs text-indigo-700">T</div>
                                        <div class="font-semibold text-xs text-indigo-700">W</div>
                                        <div class="font-semibold text-xs text-indigo-700">T</div>
                                        <div class="font-semibold text-xs text-indigo-700">F</div>
                                        <div class="font-semibold text-xs text-indigo-700">S</div>
                                    </div>

                                    <!-- Enhanced Calendar Grid with Better Visual Cues -->
                                    <div class="grid grid-cols-7">
                                        <template x-for="day in daysInMonth" :key="day.date || Math.random()">
                                            <div class="aspect-square flex items-center justify-center p-0.5 relative">
                                                <button x-show="day.isCurrentMonth" x-bind:class="getCalendarDayClass(day)" x-bind:disabled="day.isPast && selectionMode === 'start'"
                                                    @click="day.isCurrentMonth && day.date ? selectDate(day.date) : null" type="button"
                                                    class="w-7 h-7 md:w-8 md:h-8 flex items-center justify-center text-xs md:text-sm font-medium transition-all duration-200 focus:outline-none focus:ring focus:ring-indigo-200">
                                                    <span x-text="day.day"></span>
                                                </button>

                                                <!-- Special indicator for half-day -->
                                                <div x-show="$wire.isHalfDay && day.date && day.date === selectedFromDate && day.date === selectedToDate"
                                                    class="absolute bottom-0.5 h-0.5 left-1 right-1 bg-purple-400 rounded-full">
                                                </div>
                                            </div>
                                        </template>
                                    </div>

                                    <!-- Interactive Legend with Visual Tooltips -->
                                    <div class="flex flex-wrap justify-center gap-4 mt-3 pt-2 border-t border-gray-100 text-xs">
                                        <div class="flex items-center group relative">
                                            <div class="w-3 h-3 rounded-full bg-emerald-500 mr-1 group-hover:scale-125 transition-transform duration-200"></div>
                                            <span class="text-gray-600 group-hover:text-emerald-700 transition-colors duration-200">Today</span>
                                        </div>
                                        <div class="flex items-center group relative">
                                            <div class="w-3 h-3 rounded-full bg-indigo-600 mr-1 group-hover:scale-125 transition-transform duration-200"></div>
                                            <span class="text-gray-600 group-hover:text-indigo-700 transition-colors duration-200">Selected</span>
                                        </div>
                                        <div class="flex items-center group relative">
                                            <div class="w-3 h-3 rounded-full bg-indigo-50 border border-indigo-200 mr-1 group-hover:scale-125 transition-transform duration-200"></div>
                                            <span class="text-gray-600 group-hover:text-indigo-700 transition-colors duration-200">In Range</span>
                                        </div>
                                    </div>
                                </div> <!-- Hidden inputs for form submission -->
                                <input type="hidden" name="fromDate" wire:model="fromDate" x-model="selectedFromDate" id="fromDate">
                                <input type="hidden" name="toDate" wire:model="toDate" x-model="selectedToDate" id="toDate">

                                @error('fromDate')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                @error('toDate')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror

                                <!-- Compact Half Day Options with Interactive UI -->
                                <div x-show="selectedFromDate === selectedToDate" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-2"
                                    x-transition:enter-end="opacity-100 transform translate-y-0" class="mt-3 overflow-hidden">

                                    <!-- Sleek Half-day toggle with compact UI -->
                                    <div class="bg-gradient-to-r from-indigo-50 via-purple-50 to-indigo-50 p-3 border border-indigo-100 rounded-xl shadow-sm relative overflow-hidden">
                                        <!-- Background decoration -->
                                        <div class="absolute right-0 top-0 -mt-4 -mr-4 opacity-10">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>

                                        <!-- Toggle and description -->
                                        <div class="flex justify-between items-center">
                                            <div class="flex items-center">
                                                <!-- Animated toggle switch -->
                                                <label for="isHalfDay" class="flex items-center cursor-pointer">
                                                    <div class="relative mr-3">
                                                        <input type="checkbox" id="isHalfDay" wire:model="isHalfDay" class="sr-only peer">
                                                        <div
                                                            class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-3 peer-focus:ring-indigo-200 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-indigo-600 peer-checked:to-purple-600 shadow-inner">
                                                        </div>
                                                    </div>
                                                    <div class="flex flex-col ml-1">
                                                        <span class="font-semibold text-gray-800">Half-day Leave</span>
                                                        <span class="text-xs text-gray-500">Morning or afternoon only</span>
                                                    </div>
                                                </label>
                                            </div>

                                            <!-- Day count badge -->
                                            <div x-show="$wire.isHalfDay" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90"
                                                x-transition:enter-end="opacity-100 scale-100" class="flex items-center bg-white px-3 py-1.5 rounded-full shadow-sm border border-indigo-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-indigo-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span class="text-xs font-medium bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">0.5 day</span>
                                            </div>
                                        </div>

                                        <!-- Half-day type tabs - more compact and visually appealing -->
                                        <div x-show="$wire.isHalfDay" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-2"
                                            x-transition:enter-end="opacity-100 transform translate-y-0" class="mt-3 bg-white rounded-lg border border-gray-200 p-2 shadow-sm">

                                            <!-- AM/PM tabs with visual time indicator -->
                                            <div class="grid grid-cols-2 gap-2">
                                                <label class="cursor-pointer">
                                                    <input type="radio" name="halfDayType" wire:model="halfDayType" value="morning" class="hidden peer">
                                                    <div
                                                        class="flex items-center p-2 rounded-lg transition-all duration-200 peer-checked:bg-gradient-to-r peer-checked:from-amber-50 peer-checked:to-amber-100 peer-checked:border-amber-200 peer-checked:shadow-sm border border-transparent hover:bg-gray-50">
                                                        <!-- Morning sun icon -->
                                                        <div class="flex-shrink-0 mr-3">
                                                            <div class="bg-gradient-to-r from-amber-100 to-amber-200 text-amber-600 p-2 rounded-full shadow-sm">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                                        d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <div class="font-medium">Morning</div>
                                                            <div class="flex items-center text-xs text-gray-500 mt-0.5">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                                8:00 AM - 12:00 PM
                                                            </div>
                                                        </div>
                                                        <div class="ml-auto" x-show="$wire.halfDayType === 'morning'">
                                                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </label>

                                                <label class="cursor-pointer">
                                                    <input type="radio" name="halfDayType" wire:model="halfDayType" value="afternoon" class="hidden peer">
                                                    <div
                                                        class="flex items-center p-2 rounded-lg transition-all duration-200 peer-checked:bg-gradient-to-r peer-checked:from-indigo-50 peer-checked:to-purple-100 peer-checked:border-indigo-200 peer-checked:shadow-sm border border-transparent hover:bg-gray-50">
                                                        <!-- Afternoon icon -->
                                                        <div class="flex-shrink-0 mr-3">
                                                            <div class="bg-gradient-to-r from-indigo-100 to-purple-200 text-indigo-600 p-2 rounded-full shadow-sm">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                                        d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <div class="font-medium">Afternoon</div>
                                                            <div class="flex items-center text-xs text-gray-500 mt-0.5">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                                1:00 PM - 5:00 PM
                                                            </div>
                                                        </div>
                                                        <div class="ml-auto" x-show="$wire.halfDayType === 'afternoon'">
                                                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>

                                            @error('halfDayType')
                                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                    </svg>
                                                    {{ $message }}
                                                </p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <!-- Enhanced Reason Field with Visual Appeal -->
                            <div
                                class="bg-white p-4 rounded-xl border border-gray-200 transition-all duration-300 focus-within:border-indigo-300 focus-within:ring-2 focus-within:ring-indigo-100 hover:shadow-md">
                                <div class="flex items-center mb-2">
                                    <div class="bg-indigo-100 p-1.5 rounded-full mr-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </div>
                                    <label for="reason" class="block text-sm font-medium text-gray-700">Reason for Leave</label>
                                </div>
                                <div class="relative">
                                    <!-- Styled text area with character count -->
                                    <div class="relative" x-data="{ count: 0 }" x-init="count = $refs.textarea.value.length || 0" x-effect="count = $refs.textarea.value.length || 0">
                                        <textarea id="reason" wire:model="reason" rows="3" x-ref="textarea" placeholder="Please provide details about your leave request..." @input="count = $event.target.value.length"
                                            class="appearance-none bg-gray-50 w-full p-3 rounded-lg focus:outline-none focus:ring-1 focus:ring-indigo-300 focus:border-indigo-300 border border-gray-200 text-base resize-none transition-all duration-200"></textarea>

                                        <!-- Character count -->
                                        <div class="absolute right-2 bottom-2">
                                            <span class="text-xs text-gray-400" :class="{ 'text-indigo-600': count > 0 }">
                                                <span x-text="count"></span>/500
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @error('reason')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Enhanced Submit Button with Visual Effects -->
                        <div class="pt-3">
                            <button type="submit"
                                class="group relative w-full overflow-hidden bg-gradient-to-r from-indigo-600 via-purple-600 to-indigo-600 hover:from-indigo-700 hover:via-purple-700 hover:to-indigo-700 text-white font-medium py-3.5 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 flex justify-center items-center">

                                <!-- Light effect animation -->
                                <span class="absolute right-0 w-12 h-44 -mt-12 transition-all duration-1000 transform translate-x-16 bg-white opacity-10 rotate-12 group-hover:-translate-x-96"></span>
                                <span class="absolute left-0 w-12 h-44 -mt-12 transition-all duration-1000 transform -translate-x-16 bg-white opacity-10 rotate-12 group-hover:translate-x-96"></span>

                                <!-- Pulsing dot -->
                                <span class="absolute right-4 top-1/2 transform -translate-y-1/2 flex h-3 w-3">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-60"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-white opacity-75"></span>
                                </span>

                                <!-- Icon and text -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Submit Leave Application
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="order-1 md:order-2">
                <!-- Leave Balance Card -->
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 border border-gray-100 hover:shadow-xl transition-all duration-300">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Your Leave Balance
                    </h2>
                    <div class="space-y-4">
                        @forelse($userLeaveBalances as $balance)
                            <div class="p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-all duration-200">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="font-medium text-gray-800">{{ $balance->leaveType->name }}</span>
                                    <span class="text-sm text-gray-500">{{ now()->year }}</span>
                                </div>
                                <div class="relative pt-1">
                                    <div class="overflow-hidden h-2 text-xs flex rounded-full bg-gray-200">
                                        @php
                                            $percent = $balance->total_allocated > 0 ? ($balance->remaining / $balance->total_allocated) * 100 : 0;
                                            $colorClass = $percent > 70 ? 'bg-green-500' : ($percent > 30 ? 'bg-yellow-500' : 'bg-red-500');
                                        @endphp
                                        <div style="width: {{ $percent }}%"
                                            class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center {{ $colorClass }} transition-all duration-500"></div>
                                    </div>
                                    <div class="flex justify-between items-center mt-2">
                                        <span class="text-indigo-700 text-lg font-bold">{{ $balance->remaining }}</span>
                                        <span class="text-gray-500 text-sm">of {{ $balance->total_allocated }} days remaining</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-4 rounded-lg border border-indigo-100 bg-indigo-50 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-indigo-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                <p class="text-indigo-800 font-medium">No leave balances found for the current year.</p>
                                <p class="text-indigo-600 text-sm mt-1">Contact HR for more information.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Leave Policy Card -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Leave Policy
                    </h2>
                    <div class="space-y-4">
                        <div class="flex items-start p-3 rounded-lg hover:bg-gray-50 transition-all duration-200">
                            <div class="bg-blue-100 p-2 rounded-full mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-800">Casual Leave</h3>
                                <p class="text-sm text-gray-600 mt-1">Requires 7 days notice before the leave date.</p>
                            </div>
                        </div>

                        <div class="flex items-start p-3 rounded-lg hover:bg-gray-50 transition-all duration-200">
                            <div class="bg-purple-100 p-2 rounded-full mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-800">Special Holiday</h3>
                                <p class="text-sm text-gray-600 mt-1">Requires 30 days notice before the leave date.</p>
                            </div>
                        </div>

                        <div class="flex items-start p-3 rounded-lg hover:bg-gray-50 transition-all duration-200">
                            <div class="bg-red-100 p-2 rounded-full mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-800">Sick Leave</h3>
                                <p class="text-sm text-gray-600 mt-1">No advance notice required. Medical certificate may be requested for leaves longer than 2 days.</p>
                            </div>
                        </div>

                        <div class="flex items-start p-3 rounded-lg hover:bg-gray-50 transition-all duration-200">
                            <div class="bg-yellow-100 p-2 rounded-full mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-800">Loss of Pay (LOP)</h3>
                                <p class="text-sm text-gray-600 mt-1">Applied when other leave balances are exhausted. No limit but affects monthly salary.</p>
                            </div>
                        </div>

                        <div class="flex items-start p-3 rounded-lg hover:bg-gray-50 transition-all duration-200">
                            <div class="bg-green-100 p-2 rounded-full mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-800">Compensation Leave</h3>
                                <p class="text-sm text-gray-600 mt-1">For overtime work. Must be approved by manager.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
