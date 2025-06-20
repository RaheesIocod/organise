<div>
    <div class="container mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Leave Application Details</h1>
            <a href="{{ route('leaves') }}" class="text-blue-600 hover:text-blue-900 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm.707-10.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L9.414 11H13a1 1 0 100-2H9.414l1.293-1.293z"
                        clip-rule="evenodd" />
                </svg>
                Back to Leave Applications
            </a>
        </div>

        @if (session()->has('success'))
            <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-md">
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-md">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2">
                <!-- Leave Details Card -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-start mb-6">
                        <h2 class="text-lg font-semibold text-gray-700">Leave Application #{{ $leave->id }}</h2>
                        <span
                            class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                            {{ $leave->status === 'approved' ? 'bg-green-100 text-green-800' : ($leave->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst($leave->status) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Leave Type</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $leave->leaveType->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">From Date</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $leave->from_date->format('M d, Y') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">To Date</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $leave->to_date->format('M d, Y') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Total Days</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $leave->days_count }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Applied On</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $leave->created_at->format('M d, Y h:i A') }}</dd>
                                </div>
                                @if ($leave->reviewed_at)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Reviewed On</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $leave->reviewed_at->format('M d, Y h:i A') }}</dd>
                                    </div>
                                @endif
                                @if ($leave->reviewer)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Reviewed By</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $leave->reviewer->name }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h3 class="text-sm font-medium text-gray-500">Reason for Leave</h3>
                        <div class="mt-2 p-4 bg-gray-50 rounded-md">
                            <p class="text-sm text-gray-700">{{ $leave->reason }}</p>
                        </div>
                    </div>

                    @if ($leave->comments)
                        <div class="mt-6">
                            <h3 class="text-sm font-medium text-gray-500">Reviewer Comments</h3>
                            <div class="mt-2 p-4 bg-gray-50 rounded-md">
                                <p class="text-sm text-gray-700">{{ $leave->comments }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($leave->status === 'pending')
                        <div class="mt-6 flex justify-end">
                            <button wire:click="cancel" wire:confirm="Are you sure you want to cancel this leave application?"
                                class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-md">
                                Cancel Application
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <div>
                <!-- Leave Timeline Card -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">Application Timeline</h2>
                    <ol class="relative border-l border-gray-200 dark:border-gray-700 ml-3">
                        <li class="mb-10 ml-6">
                            <span class="absolute flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full -left-3 ring-8 ring-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-blue-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </span>
                            <h3 class="flex items-center mb-1 text-sm font-semibold text-gray-900">Application Submitted</h3>
                            <time class="block mb-2 text-xs font-normal leading-none text-gray-400">{{ $leave->created_at->format('M d, Y h:i A') }}</time>
                            <p class="text-xs text-gray-500">Leave application was submitted for {{ $leave->days_count }} day(s).</p>
                        </li>

                        @if ($leave->status !== 'pending')
                            <li class="mb-10 ml-6">
                                <span
                                    class="absolute flex items-center justify-center w-6 h-6 {{ $leave->status === 'approved' ? 'bg-green-100' : 'bg-red-100' }} rounded-full -left-3 ring-8 ring-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 {{ $leave->status === 'approved' ? 'text-green-800' : 'text-red-800' }}" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </span>
                                <h3 class="flex items-center mb-1 text-sm font-semibold text-gray-900">Application {{ ucfirst($leave->status) }}</h3>
                                <time class="block mb-2 text-xs font-normal leading-none text-gray-400">{{ $leave->reviewed_at->format('M d, Y h:i A') }}</time>
                                <p class="text-xs text-gray-500">
                                    Leave was {{ $leave->status }} by {{ $leave->reviewer->name }}.
                                    @if ($leave->comments)
                                        <br>Comment: "{{ $leave->comments }}"
                                    @endif
                                </p>
                            </li>
                        @else
                            <li class="mb-10 ml-6">
                                <span class="absolute flex items-center justify-center w-6 h-6 bg-yellow-100 rounded-full -left-3 ring-8 ring-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-yellow-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </span>
                                <h3 class="flex items-center mb-1 text-sm font-semibold text-gray-900">Awaiting Approval</h3>
                                <p class="text-xs text-gray-500">Leave application is pending approval from your manager.</p>
                            </li>
                        @endif
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
