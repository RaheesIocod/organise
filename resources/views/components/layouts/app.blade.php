<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Organice') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Livewire Styles -->
    @livewireStyles

    <!-- Styles -->
    @stack('styles')
</head>

<body class="font-sans antialiased min-h-screen bg-gray-100">
    <div class="min-h-screen bg-gray-100 flex flex-col">
        <!-- Navigation -->
        <nav class="bg-white shadow">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between">
                    <div class="flex">
                        <div class="flex flex-shrink-0 items-center">
                            <a href="{{ route('dashboard') }}" class="text-xl font-bold text-indigo-600">Organice</a>
                        </div>
                        <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                            <a href="{{ route('dashboard') }}"
                                class="{{ request()->routeIs('dashboard') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center border-b-2 px-1 pt-1 text-sm font-medium">
                                Dashboard
                            </a>
                            <a href="{{ route('leaves') }}"
                                class="{{ request()->routeIs('leaves*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center border-b-2 px-1 pt-1 text-sm font-medium">
                                Leaves
                            </a>
                            <a href="{{ route('attendance') }}"
                                class="{{ request()->routeIs('attendance*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center border-b-2 px-1 pt-1 text-sm font-medium">
                                Attendance
                            </a>
                            <a href="{{ route('projects') }}"
                                class="{{ request()->routeIs('projects*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center border-b-2 px-1 pt-1 text-sm font-medium">
                                Projects
                            </a>
                            <a href="{{ route('task-time-entries') }}"
                                class="{{ request()->routeIs('task-time-entries*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center border-b-2 px-1 pt-1 text-sm font-medium">
                                Tasks
                            </a>
                            <a href="{{ route('reports') }}"
                                class="{{ request()->routeIs('reports*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center border-b-2 px-1 pt-1 text-sm font-medium">
                                Reports
                            </a>
                        </div>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:items-center">
                        <!-- Profile dropdown -->
                        <div class="relative ml-3">
                            <div>
                                <button type="button" class="flex rounded-full bg-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2" id="user-menu-button"
                                    aria-expanded="false" aria-haspopup="true">
                                    <span class="sr-only">Open user menu</span>
                                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-indigo-600">
                                        <span class="text-sm font-medium leading-none text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                    </span>
                                </button>
                            </div>
                            <div class="hidden absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu"
                                aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1" id="user-menu">
                                <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Your Profile</a>
                                @livewire('auth.logout')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <main class="flex-1">
            <div class="py-6">
                {{ $slot }}
            </div>
        </main>
    </div>

    <!-- User Menu Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userMenuButton = document.getElementById('user-menu-button');
            const userMenu = document.getElementById('user-menu');

            userMenuButton?.addEventListener('click', function() {
                userMenu?.classList.toggle('hidden');
            });

            document.addEventListener('click', function(e) {
                if (!userMenuButton?.contains(e.target) && !userMenu?.contains(e.target)) {
                    userMenu?.classList.add('hidden');
                }
            });
        });
    </script>

    <!-- Livewire Scripts -->
    @livewireScripts

    <!-- Scripts -->
    @stack('scripts')
</body>

</html>
