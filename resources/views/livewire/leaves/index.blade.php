<div>
    <x-slot name="header">Leave Management</x-slot>

    <!-- Tabs -->
    <div class="mb-6 border-b border-gray-200">
        <div class="flex">
            <button wire:click="changeTab('pending')" class="py-4 px-6 {{ $tab === 'pending' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-500 hover:text-gray-700' }} font-medium">
                Pending
            </button>
            <button wire:click="changeTab('approved')" class="py-4 px-6 {{ $tab === 'approved' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-500 hover:text-gray-700' }} font-medium">
                Approved
            </button>
            <button wire:click="changeTab('rejected')" class="py-4 px-6 {{ $tab === 'rejected' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-500 hover:text-gray-700' }} font-medium">
                Rejected
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Leave List -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                    <h2 class="font-semibold text-lg text-gray-800">
                        {{ ucfirst($tab) }} Leave Applications
                    </h2>
                    <a href="{{ route('leaves.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Apply for Leave
                    </a>
                </div>
                <div class="p-6">
                    @if ($leaves->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Leave Type</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Days</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($leaves as $leave)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $leave->leaveType->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $leave->from_date->format('M d, Y') }}</div>
                                                <div class="text-sm text-gray-500">to {{ $leave->to_date->format('M d, Y') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $leave->days_count }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if ($leave->status === 'pending')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Pending
                                                    </span>
                                                @elseif($leave->status === 'approved')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Approved
                                                    </span>
                                                @elseif($leave->status === 'rejected')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        Rejected
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('leaves.show', $leave) }}" class="text-blue-600 hover:text-blue-900">View</a>

                                                @if ($leave->status === 'pending')
                                                    <button type="button" class="ml-3 text-red-600 hover:text-red-900">Cancel</button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $leaves->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No {{ $tab }} leave applications</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                @if ($tab === 'pending')
                                    You don't have any pending leave applications.
                                @elseif($tab === 'approved')
                                    You don't have any approved leave applications.
                                @else
                                    You don't have any rejected leave applications.
                                @endif
                            </p>
                            <div class="mt-6">
                                <a href="{{ route('leaves.create') }}"
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                    </svg>
                                    Apply for Leave
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Leave Balance and Policy -->
        <div>
            <!-- Leave Balance -->
            <div class="bg-white shadow-sm sm:rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="font-semibold text-lg text-gray-800">Leave Balance</h2>
                </div>
                <div class="p-6">
                    @if ($leaveBalances->count() > 0)
                        <ul class="divide-y divide-gray-200">
                            @foreach ($leaveBalances as $balance)
                                <li class="py-3 flex justify-between">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-900">{{ $balance->leaveType->name }}</span>
                                        <span class="text-sm text-gray-500">{{ $balance->leaveType->is_limited ? 'Limited' : 'Unlimited' }}</span>
                                    </div>
                                    <div class="text-sm text-gray-900">
                                        <span class="font-medium">{{ $balance->total_allocated - $balance->used }}</span> / {{ $balance->total_allocated }}
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center py-4">
                            <p class="text-gray-500">No leave balances found for the current year.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Leave Policy -->
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="font-semibold text-lg text-gray-800">Leave Policy</h2>
                </div>
                <div class="p-6">
                    <ul class="divide-y divide-gray-200">
                        @foreach ($leaveTypes as $leaveType)
                            <li class="py-3">
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-gray-900">{{ $leaveType->name }}</span>
                                    <span class="text-sm text-gray-500">{{ $leaveType->description }}</span>
                                    <div class="mt-1 flex items-center text-xs text-gray-500">
                                        @if ($leaveType->is_limited)
                                            <span>{{ $leaveType->annual_quota }} days/year</span>
                                        @else
                                            <span>Unlimited</span>
                                        @endif

                                        <span class="mx-2">•</span>

                                        @if ($leaveType->advance_notice_days > 0)
                                            <span>{{ $leaveType->advance_notice_days }} days notice</span>
                                        @else
                                            <span>No advance notice required</span>
                                        @endif
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
