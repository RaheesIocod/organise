<div>
    <div class="container mx-auto max-w-7xl px-3 sm:px-5 py-4 sm:py-6">

        <!-- Main Layout: Two-Column Grid -->
        <div class="grid grid-cols-12 gap-4 sm:gap-6 lg:gap-8">
            <div class="col-span-12 lg:col-span-9 order-2 lg:order-1">
                <!-- Apply for Leave Card -->
                <div class="bg-white rounded-xl shadow-lg p-5 sm:p-6 border border-gray-200 hover:shadow-xl transition-all duration-300">
                    <div class="mb-3 sm:mb-5">
                        <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-1 sm:mb-1.5 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 text-indigo-500 mr-1.5 sm:mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Leave Application Form
                        </h2>
                        <p class="text-xs sm:text-sm text-gray-500">Please complete the form below to submit your leave request</p>
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

                    <form wire:submit.prevent="submit" class="space-y-5">
                        <!-- Two Column Layout -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">

                            <!-- Left Column - Leave Type & Calendar -->
                            <div class="flex flex-col space-y-4">
                                <!-- 1. Leave Type Section -->
                                <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl border border-indigo-200 shadow-md hover:shadow-lg transition-all duration-300 p-4 sm:p-5">
                                    <div class="mb-3">
                                        <div class="flex items-center mb-3">
                                            <div class="bg-gradient-to-br from-indigo-200 to-purple-300 p-2 rounded-md mr-3 border border-indigo-300/50 shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-4.5 sm:w-4.5 text-indigo-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                                </svg>
                                            </div>
                                            <label for="leaveTypeId"
                                                class="text-sm sm:text-base font-bold text-indigo-800 bg-gradient-to-r from-indigo-800 to-purple-700 bg-clip-text text-transparent">
                                                Leave Type
                                            </label>
                                        </div>
                                        <div class="flex flex-col space-y-4">
                                            <div class="relative" wire:key="leave-type-dropdown">
                                                <!-- Custom styled dropdown trigger -->
                                                <button wire:click="$toggle('showLeaveTypeDropdown')" type="button"
                                                    class="flex items-center justify-between w-full bg-gradient-to-r from-white to-indigo-50 border-2 border-indigo-200 px-4 py-3 rounded-lg text-left focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-base transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-[1.02]">
                                                    <span class="{{ $leaveTypeId ? 'text-indigo-900 font-bold text-lg' : 'text-gray-500' }}">
                                                        @if ($leaveTypeId)
                                                            {{ $leaveTypes->firstWhere('id', $leaveTypeId)->name }}
                                                        @else
                                                            Select Your Leave Type
                                                        @endif
                                                    </span>
                                                    <div class="flex items-center">
                                                        <!-- Show colored indicator based on selected leave type -->
                                                        @if ($leaveTypeId)
                                                            <div class="mr-2.5">
                                                                @php
                                                                    $selectedType = $leaveTypes->firstWhere('id', $leaveTypeId);
                                                                    $colorClass = 'bg-indigo-500';
                                                                    if ($selectedType) {
                                                                        $name = strtolower($selectedType->name);
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
                                                                    }
                                                                @endphp
                                                                <div class="w-4 h-4 rounded-full shadow-inner border-2 border-white {{ $colorClass }}"></div>
                                                            </div>
                                                        @endif
                                                        <!-- Dropdown arrow with animation -->
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="h-5 w-5 text-indigo-600 transition-transform duration-200 {{ $showLeaveTypeDropdown ? 'rotate-180' : '' }}" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                        </svg>
                                                    </div>
                                                </button>

                                                <!-- Enhanced dropdown menu with visual indicators -->
                                                <div wire:key="dropdown-menu" @click.outside="$wire.set('showLeaveTypeDropdown', false)"
                                                    class="absolute mt-2 z-50 w-full bg-white rounded-xl shadow-xl border-2 border-indigo-100 py-2 max-h-72 overflow-auto backdrop-blur-sm
                                                    {{ $showLeaveTypeDropdown ? '' : 'hidden' }} transition-all duration-200">

                                                    <div class="p-3 border-b border-indigo-100 bg-gradient-to-r from-indigo-50 to-purple-50">
                                                        <div class="text-sm text-indigo-700 font-bold">Select Leave Type</div>
                                                    </div>

                                                    <!-- Empty option -->
                                                    <button wire:click="resetLeaveType"
                                                        class="flex items-center px-5 py-3 w-full text-left hover:bg-indigo-50 cursor-pointer transition-all duration-200">
                                                        <div class="w-4 h-4 rounded-full border-2 border-gray-300 mr-3 shadow-sm"></div>
                                                        <span class="text-gray-500 font-medium">Select Your Leave Type</span>
                                                    </button>

                                                    <!-- Leave type options -->
                                                    @foreach ($leaveTypes as $leaveType)
                                                        <button wire:key="leave-type-{{ $leaveType->id }}" wire:click="selectLeaveType({{ $leaveType->id }})"
                                                            class="flex items-center px-5 py-3 w-full text-left hover:bg-indigo-50 cursor-pointer transition-all duration-200 transform hover:scale-[1.01] {{ $leaveTypeId == $leaveType->id ? 'bg-gradient-to-r from-indigo-50 to-purple-50 shadow-sm' : '' }}">
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
                                                                <div class="w-4 h-4 rounded-full {{ $colorClass }} mr-3 shadow-sm border-2 border-white"></div>
                                                            </div>
                                                            <div class="flex-1">
                                                                <div class="text-gray-900 font-medium">{{ $leaveType->name }}</div>
                                                                <div class="text-xs text-gray-500 mt-0.5">
                                                                    @if ($leaveType->is_limited)
                                                                        <span class="inline-flex items-center bg-blue-50 text-blue-700 px-1.5 py-0.5 rounded-full text-[10px] font-medium">
                                                                            <svg class="w-2 h-2 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                                                            </svg>
                                                                            Limited
                                                                        </span>
                                                                    @else
                                                                        <span class="inline-flex items-center bg-green-50 text-green-700 px-1.5 py-0.5 rounded-full text-[10px] font-medium">
                                                                            <svg class="w-2 h-2 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                            </svg>
                                                                            Unlimited
                                                                        </span>
                                                                    @endif
                                                                    @if ($leaveType->advance_notice_days > 0)
                                                                        <span class="inline-flex items-center bg-indigo-50 text-indigo-700 px-1.5 py-0.5 rounded-full text-[10px] font-medium ml-1">
                                                                            <svg class="w-2 h-2 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">
                                                                                </path>
                                                                            </svg>
                                                                            {{ $leaveType->advance_notice_days }}d notice
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="flex items-center" x-show="$wire.leaveTypeId == '{{ $leaveType->id }}'">
                                                                <div class="bg-indigo-100 p-1 rounded-full">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24"
                                                                        stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                                    </svg>
                                                                </div>
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
                                                <p class="mt-2 text-sm text-red-600 bg-red-50 px-3 py-1.5 rounded-lg border border-red-100 flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 flex-shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                    </svg>
                                                    {{ $message }}
                                                </p>
                                            @enderror
                                        </div>

                                        <!-- Leave type details with enhanced animation -->
                                        <div class="p-4 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-b-xl {{ $leaveTypeDetails ? '' : 'hidden' }}">

                                            <div class="flex items-center text-indigo-700 font-medium mb-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span>{{ $leaveTypeDetails ? $leaveTypeDetails->name . ' Information' : '' }}</span>
                                            </div>

                                            <!-- Enhanced styled info pills -->
                                            <div class="mt-2 flex flex-wrap gap-2">
                                                @if ($leaveTypeDetails && $leaveTypeDetails->is_limited)
                                                    <div class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 shadow-sm">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        Limited (Subject to balance)
                                                    </div>
                                                @endif
                                                @if ($leaveTypeDetails && !$leaveTypeDetails->is_limited)
                                                    <div class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 shadow-sm">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                        Unlimited
                                                    </div>
                                                @endif
                                                @if ($leaveTypeDetails && $leaveTypeDetails->advance_notice_days > 0)
                                                    <div class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 shadow-sm">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        {{ $leaveTypeDetails->advance_notice_days }} days notice required
                                                    </div>
                                                @endif
                                                @if ($leaveTypeDetails && $leaveTypeDetails->advance_notice_days === 0)
                                                    <div class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 shadow-sm">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                        No advance notice needed
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        @error('leaveTypeId')
                                            <p class="mt-1 text-xs text-red-600 flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                    <!-- Calendar -->
                                    <div class="bg-white rounded-xl border-2 border-indigo-100 shadow-lg hover:shadow-xl transition-all duration-300 p-3 sm:p-5 mt-2 sm:mt-3">
                                        <div class="mb-2 sm:mb-4 flex justify-between items-center">
                                            <div class="flex items-center">
                                                <div class="bg-gradient-to-br from-indigo-300 to-purple-400 p-1.5 sm:p-2 rounded-md mr-2 sm:mr-2.5 shadow-sm">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 sm:h-4 sm:w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                                <span class="text-sm sm:text-base font-bold text-indigo-800 bg-gradient-to-r from-indigo-800 to-purple-700 bg-clip-text text-transparent">Date
                                                    Selection</span>
                                            </div>

                                            <!-- Selection mode toggle -->
                                            <div class="inline-flex rounded-md shadow-md" role="group">
                                                <button wire:click="setSelectionMode('start')" type="button"
                                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-l-lg focus:outline-none focus:ring-1 focus:ring-indigo-400 transition-all duration-200 {{ $selectionMode === 'start' ? 'bg-gradient-to-r from-indigo-600 to-indigo-700 text-white border border-indigo-500' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                                    </svg>
                                                    From
                                                </button>
                                                <button wire:click="setSelectionMode('end')" type="button"
                                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-r-lg focus:outline-none focus:ring-1 focus:ring-indigo-400 transition-all duration-200 {{ $selectionMode === 'end' ? 'bg-gradient-to-r from-indigo-600 to-indigo-700 text-white border border-indigo-500' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                                    </svg>
                                                    To
                                                </button>
                                            </div>
                                        </div>

                                        <div class="flex justify-between items-center mb-1 bg-gray-50 rounded-md px-1 py-0.5 border border-gray-100">
                                            <!-- Previous Month Button -->
                                            <button wire:click="prevMonth" class="p-0.5 hover:bg-indigo-50 rounded text-gray-500 hover:text-indigo-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                                </svg>
                                            </button>

                                            <!-- Month/Year Display -->
                                            <div class="px-2 rounded-sm bg-gradient-to-r from-indigo-50 to-purple-50">
                                                <h4 class="text-xs font-bold text-indigo-700">
                                                    {{ \Carbon\Carbon::createFromDate($currentYear, $currentMonth + 1)->format('M Y') }}
                                                </h4>
                                            </div>

                                            <!-- Next Month Button -->
                                            <button wire:click="nextMonth" class="p-0.5 hover:bg-indigo-50 rounded text-gray-500 hover:text-indigo-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </button>
                                        </div>

                                        <!-- Calendar Days of Week Header -->
                                        <div class="grid grid-cols-7 gap-0 text-center bg-indigo-50 rounded-t-md mb-1 py-1">
                                            <div class="font-medium text-[10px] text-indigo-800 w-6 h-6 mx-auto flex items-center justify-center">S</div>
                                            <div class="font-medium text-[10px] text-indigo-800 w-6 h-6 mx-auto flex items-center justify-center">M</div>
                                            <div class="font-medium text-[10px] text-indigo-800 w-6 h-6 mx-auto flex items-center justify-center">T</div>
                                            <div class="font-medium text-[10px] text-indigo-800 w-6 h-6 mx-auto flex items-center justify-center">W</div>
                                            <div class="font-medium text-[10px] text-indigo-800 w-6 h-6 mx-auto flex items-center justify-center">T</div>
                                            <div class="font-medium text-[10px] text-indigo-800 w-6 h-6 mx-auto flex items-center justify-center">F</div>
                                            <div class="font-medium text-[10px] text-indigo-800 w-6 h-6 mx-auto flex items-center justify-center">S</div>
                                        </div>

                                        <!-- Ultra-Compact Calendar Grid -->
                                        <div class="grid grid-cols-7 gap-[1px] text-center">
                                            @foreach ($daysInMonth as $day)
                                                <div class="flex items-center justify-center p-0 relative">
                                                    @if ($day['isCurrentMonth'])
                                                        <button
                                                            class="w-6 h-6 flex items-center justify-center text-[10px] font-medium transition-all duration-200 focus:outline-none focus:ring-1 focus:ring-indigo-200 mx-auto {{ $this->getCalendarDayClass($day) }}"
                                                            wire:click="selectDate('{{ $day['date'] }}')" {{ $day['isPast'] && $selectionMode === 'start' ? 'disabled' : '' }} type="button">
                                                            {{ $day['day'] }}
                                                        </button>

                                                        <!-- Special indicator for partial day -->
                                                        @if ($isHalfDay && $day['date'] && $day['date'] === $fromDate && $day['date'] === $toDate)
                                                            <div class="absolute bottom-0 h-0.5 left-0.5 right-0.5 bg-purple-400 rounded-full">
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>

                                        <!-- Ultra-Compact Legend -->
                                        <div class="flex flex-wrap justify-center gap-1.5 mt-1 pt-1 border-t border-gray-100">
                                            <div class="flex items-center">
                                                <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-0.5"></div>
                                                <span class="text-[8px] text-gray-600">Today</span>
                                            </div>
                                            <div class="flex items-center">
                                                <div class="w-1.5 h-1.5 rounded-full bg-indigo-600 mr-0.5"></div>
                                                <span class="text-[8px] text-gray-600">Selected</span>
                                            </div>
                                            <div class="flex items-center">
                                                <div class="w-1.5 h-1.5 rounded-full bg-indigo-50 border border-indigo-200 mr-0.5"></div>
                                                <span class="text-[8px] text-gray-600">In Range</span>
                                            </div>
                                        </div>

                                        <!-- Leave Duration Summary -->
                                        <div class="mt-3 pt-2 border-t border-gray-100">
                                            <p class="text-purple-700 text-sm flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <span class="font-medium">
                                                    {{ $this->daysCount }} {{ $this->daysCount == 1 ? 'day' : 'days' }}
                                                </span>
                                            </p>
                                        </div>

                                        <input type="hidden" name="fromDate" wire:model="fromDate" id="fromDate">
                                        <input type="hidden" name="toDate" wire:model="toDate" id="toDate">

                                        @error('fromDate')
                                            <p class="mt-2 text-xs text-red-600 text-center">{{ $message }}</p>
                                        @enderror
                                        @error('toDate')
                                            <p class="mt-2 text-xs text-red-600 text-center">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <!-- 2. Start Time + End Time -->
                            <div
                                class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl border border-gray-700 shadow-xl hover:shadow-2xl transition-all duration-300 p-4 sm:p-5 transform hover:scale-[1.01]">
                                <div class="mb-4 flex flex-wrap sm:flex-nowrap justify-between items-center gap-3">
                                    <div class="flex items-center">
                                        <div class="bg-gradient-to-br from-indigo-500 to-purple-600 p-2 rounded-md mr-3 shadow-md">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <span class="text-sm sm:text-base font-bold text-white">Time Selection</span>
                                    </div>

                                    <!-- Date range display -->
                                    <div class="w-full sm:w-auto flex items-center justify-center text-xs bg-gray-700/70 px-3 py-1.5 rounded-lg shadow-inner border border-gray-600/80">
                                        <span class="font-medium text-indigo-300 truncate max-w-[80px] sm:max-w-none">
                                            {{ $fromDate ? $this->formatDate($fromDate) : 'Start Date' }}
                                        </span>
                                        <span class="text-purple-400 px-2 font-bold">→</span>
                                        <span class="font-medium text-indigo-300 truncate max-w-[80px] sm:max-w-none">
                                            {{ $toDate ? $this->formatDate($toDate) : 'End Date' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Ultra-Compact Day Parts Selection UI -->
                                <div class="grid grid-cols-1 md:grid-cols-1 gap-2 sm:gap-3">
                                    <!-- Start Date Time Part Selection -->
                                    <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-lg text-white p-4 shadow-sm border border-gray-700">
                                        <div class="mb-2 flex justify-between items-center">
                                            <span class="text-xs text-gray-200 font-medium flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1.5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Start Time
                                            </span>
                                            <span class="text-xs text-indigo-300 bg-gray-800 px-2 py-0.5 rounded-md border border-gray-700/80">
                                                {{ $fromDate ? $this->formatDate($fromDate) : '' }}
                                            </span>
                                        </div>

                                        <div class="grid grid-cols-2 gap-2 mb-2">
                                            <!-- Morning Option -->
                                            <label class="cursor-pointer flex items-center justify-center">
                                                <input type="radio" name="startTimePart" wire:model="startTimePart" value="morning" class="sr-only peer">
                                                <div
                                                    class="w-full py-1.5 px-3 rounded-md flex items-center justify-center text-center border border-gray-700 peer-checked:bg-indigo-600 peer-checked:border-indigo-500 hover:bg-gray-800 transition duration-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                                    </svg>
                                                    <span class="text-xs">Morning</span>
                                                </div>
                                            </label>

                                            <!-- Afternoon Option -->
                                            <label class="cursor-pointer flex items-center justify-center">
                                                <input type="radio" name="startTimePart" wire:model="startTimePart" value="afternoon" class="sr-only peer">
                                                <div
                                                    class="w-full py-1.5 px-3 rounded-md flex items-center justify-center text-center border border-gray-700 peer-checked:bg-indigo-600 peer-checked:border-indigo-500 hover:bg-gray-800 transition duration-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                    </svg>
                                                    <span class="text-xs">Afternoon</span>
                                                </div>
                                            </label>
                                        </div>

                                        <div class="text-xs text-indigo-200 flex items-center bg-gray-800/60 p-1.5 rounded-md mt-1 border border-gray-700/50">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1 flex-shrink-0 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span>Select <span class="font-medium text-indigo-300">Afternoon</span> to start leave after lunch</span>
                                        </div>

                                        @error('startTimePart')
                                            <p class="mt-1 text-[9px] text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- End Date Time Part Selection -->
                                    <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-lg text-white p-4 shadow-sm border border-gray-700">
                                        <div class="mb-2 flex justify-between items-center">
                                            <span class="text-xs text-gray-200 font-medium flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1.5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                                </svg>
                                                End Time
                                            </span>
                                            <span
                                                class="text-xs text-indigo-300 bg-gray-800 px-2 py-0.5 rounded-md border border-gray-700/80">{{ $toDate ? $this->formatDateForDisplay($toDate) : '' }}</span>
                                        </div>

                                        <div class="grid grid-cols-2 gap-2 mb-2">
                                            <!-- Morning Option -->
                                            <label class="cursor-pointer flex items-center justify-center">
                                                <input type="radio" name="endTimePart" wire:model="endTimePart" value="morning" class="sr-only peer">
                                                <div
                                                    class="w-full py-1.5 px-3 rounded-md flex items-center justify-center text-center border border-gray-700 peer-checked:bg-indigo-600 peer-checked:border-indigo-500 hover:bg-gray-800 transition duration-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                                    </svg>
                                                    <span class="text-xs">Morning</span>
                                                </div>
                                            </label>

                                            <!-- End of Day Option -->
                                            <label class="cursor-pointer flex items-center justify-center">
                                                <input type="radio" name="endTimePart" wire:model="endTimePart" value="end_of_day" class="sr-only peer">
                                                <div
                                                    class="w-full py-1.5 px-3 rounded-md flex items-center justify-center text-center border border-gray-700 peer-checked:bg-indigo-600 peer-checked:border-indigo-500 hover:bg-gray-800 transition duration-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                                    </svg>
                                                    <span class="text-xs">End of day</span>
                                                </div>
                                            </label>
                                        </div>

                                        <div class="text-xs text-indigo-200 flex items-center bg-gray-800/60 p-1.5 rounded-md mt-1 border border-gray-700/50">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1 flex-shrink-0 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span>Select <span class="font-medium text-indigo-300">Morning</span> to end leave before lunch</span>
                                        </div>

                                        @error('endTimePart')
                                            <p class="mt-1 text-[9px] text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Leave Days Counter with dynamic calculation -->
                                <div class="mt-5 bg-gradient-to-r from-indigo-900 to-purple-900 rounded-lg p-3 border border-indigo-800 shadow-inner">
                                    <div class="flex items-center justify-center">
                                        <div class="bg-white/10 rounded-l-lg px-3 py-2 border-r border-indigo-700/50">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div class="px-4 py-1.5">
                                            <p class="text-purple-200 text-sm">
                                                Leave duration: <span class="font-bold text-white text-lg">{{ $this->daysCount }}</span>
                                                <span>{{ $this->daysCount == 1 ? 'day' : 'days' }}</span>
                                                @if ($leaveTypeId)
                                                    <span class="ml-1 text-xs"> from
                                                        <span class="text-white font-medium whitespace-nowrap bg-white/10 px-2 py-0.5 rounded-full ml-0.5">
                                                            {{ $leaveTypes->firstWhere('id', $leaveTypeId)->name }}
                                                        </span>
                                                    </span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 3. Reason for Leave Section -->
                            <div
                                class="bg-gradient-to-br from-white to-indigo-50 rounded-xl border border-indigo-200 shadow-md hover:shadow-lg transition-all duration-300 p-4 sm:p-5 transform hover:scale-[1.01]">
                                <div class="mb-3 flex items-center justify-between flex-wrap sm:flex-nowrap">
                                    <div class="flex items-center">
                                        <div class="bg-gradient-to-br from-indigo-200 to-purple-300 p-2 rounded-md mr-3 border border-indigo-300/50 shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <label for="reason" class="text-sm sm:text-base font-medium text-gray-800 flex items-center flex-wrap">
                                            Reason for Leave <span class="text-red-500 ml-0.5">*</span>
                                        </label>
                                    </div>
                                    <span class="mt-1 sm:mt-0 text-xs text-gray-500 bg-white px-2 py-0.5 rounded-full shadow-sm border border-gray-100">(Required for approval)</span>
                                </div>
                                <div class="relative">
                                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-indigo-500 to-purple-500 rounded-l"></div>
                                    <textarea id="reason" wire:model="reason" rows="3" class="block w-full bg-white border-gray-300 rounded-lg pl-3 pr-14 py-2 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                        placeholder="Please provide a specific reason for your leave request..."></textarea>

                                    <div class="absolute right-2 bottom-2 text-xs bg-gray-50 px-2 py-0.5 rounded-md border border-gray-200 text-gray-500 shadow-sm">
                                        {{ strlen($reason ?? '') }}/500
                                    </div>

                                    @error('reason')
                                        <p class="mt-1 text-xs text-red-600 flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Enhanced Submit Button Section -->
                            <div class="mt-6 sm:mt-8 flex justify-center">
                                <div wire:key="submit-button" class="relative w-full sm:w-4/5 lg:w-full mx-auto">
                                    <button type="submit" wire:loading.attr="disabled"
                                        class="group w-full px-5 sm:px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-medium text-base sm:text-lg rounded-xl shadow-xl hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300 transform hover:scale-[1.03] {{ !$fromDate || !$toDate || !$startTimePart || !$endTimePart || !$leaveTypeId || !$reason ? 'opacity-50 cursor-not-allowed' : '' }}"
                                        {{ !$fromDate || !$toDate || !$startTimePart || !$endTimePart || !$leaveTypeId || !$reason ? 'disabled' : '' }}
                                        wire:mouseenter="$set('showHint', {{ !$fromDate || !$toDate || !$startTimePart || !$endTimePart || !$leaveTypeId || !$reason ? 'true' : 'false' }})"
                                        wire:mouseleave="$set('showHint', false)">
                                        <div class="flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Submit Leave Application
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                            </svg>
                                        </div>
                                    </button>

                                    <!-- Missing Fields Tooltip -->
                                    <div wire:key="hint-tooltip"
                                        class="absolute bottom-full mb-3 w-64 right-0 bg-white rounded-lg shadow-xl border border-gray-200 text-xs p-3 z-10 transition-all duration-200
                                        {{ $showHint ? 'opacity-100 transform translate-y-0' : 'opacity-0 transform -translate-y-2 pointer-events-none' }}"
                                        wire:mouseleave="$set('showHint', false)">
                                        <h4 class="font-semibold text-gray-800 mb-2 text-center border-b border-gray-100 pb-2">Complete Required Fields</h4>
                                        <ul class="space-y-2 text-gray-600">
                                            <li class="flex items-center">
                                                <span class="w-5 h-5 flex items-center justify-center rounded-full {{ $leaveTypeId ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-500' }}">
                                                    {{ $leaveTypeId ? '✓' : '×' }}
                                                </span>
                                                <span class="ml-2">Leave type</span>
                                            </li>
                                            <li class="flex items-center">
                                                <span class="w-5 h-5 flex items-center justify-center rounded-full {{ $fromDate ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-500' }}">
                                                    {{ $fromDate ? '✓' : '×' }}
                                                </span>
                                                <span class="ml-2">Start date</span>
                                            </li>
                                            <li class="flex items-center">
                                                <span class="w-5 h-5 flex items-center justify-center rounded-full {{ $toDate ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-500' }}">
                                                    {{ $toDate ? '✓' : '×' }}
                                                </span>
                                                <span class="ml-2">End date</span>
                                            </li>
                                            <li class="flex items-center">
                                                <span class="w-5 h-5 flex items-center justify-center rounded-full {{ $startTimePart ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-500' }}">
                                                    {{ $startTimePart ? '✓' : '×' }}
                                                </span>
                                                <span class="ml-2">Start time part</span>
                                            </li>
                                            <li class="flex items-center">
                                                <span class="w-5 h-5 flex items-center justify-center rounded-full {{ $endTimePart ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-500' }}">
                                                    {{ $endTimePart ? '✓' : '×' }}
                                                </span>
                                                <span class="ml-2">End time part</span>
                                            </li>
                                            <li class="flex items-center">
                                                <span class="w-5 h-5 flex items-center justify-center rounded-full {{ $reason ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-500' }}">
                                                    {{ $reason ? '✓' : '×' }}
                                                </span>
                                                <span class="ml-2">Reason</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Enhanced Right Sidebar -->
            <div class="col-span-12 lg:col-span-3 order-1 lg:order-2">
                <!-- Leave Information Card -->
                <div
                    class="bg-white rounded-xl shadow-lg p-4 sm:p-5 border border-gray-200 border-l-4 border-l-indigo-500 lg:sticky lg:top-4 bg-gradient-to-br from-white via-white to-indigo-50/70 mb-4 sm:mb-6">
                    <div class="mb-3 flex items-center border-b border-indigo-100 pb-2 relative">
                        <!-- Left accent border is already handled by border-l classes -->
                        <div class="bg-gradient-to-br from-indigo-600 to-purple-700 p-1.5 sm:p-2 rounded-lg mr-2 shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-semibold bg-gradient-to-r from-indigo-800 to-purple-700 bg-clip-text text-transparent">Leave Information</h3>
                    </div>

                    <div class="space-y-3">
                        <!-- Enhanced Current Leave Balances with Visual Progress -->
                        <div class="bg-gradient-to-r from-indigo-50 to-purple-50/70 rounded-xl p-3 border border-indigo-100 shadow-md">
                            <h4 class="font-medium text-indigo-900 text-sm mb-2 flex items-center">
                                <div class="bg-indigo-100 p-1 rounded mr-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                Your Leave Balances
                                <span class="ml-auto text-[10px] text-indigo-600 bg-white py-0.5 px-1.5 rounded border border-indigo-100">{{ now()->year }}</span>
                            </h4>
                            <div class="bg-white/80 rounded-lg p-2 shadow-sm border border-indigo-50">
                                @forelse($userLeaveBalances as $balance)
                                    @php
                                        $colorClass = 'bg-indigo-500';
                                        $gradientClass = 'from-indigo-500 to-indigo-600';
                                        $textColorClass = 'text-indigo-800';
                                        $bgColorClass = 'bg-indigo-50';
                                        $name = strtolower($balance->leaveType->name);

                                        if (strpos($name, 'sick') !== false) {
                                            $colorClass = 'bg-red-500';
                                            $gradientClass = 'from-red-500 to-red-600';
                                            $textColorClass = 'text-red-800';
                                            $bgColorClass = 'bg-red-50';
                                        } elseif (strpos($name, 'casual') !== false) {
                                            $colorClass = 'bg-blue-500';
                                            $gradientClass = 'from-blue-500 to-blue-600';
                                            $textColorClass = 'text-blue-800';
                                            $bgColorClass = 'bg-blue-50';
                                        } elseif (strpos($name, 'vacation') !== false || strpos($name, 'annual') !== false) {
                                            $colorClass = 'bg-emerald-500';
                                            $gradientClass = 'from-emerald-500 to-emerald-600';
                                            $textColorClass = 'text-emerald-800';
                                            $bgColorClass = 'bg-emerald-50';
                                        } elseif (strpos($name, 'unpaid') !== false || strpos($name, 'lop') !== false) {
                                            $colorClass = 'bg-yellow-500';
                                            $gradientClass = 'from-yellow-500 to-yellow-600';
                                            $textColorClass = 'text-yellow-800';
                                            $bgColorClass = 'bg-yellow-50';
                                        } elseif (strpos($name, 'comp') !== false) {
                                            $colorClass = 'bg-purple-500';
                                            $gradientClass = 'from-purple-500 to-purple-600';
                                            $textColorClass = 'text-purple-800';
                                            $bgColorClass = 'bg-purple-50';
                                        }

                                        // Calculate percentage remaining
                                        $totalDays = $balance->total_allocated;
                                        $usedDays = $balance->used;
                                        $remainingDays = $balance->remaining_days;
                                        $percentRemaining = $totalDays > 0 ? ($remainingDays / $totalDays) * 100 : 0;
                                    @endphp

                                    <div class="mb-2.5 last:mb-0">
                                        <div class="flex justify-between items-center text-xs mb-0.5">
                                            <span class="flex items-center font-medium">
                                                <span class="w-2 h-2 rounded-full {{ $colorClass }} mr-1.5"></span>
                                                {{ $balance->leaveType->name }}
                                            </span>
                                            <span class="font-bold {{ $textColorClass }} {{ $bgColorClass }} px-2 py-0.5 rounded-full text-[10px]">
                                                {{ $balance->remaining_days }}<span class="text-[9px] font-normal"> of </span>{{ $balance->total_allocated }}
                                            </span>
                                        </div>

                                        <!-- Progress bar -->
                                        <div class="w-full bg-gray-200 rounded-full h-1.5 overflow-hidden">
                                            <div class="h-full bg-gradient-to-r {{ $gradientClass }} rounded-full transition-all duration-500" style="width: {{ $percentRemaining }}%"></div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="flex items-center justify-center py-2 text-xs text-gray-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        No leave balances available.
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Leave Types Explanation -->
                        <div class="bg-gray-50 rounded-xl p-3 border border-gray-200 shadow-sm">
                            <h4 class="font-medium text-gray-800 text-sm mb-2 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                                </svg>
                                Leave Types
                            </h4>
                            <ul class="space-y-1 text-xs bg-white rounded-lg p-2 shadow-sm border border-gray-50">
                                <li class="flex items-center">
                                    <div class="w-2 h-2 rounded-full bg-blue-500 mr-1.5"></div>
                                    <span>Casual Leave - Short-term personal needs</span>
                                </li>
                                <li class="flex items-center">
                                    <div class="w-2 h-2 rounded-full bg-red-500 mr-1.5"></div>
                                    <span>Sick Leave - Health related absences</span>
                                </li>
                                <li class="flex items-center">
                                    <div class="w-2 h-2 rounded-full bg-emerald-500 mr-1.5"></div>
                                    <span>Annual Leave - Planned vacations</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Quick Tips -->
                        <div class="rounded-xl p-3 bg-gradient-to-r from-purple-50 to-indigo-50 border border-purple-100 shadow-sm">
                            <h4 class="font-medium text-purple-800 text-sm mb-2 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Pro Tips
                            </h4>
                            <ul class="space-y-1.5 text-xs text-gray-700 bg-white/80 rounded-lg p-2 shadow-sm border border-purple-50">
                                <li class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-purple-500 mr-1 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Apply at least 3 days before for planned leaves</span>
                                </li>
                                <li class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-purple-500 mr-1 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Select half-day options for shorter leaves</span>
                                </li>
                                <li class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-purple-500 mr-1 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Provide detailed reason for faster approval</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
