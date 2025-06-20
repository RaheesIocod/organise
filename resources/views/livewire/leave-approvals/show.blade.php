<div>
    <x-slot name="header">Leave Approval Details</x-slot>

    <div class="mb-6">
        <a href="{{ route('manager.leave-approvals') }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Leave Approvals
        </a>
    </div>

    <!-- Session Messages -->
    @if (session()->has('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Leave Application Details -->
        <div class="md:col-span-2">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Leave Application Details</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Employee</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $leaveApplication->user->name }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Designation</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $leaveApplication->user->designation->name }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Leave Type</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $leaveApplication->leaveType->name }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Status</h3>
                            <p class="mt-1">
                                @if ($leaveApplication->status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                @elseif($leaveApplication->status === 'approved')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Approved
                                    </span>
                                @elseif($leaveApplication->status === 'rejected')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Rejected
                                    </span>
                                @endif
                            </p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500">From Date</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $leaveApplication->from_date->format('M d, Y') }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500">To Date</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $leaveApplication->to_date->format('M d, Y') }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Days Count</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $leaveApplication->days_count }} day(s)</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Applied On</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $leaveApplication->created_at->format('M d, Y') }}</p>
                        </div>

                        <div class="md:col-span-2">
                            <h3 class="text-sm font-medium text-gray-500">Reason</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $leaveApplication->reason }}</p>
                        </div>

                        @if ($leaveApplication->approved_by)
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Approved/Rejected By</h3>
                                <p class="mt-1 text-sm text-gray-900">{{ $leaveApplication->approver->name }}</p>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Approved/Rejected On</h3>
                                <p class="mt-1 text-sm text-gray-900">{{ $leaveApplication->approved_at->format('M d, Y') }}</p>
                            </div>

                            <div class="md:col-span-2">
                                <h3 class="text-sm font-medium text-gray-500">Comments</h3>
                                <p class="mt-1 text-sm text-gray-900">{{ $leaveApplication->comments ?? 'No comments provided' }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Approval/Rejection Form -->
        <div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Leave Balance</h2>

                    @if ($leaveApplication->leaveType->is_limited)
                        @php
                            $balance = $leaveApplication->user->leaveBalances()->where('leave_type_id', $leaveApplication->leave_type_id)->where('year', now()->year)->first();
                        @endphp

                        @if ($balance)
                            <div class="mb-4">
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700">{{ $leaveApplication->leaveType->name }}</span>
                                    <span class="text-sm font-medium text-gray-700">
                                        {{ $balance->total_allocated - $balance->used }} / {{ $balance->total_allocated }}
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-blue-600 h-2.5 rounded-full"
                                        style="width: {{ max(0, min(100, $balance->total_allocated > 0 ? (($balance->total_allocated - $balance->used) / $balance->total_allocated) * 100 : 0)) }}%">
                                    </div>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">
                                    {{ $balance->pending }} days pending approval
                                </p>
                            </div>
                        @else
                            <p class="text-sm text-gray-500">No leave balance information available.</p>
                        @endif
                    @else
                        <p class="text-sm text-gray-500">This leave type has no quota limit.</p>
                    @endif
                </div>

                @if ($leaveApplication->status === 'pending')
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Take Action</h2>

                        <div class="mb-4">
                            <label for="comments" class="block text-sm font-medium text-gray-700">Comments</label>
                            <textarea wire:model="comments" id="comments" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                placeholder="Add comments (optional)"></textarea>
                        </div>

                        <div class="flex space-x-3">
                            <button wire:click="approve" type="button"
                                class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Approve
                            </button>
                            <button wire:click="reject" type="button"
                                class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Reject
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
