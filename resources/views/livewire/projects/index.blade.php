<div>
    <x-slot name="header">Projects</x-slot>

    <!-- Search & Filter Bar -->
    <div class="bg-white p-4 shadow sm:rounded-lg mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="flex space-x-4 mb-4 md:mb-0">
                <button wire:click="setFilter('all')" class="{{ $filter === 'all' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }} px-4 py-2 rounded-md text-sm font-medium">
                    All
                </button>
                <button wire:click="setFilter('not_started')"
                    class="{{ $filter === 'not_started' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }} px-4 py-2 rounded-md text-sm font-medium">
                    Not Started
                </button>
                <button wire:click="setFilter('in_progress')"
                    class="{{ $filter === 'in_progress' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }} px-4 py-2 rounded-md text-sm font-medium">
                    In Progress
                </button>
                <button wire:click="setFilter('completed')"
                    class="{{ $filter === 'completed' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }} px-4 py-2 rounded-md text-sm font-medium">
                    Completed
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
                        placeholder="Search projects" />
                </div>
            </div>
        </div>
    </div>

    <!-- Projects Card Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($projects as $project)
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ $project->name }}</h3>
                        @if ($project->status === 'not_started')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                Not Started
                            </span>
                        @elseif($project->status === 'in_progress')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                In Progress
                            </span>
                        @elseif($project->status === 'completed')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Completed
                            </span>
                        @endif
                    </div>

                    <p class="text-gray-600 line-clamp-2 mb-4">{{ $project->description }}</p>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <div class="text-xs font-medium text-gray-500">Start Date</div>
                            <div class="text-sm text-gray-800">{{ $project->start_date->format('M d, Y') }}</div>
                        </div>
                        <div>
                            <div class="text-xs font-medium text-gray-500">End Date</div>
                            <div class="text-sm text-gray-800">{{ $project->end_date->format('M d, Y') }}</div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('projects.show', $project) }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3">
                <div class="text-center py-10 bg-white rounded-lg shadow">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No projects found</h3>

                    @if ($search || $filter !== 'all')
                        <p class="mt-1 text-sm text-gray-500">Try adjusting your search or filter criteria.</p>
                    @else
                        <p class="mt-1 text-sm text-gray-500">Please create a project to get started.</p>
                    @endif
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($projects->hasPages())
        <div class="mt-6">
            {{ $projects->links() }}
        </div>
    @endif
</div>
