<div class="space-y-6">
    <!-- Year Selector -->
    <div class="flex items-center justify-between bg-white p-4 rounded-lg shadow-sm">
        <button wire:click="changeYear('prev')" class="p-2 rounded-full hover:bg-gray-100">
            <span class="material-icons-outlined">chevron_left</span>
        </button>
        <h2 class="text-xl font-semibold text-gray-800">{{ $year }} Leave Summary</h2>
        <button wire:click="changeYear('next')" class="p-2 rounded-full hover:bg-gray-100">
            <span class="material-icons-outlined">chevron_right</span>
        </button>
    </div>

    <!-- Leave Balances -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-4 border-b">
            <h3 class="text-lg font-medium text-gray-800">Leave Balances</h3>
            <p class="text-sm text-gray-500">Your leave entitlements for {{ $year }}</p>
        </div>
        <div class="p-4">
            @if ($leaveBalances->isEmpty())
                <div class="text-center py-4 text-gray-500">No leave balances found for {{ $year }}</div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($leaveBalances as $balance)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex justify-between items-center">
                                <h4 class="font-medium text-gray-800">{{ $balance->leaveType->name }}</h4>
                                <span class="text-sm text-gray-500">{{ $year }}</span>
                            </div>
                            <div class="mt-3 flex items-center justify-between">
                                <div>
                                    <div class="text-xs text-gray-500">Allocated</div>
                                    <div class="font-semibold">{{ $balance->allocated_days }} days</div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500">Used</div>
                                    <div class="font-semibold">{{ $balance->used_days }} days</div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500">Remaining</div>
                                    <div class="font-semibold">{{ $balance->remaining_days }} days</div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                    @php
                                        $percentUsed = $balance->allocated_days > 0 ? ($balance->used_days / $balance->allocated_days) * 100 : 0;
                                    @endphp
                                    <div class="h-full bg-indigo-500 rounded-full" style="width: {{ $percentUsed }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Leave History -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-4 border-b">
            <h3 class="text-lg font-medium text-gray-800">Leave History</h3>
            <p class="text-sm text-gray-500">Your leave applications for {{ $year }}</p>
        </div>
        <div class="p-4 overflow-x-auto">
            @if ($leaveHistory->isEmpty())
                <div class="text-center py-4 text-gray-500">No leave applications found for {{ $year }}</div>
            @else
                <table class="min-w-full responsive-table">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Leave Type</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">From</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">To</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Days</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Approved By</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($leaveHistory as $leave)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                    {{ $leave->leaveType->name }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                    {{ $leave->from_date->format('M d, Y') }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                    {{ $leave->to_date->format('M d, Y') }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                    {{ $leave->days_count }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm">
                                    @if ($leave->status == 'approved')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Approved
                                        </span>
                                    @elseif($leave->status == 'rejected')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Rejected
                                        </span>
                                    @elseif($leave->status == 'pending')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            {{ ucfirst($leave->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                    {{ $leave->approver->name ?? 'N/A' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
