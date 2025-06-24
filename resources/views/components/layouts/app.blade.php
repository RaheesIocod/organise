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
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Livewire Styles -->
    @livewireStyles

    <!-- Styles -->
    @stack('styles')
</head>

<body class="font-sans antialiased min-h-screen bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="sidebar-wrapper transition-all duration-300 ease-in-out" id="sidebar-wrapper">
            <div class="fixed inset-y-0 flex flex-col w-64 bg-gradient-to-br from-indigo-700 to-indigo-900 text-white shadow-xl overflow-hidden" id="sidebar">
                <!-- Logo -->
                <div class="flex items-center justify-between h-16 px-4 border-b border-indigo-800">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 min-w-0">
                        <span class="material-icons-outlined text-2xl flex-shrink-0">apps</span>
                        <span class="text-xl font-bold tracking-wide truncate">Organice</span>
                    </a>
                    <button id="sidebar-toggle-btn" class="lg:hidden p-2 rounded-md hover:bg-indigo-800 focus:outline-none flex-shrink-0">
                        <span class="material-icons-outlined">menu_open</span>
                    </button>
                </div>

                <!-- Navigation Links -->
                <nav class="flex-1 px-2 py-4 space-y-2 overflow-y-auto">
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-indigo-800 text-white' : 'text-indigo-100 hover:bg-indigo-800 hover:text-white' }} transition-colors duration-200 ease-in-out overflow-hidden">
                        <span class="material-icons-outlined mr-3 flex-shrink-0">dashboard</span>
                        <span class="truncate">Dashboard</span>
                    </a>

                    <a href="{{ route('leaves') }}"
                        class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('leaves*') ? 'bg-indigo-800 text-white' : 'text-indigo-100 hover:bg-indigo-800 hover:text-white' }} transition-colors duration-200 ease-in-out overflow-hidden">
                        <span class="material-icons-outlined mr-3 flex-shrink-0">event_busy</span>
                        <span class="truncate">Leaves</span>
                    </a>

                    <a href="{{ route('attendance') }}"
                        class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('attendance*') ? 'bg-indigo-800 text-white' : 'text-indigo-100 hover:bg-indigo-800 hover:text-white' }} transition-colors duration-200 ease-in-out overflow-hidden">
                        <span class="material-icons-outlined mr-3 flex-shrink-0">fact_check</span>
                        <span class="truncate">Attendance</span>
                    </a>

                    <a href="{{ route('projects') }}"
                        class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('projects*') ? 'bg-indigo-800 text-white' : 'text-indigo-100 hover:bg-indigo-800 hover:text-white' }} transition-colors duration-200 ease-in-out overflow-hidden">
                        <span class="material-icons-outlined mr-3 flex-shrink-0">folder</span>
                        <span class="truncate">Projects</span>
                    </a>

                    <a href="{{ route('task-time-entries') }}"
                        class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('task-time-entries*') ? 'bg-indigo-800 text-white' : 'text-indigo-100 hover:bg-indigo-800 hover:text-white' }} transition-colors duration-200 ease-in-out overflow-hidden">
                        <span class="material-icons-outlined mr-3 flex-shrink-0">task_alt</span>
                        <span class="truncate">Tasks</span>
                    </a>

                    <a href="{{ route('reports') }}"
                        class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('reports*') ? 'bg-indigo-800 text-white' : 'text-indigo-100 hover:bg-indigo-800 hover:text-white' }} transition-colors duration-200 ease-in-out overflow-hidden">
                        <span class="material-icons-outlined mr-3 flex-shrink-0">analytics</span>
                        <span class="truncate">Reports</span>
                    </a>
                </nav> <!-- Sidebar Toggle Button -->
                <div class="p-3 sm:p-4 border-t border-indigo-800">
                    <button id="sidebar-hide-btn"
                        class="flex items-center justify-center w-full px-3 sm:px-4 py-2 bg-indigo-800 rounded-lg text-white hover:bg-indigo-900 transition-colors duration-200 ease-in-out group relative">
                        <span class="material-icons-outlined mr-2 flex-shrink-0">menu_open</span>
                        <span class="truncate">Collapse Sidebar</span>

                        <!-- Tooltip that appears on hover -->
                        <div class="absolute bottom-full mb-2 left-1/2 transform -translate-x-1/2 hidden group-hover:block bg-gray-900 text-xs text-white px-2 py-1 rounded whitespace-nowrap z-10">
                            <span class="sidebar-expanded-tooltip">Hide sidebar</span>
                            <span class="sidebar-collapsed-tooltip hidden">Expand sidebar</span>
                            <div class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-gray-900"></div>
                        </div>
                    </button>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="flex-1 relative z-0 overflow-y-auto focus:outline-none" id="main-content">
            <!-- Top bar -->
            <div class="bg-white shadow-sm z-10">
                <div class="flex items-center justify-between h-16 px-4 sm:px-6">
                    <!-- Mobile menu button and actions container -->
                    <div class="flex items-center">
                        <button id="mobile-menu-btn" class="lg:hidden text-gray-600 hover:text-gray-900 focus:outline-none mr-3">
                            <span class="material-icons-outlined">menu</span>
                        </button>

                        <!-- Desktop sidebar toggle -->
                        <button id="desktop-sidebar-toggle" class="hidden lg:flex items-center justify-center p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none"
                            aria-label="Toggle sidebar">
                            <span class="material-icons-outlined">menu_open</span>
                        </button>
                    </div>

                    <div class="mx-auto lg:mx-0 lg:ml-4 flex-1 min-w-0">
                        <h1 class="text-lg sm:text-xl font-semibold text-gray-800 truncate text-center lg:text-left">
                            @if (request()->routeIs('dashboard'))
                                Dashboard
                            @elseif(request()->routeIs('leaves*'))
                                Leaves Management
                            @elseif(request()->routeIs('attendance*'))
                                Attendance Tracking
                            @elseif(request()->routeIs('projects*'))
                                Projects
                            @elseif(request()->routeIs('task-time-entries*'))
                                Task Tracking
                            @elseif(request()->routeIs('reports*'))
                                Reports & Analytics
                            @elseif(request()->routeIs('profile'))
                                User Profile
                            @else
                                {{ config('app.name', 'Organice') }}
                            @endif
                        </h1>
                    </div>

                    <!-- Right side buttons -->
                    <div class="flex items-center">
                        <!-- Notification and help buttons (hidden on small screens) -->
                        <div class="hidden sm:flex items-center space-x-1 mr-2">
                            <button class="p-2 rounded-full text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none relative">
                                <span class="material-icons-outlined">notifications</span>
                                <!-- Notification badge -->
                                <span class="absolute top-1 right-1 h-3 w-3 bg-red-500 rounded-full border-2 border-white"></span>
                            </button>
                            <button class="p-2 rounded-full text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none">
                                <span class="material-icons-outlined">help_outline</span>
                            </button>
                        </div>

                        <!-- User Menu -->
                        <div class="relative ml-2">
                            <button type="button"
                                class="flex items-center space-x-2 focus:outline-none border border-transparent hover:border-gray-200 rounded-md px-2 py-1 transition-all duration-150"
                                id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                <div class="h-8 w-8 rounded-full bg-indigo-600 flex items-center justify-center">
                                    <span class="font-medium text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                </div>
                                <div class="hidden md:block text-left">
                                    <p class="text-sm font-medium text-gray-800">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500">My Account</p>
                                </div>
                                <span class="material-icons-outlined text-gray-500 transition-transform duration-200" id="user-menu-arrow">expand_more</span>
                            </button>

                            <!-- Profile Dropdown -->
                            <div class="hidden absolute right-0 z-50 mt-2 w-56 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" id="user-menu"
                                role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                                </div>
                                <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1" id="user-menu-item-0">
                                    <div class="flex items-center">
                                        <span class="material-icons-outlined mr-2 text-sm">person</span>
                                        <span>Profile</span>
                                    </div>
                                </a>
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1" id="user-menu-item-1">
                                    <div class="flex items-center">
                                        <span class="material-icons-outlined mr-2 text-sm">dashboard</span>
                                        <span>Dashboard</span>
                                    </div>
                                </a>
                                <div class="border-t border-gray-100 mt-1">
                                    @livewire('auth.logout')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <main class="px-4 sm:px-6 py-6 sm:py-8">
                <div class="w-full max-w-7xl mx-auto">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // User menu toggle with animation
            const userMenuButton = document.getElementById('user-menu-button');
            const userMenu = document.getElementById('user-menu');
            const userMenuArrow = document.getElementById('user-menu-arrow');

            userMenuButton?.addEventListener('click', function() {
                userMenu?.classList.toggle('hidden');

                // Rotate arrow when menu is opened/closed
                if (!userMenu?.classList.contains('hidden')) {
                    userMenuArrow.style.transform = 'rotate(180deg)';
                    userMenuButton.setAttribute('aria-expanded', 'true');
                } else {
                    userMenuArrow.style.transform = 'rotate(0)';
                    userMenuButton.setAttribute('aria-expanded', 'false');
                }
            }); // Mobile and desktop sidebar toggle
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const sidebarWrapper = document.getElementById('sidebar-wrapper');
            const sidebarToggleBtn = document.getElementById('sidebar-toggle-btn');
            const sidebarHideBtn = document.getElementById('sidebar-hide-btn');
            const mainContent = document.getElementById('main-content');
            const sidebar = document.getElementById('sidebar');

            // Get sidebar state from localStorage or default to false
            let isSidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';

            // Apply the saved state on page load
            if (isSidebarCollapsed) {
                applySidebarCollapsedState();
            }

            function toggleMobileSidebar() {
                sidebarWrapper.classList.toggle('mobile-sidebar-open');

                if (sidebarWrapper.classList.contains('mobile-sidebar-open')) {
                    document.body.classList.add('sidebar-open-body');
                    // Prevent scrolling on the body when sidebar is open
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.classList.remove('sidebar-open-body');
                    // Restore scrolling
                    document.body.style.overflow = '';
                }
            }

            function applySidebarCollapsedState() {
                sidebar.classList.add('w-20');
                sidebar.classList.remove('w-64');
                sidebarWrapper.classList.add('sidebar-collapsed');
                mainContent.classList.remove('lg:ml-64');
                mainContent.classList.add('lg:ml-20');

                // Update the button content
                sidebarHideBtn.innerHTML = `
                    <span class="material-icons-outlined mr-0">menu</span>
                    <div class="absolute bottom-full mb-2 left-1/2 transform -translate-x-1/2 hidden group-hover:block bg-gray-900 text-xs text-white px-2 py-1 rounded whitespace-nowrap">
                        <span class="sidebar-expanded-tooltip hidden">Hide sidebar</span>
                        <span class="sidebar-collapsed-tooltip">Expand sidebar</span>
                        <div class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-gray-900"></div>
                    </div>`;
                sidebarHideBtn.classList.add('justify-center');
            }

            function applySidebarExpandedState() {
                sidebar.classList.remove('w-20');
                sidebar.classList.add('w-64');
                sidebarWrapper.classList.remove('sidebar-collapsed');
                mainContent.classList.add('lg:ml-64');
                mainContent.classList.remove('lg:ml-20');

                // Update the button content
                sidebarHideBtn.innerHTML = `
                    <span class="material-icons-outlined mr-2">menu_open</span>
                    <span>Collapse Sidebar</span>
                    <div class="absolute bottom-full mb-2 left-1/2 transform -translate-x-1/2 hidden group-hover:block bg-gray-900 text-xs text-white px-2 py-1 rounded whitespace-nowrap">
                        <span class="sidebar-expanded-tooltip">Hide sidebar</span>
                        <span class="sidebar-collapsed-tooltip hidden">Expand sidebar</span>
                        <div class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-gray-900"></div>
                    </div>`;
                sidebarHideBtn.classList.remove('justify-center');
            }

            function toggleDesktopSidebar() {
                isSidebarCollapsed = !isSidebarCollapsed;

                // Save state to localStorage
                localStorage.setItem('sidebarCollapsed', isSidebarCollapsed);

                if (isSidebarCollapsed) {
                    applySidebarCollapsedState();
                } else {
                    applySidebarExpandedState();
                }
            }

            mobileMenuBtn?.addEventListener('click', toggleMobileSidebar);
            sidebarToggleBtn?.addEventListener('click', toggleMobileSidebar);
            sidebarHideBtn?.addEventListener('click', toggleDesktopSidebar);

            // Handle toggle from top bar button if it exists
            const desktopSidebarToggle = document.getElementById('desktop-sidebar-toggle');
            desktopSidebarToggle?.addEventListener('click', toggleDesktopSidebar);

            // Close profile menu when clicking outside
            document.addEventListener('click', function(e) {
                if (!userMenuButton?.contains(e.target) && !userMenu?.contains(e.target)) {
                    userMenu?.classList.add('hidden');
                    userMenuArrow.style.transform = 'rotate(0)';
                    userMenuButton.setAttribute('aria-expanded', 'false');
                }
            });

            // Add responsive behavior
            function handleResize() {
                if (window.innerWidth < 1024) { // lg breakpoint
                    sidebarWrapper.classList.add('lg:translate-x-0');
                    sidebarWrapper.classList.add('-translate-x-full');

                    // For mobile, don't apply collapsed margins
                    if (isSidebarCollapsed) {
                        mainContent.classList.add('lg:ml-20');
                    } else {
                        mainContent.classList.add('lg:ml-64');
                    }
                } else {
                    sidebarWrapper.classList.remove('-translate-x-full');
                    sidebarWrapper.classList.remove('mobile-sidebar-open');
                    document.body.classList.remove('sidebar-open-body');

                    // Apply the correct margin based on sidebar state
                    if (isSidebarCollapsed) {
                        mainContent.classList.remove('lg:ml-64');
                        mainContent.classList.add('lg:ml-20');
                    } else {
                        mainContent.classList.add('lg:ml-64');
                        mainContent.classList.remove('lg:ml-20');
                    }
                }
            }

            // Initial call
            handleResize();

            // Listen for window resize
            window.addEventListener('resize', handleResize);
        });
    </script>
    <style>
        /* Custom sidebar styles */
        .sidebar-wrapper {
            @apply fixed inset-y-0 z-20 transform lg:translate-x-0 transition-all duration-300 ease-in-out;
        }

        .mobile-sidebar-open {
            @apply translate-x-0;
        }

        .sidebar-open-body {
            @apply overflow-hidden;
        }

        /* Collapsed sidebar styles */
        .sidebar-collapsed nav a span:not(.material-icons-outlined) {
            @apply opacity-0 absolute;
            transition: opacity 0.2s ease-out;
        }

        .sidebar-collapsed nav a {
            @apply justify-center relative overflow-hidden;
        }

        .sidebar-collapsed .material-icons-outlined {
            @apply mr-0;
            transition: margin 0.3s ease;
        }

        .sidebar-collapsed .text-xl {
            @apply opacity-0 absolute;
            transition: opacity 0.2s ease-out;
        }

        /* Smooth transitions for sidebar width changes */
        #sidebar {
            transition: width 0.3s ease, transform 0.3s ease;
        }

        #main-content {
            transition: margin 0.3s ease;
        }

        /* Overlay for mobile when sidebar is open */
        .sidebar-open-body::before {
            content: "";
            @apply fixed inset-0 bg-gray-900 bg-opacity-50 z-10;
        }

        /* Responsive improvements */
        @media (max-width: 640px) {

            .h1,
            h1 {
                @apply text-lg;
            }

            .h2,
            h2 {
                @apply text-base;
            }
        }

        /* Fix for cards on small screens */
        .card,
        .card-body {
            min-width: 0;
        }

        /* Responsive table styles */
        @media (max-width: 768px) {
            .responsive-table {
                @apply block w-full overflow-x-auto;
            }
        }

        /* Responsive improvements */
        @media (max-width: 640px) {

            .h1,
            h1 {
                @apply text-lg;
            }

            .h2,
            h2 {
                @apply text-base;
            }
        }

        /* Fix for cards on small screens */
        .card,
        .card-body {
            min-width: 0;
        }

        /* Responsive table styles */
        @media (max-width: 768px) {
            .responsive-table {
                @apply block w-full overflow-x-auto;
            }
        }

        /* Beautiful custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            @apply bg-transparent;
        }

        ::-webkit-scrollbar-thumb {
            @apply bg-indigo-300 bg-opacity-70 rounded-full;
        }

        ::-webkit-scrollbar-thumb:hover {
            @apply bg-indigo-400;
        }
    </style>

    <!-- Livewire Scripts -->
    @livewireScripts

    <!-- Scripts -->
    @stack('scripts')
</body>

</html>
