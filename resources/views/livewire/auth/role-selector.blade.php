<div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
    <div class="text-center mb-3">
        <h3 class="font-medium text-gray-700">Quick Login Demo</h3>
        <p class="text-xs text-gray-500">Select a role to auto-fill credentials</p>
    </div>

    <div class="grid grid-cols-4 gap-2">
        <button wire:click="$set('selectedRole', 'admin')" type="button"
            class="py-2 px-3 text-xs text-center font-medium rounded-md transition-all duration-200 {{ $selectedRole === 'admin' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-300' }}">
            Admin
        </button>

        <button wire:click="$set('selectedRole', 'hr')" type="button"
            class="py-2 px-3 text-xs text-center font-medium rounded-md transition-all duration-200 {{ $selectedRole === 'hr' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-300' }}">
            HR
        </button>

        <button wire:click="$set('selectedRole', 'manager')" type="button"
            class="py-2 px-3 text-xs text-center font-medium rounded-md transition-all duration-200 {{ $selectedRole === 'manager' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-300' }}">
            Manager
        </button>

        <button wire:click="$set('selectedRole', 'employee')" type="button"
            class="py-2 px-3 text-xs text-center font-medium rounded-md transition-all duration-200 {{ $selectedRole === 'employee' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-300' }}">
            Employee
        </button>
    </div>

    @if ($selectedRole)
        <div class="mt-3 text-center">
            <button wire:click="fillCredentials" type="button" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium flex items-center mx-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Auto-fill {{ ucfirst($selectedRole) }} credentials
            </button>
            <div class="mt-2 text-xs text-gray-500">
                Email: {{ $demoCredentials['email'] ?? '' }}
                <br>
                Password: {{ $demoCredentials['password'] ? '••••••••' : '' }}
            </div>
        </div>
    @endif
</div>
