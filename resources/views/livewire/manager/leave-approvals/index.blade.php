<div>
    <x-slot name="header">Leave Approvals</x-slot>

    <!-- Search & Filter Bar -->
    <div class="bg-white p-4 shadow sm:rounded-lg mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="flex space-x-4 mb-4 md:mb-0">
                <button wire:click="setFilter('pending')"
                    class="{{ $filter === 'pending' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }} px-4 py-2 rounded-md text-sm font-medium">
                    Pending
                </button>
                <button wire:click="setFilter('approved')"
                    class="{{ $filter === 'approved' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }} px-4 py-2 rounded-md text-sm font-medium">
                    Approved
                </button>
                <button wire:click="setFilter('rejected')"
                    class="{{ $filter === 'rejected' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }} px-4 py-2 rounded-md text-sm font-medium">
                    Rejected
                </button>
            </div>

            <div class="w-full md:w-64">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search"
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        placeholder="Search by name or email" />
                </div>
            </div>
        </div>
    </div>

    <!-- Leave Applications Table -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">

            @if ($leaveApplications->count() > 0)
                <div class="overflow-x-auto mb-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Leave Type</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Days</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applied On</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($leaveApplications as $leaveApplication)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="ml-0">
                                                <div class="text-sm font-medium text-gray-900">{{ $leaveApplication->user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $leaveApplication->user->designation->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $leaveApplication->leaveType->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $leaveApplication->from_date->format('M d, Y') }}</div>
                                        <div class="text-sm text-gray-500">to {{ $leaveApplication->to_date->format('M d, Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $leaveApplication->days_count }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $leaveApplication->created_at->format('M d, Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($leaveApplication->status === 'pending')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Pending
                                            </span>
                                        @elseif ($leaveApplication->status === 'approved')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Approved
                                            </span>
                                        @elseif ($leaveApplication->status === 'rejected')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Rejected
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('leave-approvals.show', $leaveApplication) }}" class="text-blue-600 hover:text-blue-900">
                                            {{ $leaveApplication->status === 'pending' ? 'Review' : 'View' }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div>
                    {{ $leaveApplications->links() }}
                </div>
            @else
                <div class="text-center py-8">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>

                    @if ($filter === 'pending')
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No pending leave applications</h3>
                    @elseif ($filter === 'approved')
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No approved leave applications</h3>
                    @elseif ($filter === 'rejected')
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No rejected leave applications</h3>
                    @else
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No leave applications found</h3>
                    @endif

                    @if ($search)
                        <p class="mt-1 text-sm text-gray-500">Try adjusting your search criteria.</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
