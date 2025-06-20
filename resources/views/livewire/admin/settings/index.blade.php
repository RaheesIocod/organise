<div>
    <x-slot name="header">System Settings</x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800 mb-6">Administrative Settings</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="p-6 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer" onclick="Livewire.navigate('{{ route('admin.designations') }}')">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">Designations</h3>
                            <p class="text-sm text-gray-500">Manage job titles and positions</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer" onclick="Livewire.navigate('{{ route('admin.leave-types') }}')">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-green-100 flex items-center justify-center text-green-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">Leave Types</h3>
                            <p class="text-sm text-gray-500">Configure leave categories and quotas</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer" onclick="Livewire.navigate('{{ route('admin.users') }}')">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">Users</h3>
                            <p class="text-sm text-gray-500">Manage employees and permissions</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8">
                <h3 class="text-md font-medium text-gray-700 mb-4">System Information</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Application Version</dt>
                            <dd class="mt-1 text-sm text-gray-900">1.0.0</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">PHP Version</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ phpversion() }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Laravel Version</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ app()->version() }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Environment</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ app()->environment() }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
