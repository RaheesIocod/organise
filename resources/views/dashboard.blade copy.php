<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Orgnice</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#0066FF',
                        secondary: '#64748B',
                        accent: '#F1F5F9'
                    },
                    fontFamily: {
                        'sans': ['Cabin', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Cabin:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-accent font-sans">
    <div class="flex h-screen">
        <!-- Left Sidebar -->
        <div class="w-64 bg-white shadow-lg flex flex-col border-r border-gray-200">
            <!-- Logo -->
            <div class="p-6 border-b">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 flex items-center justify-center">
                        <img src="{{ asset('images/icons/dashboard-icon-1.svg') }}" alt="Orgnice Logo" class="w-6 h-6">
                    </div>
                    <span class="text-xl font-bold text-gray-800">Orgnice</span>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="flex-1 p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="#" class="flex items-center space-x-3 px-3 py-2 rounded-lg bg-primary text-white">
                            <img src="{{ asset('images/icons/dashboard-icon-2.svg') }}" alt="Dashboard Icon" class="w-5 h-5">
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-secondary hover:bg-accent">
                            <img src="{{ asset('images/icons/log-time-icon.svg') }}" alt="Log Time Icon" class="w-5 h-5">
                            <span>Log Time</span>
                            <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">2</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-secondary hover:bg-accent">
                            <img src="{{ asset('images/icons/timesheet-icon.svg') }}" alt="Time Sheet Icon" class="w-5 h-5">
                            <span>Time Sheet</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-secondary hover:bg-accent">
                            <img src="{{ asset('images/icons/cabin-book-icon.svg') }}" alt="Cabin Book Icon" class="w-5 h-5">
                            <span>Cabin Book</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-secondary hover:bg-accent">
                            <img src="{{ asset('images/icons/employee-icon.svg') }}" alt="Employee Icon" class="w-5 h-5">
                            <span>Employee</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-secondary hover:bg-accent">
                            <img src="{{ asset('images/icons/projects-icon.svg') }}" alt="Projects Icon" class="w-5 h-5">
                            <span>Projects</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-secondary hover:bg-accent">
                            <img src="{{ asset('images/icons/feedback-icon.svg') }}" alt="Feedback Icon" class="w-5 h-5">
                            <span>Feedback</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-secondary hover:bg-accent">
                            <img src="{{ asset('images/icons/leave-icon.svg') }}" alt="Leave Icon" class="w-5 h-5">
                            <span>Leave</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-secondary hover:bg-accent">
                            <i class="fas fa-file-alt w-5"></i>
                            <span>Policy</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-secondary hover:bg-accent">
                            <i class="fas fa-ticket-alt w-5"></i>
                            <span>Raise a Ticket</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col">
            <!-- Top Alert Banner -->
            <div class="bg-blue-100 border-l-4 border-blue-500 p-4 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-info-circle text-blue-500"></i>
                    <span class="text-blue-700">Alert! We're hiring UI/UX Designers. 3 positions available | Required 2.5 years of relevant experience!</span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="#" class="text-blue-600 hover:text-blue-800 font-medium">Refer Now</a>
                    <button class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <!-- Header with User Info -->
            <div class="bg-white p-6 flex items-center justify-between border-b">
                <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <i class="fas fa-bell text-gray-400 text-xl"></i>
                    </div>
                    <div class="flex items-center space-x-2">
                        <img src="https://placehold.co/40x40/4F46E5/FFFFFF?text=JD" alt="John Doe profile picture" class="w-10 h-10 rounded-full">
                        <div>
                            <div class="text-sm font-medium text-gray-800">John Doe</div>
                            <div class="text-xs text-gray-500">Manager</div>
                        </div>
                        <i class="fas fa-chevron-down text-gray-400 text-sm"></i>
                    </div>
                </div>
            </div>

            <!-- Main Dashboard Content -->
            <div class="flex-1 p-6 overflow-y-auto">
                <div class="flex space-x-6">
                    <!-- Main Content Column -->
                    <div class="flex-1 space-y-6">
                        <!-- User Profile Card -->
                        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                            <!-- Abstract Background Banner -->
                            <div class="h-32 bg-gradient-to-r from-blue-400 via-purple-500 to-blue-600 relative">
                                <div class="absolute inset-0 opacity-30">
                                    <svg viewBox="0 0 400 160" class="w-full h-full">
                                        <path d="M0,80 Q100,20 200,80 T400,80 L400,160 L0,160 Z" fill="rgba(255,255,255,0.1)" />
                                        <path d="M0,100 Q150,40 300,100 T600,100 L600,160 L0,160 Z" fill="rgba(255,255,255,0.05)" />
                                    </svg>
                                </div>
                            </div>

                            <div class="px-6 pb-6 -mt-16 relative">
                                <div class="flex items-end space-x-4">
                                    <div class="relative">
                                        <img src="https://placehold.co/80x80/4F46E5/FFFFFF?text=AV" alt="Adam John Vandervort profile picture" class="w-20 h-20 rounded-full border-4 border-white shadow-lg">
                                        <div class="absolute bottom-1 right-1 w-4 h-4 bg-green-400 border-2 border-white rounded-full"></div>
                                    </div>
                                    <div class="flex-1 pt-4">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h2 class="text-xl font-bold text-gray-800">Adam John Vandervort</h2>
                                                <p class="text-gray-600">#ORG-1006</p>
                                                <p class="text-sm text-gray-500">adam@example.com</p>
                                            </div>
                                            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center space-x-2">
                                                <span>Edit</span>
                                                <i class="fas fa-external-link-alt text-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-4 gap-6 mt-6 pt-6 border-t">
                                    <div>
                                        <p class="text-sm text-gray-500 mb-1">Designation</p>
                                        <p class="font-medium text-gray-800">UX Designer</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 mb-1">Reporting to</p>
                                        <p class="font-medium text-gray-800">Catherine</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 mb-1">Total Experience</p>
                                        <p class="font-medium text-gray-800">4.6 Years</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 mb-1">Company Experience</p>
                                        <p class="font-medium text-gray-800">0.9 Years</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Time Tracker and Break Timings Row -->
                        <div class="grid grid-cols-2 gap-6">
                            <!-- Time Tracker Card -->
                            <div class="bg-white rounded-xl shadow-sm p-6">
                                <div class="flex items-center justify-between mb-6">
                                    <h3 class="text-lg font-semibold text-gray-800">Time Tracker</h3>
                                    <i class="fas fa-external-link-alt text-gray-400"></i>
                                </div>

                                <div class="flex items-center justify-center mb-6">
                                    <div class="relative w-32 h-32">
                                        <svg class="w-32 h-32 transform -rotate-90" viewBox="0 0 120 120">
                                            <circle cx="60" cy="60" r="50" stroke="#E5E7EB" stroke-width="8" fill="none" />
                                            <circle cx="60" cy="60" r="50" stroke="#3B82F6" stroke-width="8" fill="none" stroke-dasharray="314" stroke-dashoffset="157" stroke-linecap="round" />
                                        </svg>
                                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                                            <span class="text-xs text-gray-500">Total Time</span>
                                            <span class="text-xl font-bold text-gray-800">05:36</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                        <div>
                                            <p class="text-xs text-gray-500">Login</p>
                                            <p class="text-sm font-medium">09:00 AM</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-3 h-3 bg-gray-300 rounded-full"></div>
                                        <div>
                                            <p class="text-xs text-gray-500">Logout</p>
                                            <p class="text-sm font-medium">06:00 PM</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Break Timings Card -->
                            <div class="bg-white rounded-xl shadow-sm p-6">
                                <div class="flex items-center justify-between mb-6">
                                    <h3 class="text-lg font-semibold text-gray-800">Break Timings</h3>
                                    <i class="fas fa-external-link-alt text-gray-400"></i>
                                </div>

                                <div class="space-y-4">
                                    <div class="grid grid-cols-3 gap-4 text-xs text-gray-500 font-medium border-b pb-2">
                                        <span>Break Start</span>
                                        <span>Break End</span>
                                        <span>Duration</span>
                                    </div>

                                    <div class="grid grid-cols-3 gap-4 text-sm py-2">
                                        <span class="text-gray-800">11:15:00 AM</span>
                                        <span class="text-gray-800">11:30:00 AM</span>
                                        <span class="text-gray-600">0.15 Hour</span>
                                    </div>

                                    <div class="grid grid-cols-3 gap-4 text-sm py-2">
                                        <span class="text-gray-800">01:00:00 PM</span>
                                        <span class="text-gray-800">02:00:00 PM</span>
                                        <span class="text-gray-600">1.0 Hour</span>
                                    </div>

                                    <div class="grid grid-cols-3 gap-4 text-sm py-2">
                                        <span class="text-gray-800">05:00:00 PM</span>
                                        <span class="text-gray-400">-</span>
                                        <span class="text-gray-400">-</span>
                                    </div>

                                    <div class="flex items-center justify-between pt-4 border-t">
                                        <div class="flex items-center space-x-2">
                                            <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                                            <span class="text-sm text-gray-600">Current Break Time</span>
                                        </div>
                                        <span class="text-red-500 font-mono font-bold">00:08:00</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Suggestions Section -->
                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-800">Suggestions</h3>
                                <button class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 text-sm">
                                    Give Suggestion
                                </button>
                            </div>
                            <p class="text-gray-600 text-sm leading-relaxed">
                                Your suggestions can bring great ideas to the team. Use the Suggestion section to share ways to improve
                                the workplace, enhance productivity, or streamline processes. Every idea matters—your suggestions could make a real
                                difference!
                            </p>
                        </div>

                        <!-- Cabin Booking Section -->
                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-semibold text-gray-800">Cabin Booking</h3>
                                <i class="fas fa-external-link-alt text-gray-400"></i>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div>
                                    <label class="block text-sm text-gray-600 mb-2">Choose Office</label>
                                    <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option>Unit 01</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-600 mb-2">Choose Cabin</label>
                                    <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option>Cabin 2</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-6">
                                <!-- Calendar -->
                                <div>
                                    <div class="flex items-center justify-between mb-4">
                                        <h4 class="font-medium text-gray-800">April 2025</h4>
                                        <div class="flex space-x-2">
                                            <button class="p-1 hover:bg-gray-100 rounded">
                                                <i class="fas fa-chevron-left text-gray-400"></i>
                                            </button>
                                            <button class="p-1 hover:bg-gray-100 rounded">
                                                <i class="fas fa-chevron-right text-gray-400"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-7 gap-1 text-center text-xs mb-2">
                                        <div class="text-gray-500 py-2">Su</div>
                                        <div class="text-gray-500 py-2">Mo</div>
                                        <div class="text-gray-500 py-2">Tu</div>
                                        <div class="text-gray-500 py-2">We</div>
                                        <div class="text-gray-500 py-2">Th</div>
                                        <div class="text-gray-500 py-2">Fr</div>
                                        <div class="text-gray-500 py-2">Sa</div>
                                    </div>

                                    <div class="grid grid-cols-7 gap-1 text-center text-sm">
                                        <div class="py-2 text-gray-400">30</div>
                                        <div class="py-2 text-gray-400">31</div>
                                        <div class="py-2 hover:bg-gray-100 rounded cursor-pointer">01</div>
                                        <div class="py-2 hover:bg-gray-100 rounded cursor-pointer">02</div>
                                        <div class="py-2 bg-blue-600 text-white rounded cursor-pointer">03</div>
                                        <div class="py-2 hover:bg-gray-100 rounded cursor-pointer">04</div>
                                        <div class="py-2 hover:bg-gray-100 rounded cursor-pointer">05</div>
                                        <div class="py-2 hover:bg-gray-100 rounded cursor-pointer">06</div>
                                        <div class="py-2 hover:bg-gray-100 rounded cursor-pointer">07</div>
                                        <div class="py-2 hover:bg-gray-100 rounded cursor-pointer">08</div>
                                        <div class="py-2 hover:bg-gray-100 rounded cursor-pointer">09</div>
                                        <div class="py-2 hover:bg-gray-100 rounded cursor-pointer">10</div>
                                        <div class="py-2 hover:bg-gray-100 rounded cursor-pointer">11</div>
                                        <div class="py-2 hover:bg-gray-100 rounded cursor-pointer">12</div>
                                        <div class="py-2 hover:bg-gray-100 rounded cursor-pointer">13</div>
                                        <div class="py-2 hover:bg-gray-100 rounded cursor-pointer">14</div>
                                        <div class="py-2 hover:bg-gray-100 rounded cursor-pointer">15</div>
                                        <div class="py-2 hover:bg-gray-100 rounded cursor-pointer">16</div>
                                        <div class="py-2 hover:bg-gray-100 rounded cursor-pointer">17</div>
                                        <div class="py-2 hover:bg-gray-100 rounded cursor-pointer">18</div>
                                        <div class="py-2 hover:bg-gray-100 rounded cursor-pointer">19</div>
                                        <div class="py-2 hover:bg-gray-100 rounded cursor-pointer">20</div>
                                        <div class="py-2 hover:bg-gray-100 rounded cursor-pointer">21</div>
                                        <div class="py-2 hover:bg-gray-100 rounded cursor-pointer">22</div>
                                        <div class="py-2 hover:bg-gray-100 rounded cursor-pointer">23</div>
                                        <div class="py-2 hover:bg-gray-100 rounded cursor-pointer">24</div>
                                        <div class="py-2 hover:bg-gray-100 rounded cursor-pointer">25</div>
                                        <div class="py-2 hover:bg-gray-100 rounded cursor-pointer">26</div>
                                        <div class="py-2 hover:bg-gray-100 rounded cursor-pointer">27</div>
                                        <div class="py-2 hover:bg-gray-100 rounded cursor-pointer">28</div>
                                        <div class="py-2 hover:bg-gray-100 rounded cursor-pointer">29</div>
                                        <div class="py-2 hover:bg-gray-100 rounded cursor-pointer">30</div>
                                        <div class="py-2 text-gray-400">01</div>
                                        <div class="py-2 text-gray-400">02</div>
                                        <div class="py-2 text-gray-400">03</div>
                                    </div>
                                </div>

                                <!-- Time Slots -->
                                <div>
                                    <h4 class="font-medium text-gray-800 mb-4">10:00 AM</h4>
                                    <div class="space-y-2">
                                        <button class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                                            11:00 AM - Book Slot
                                        </button>
                                        <div class="text-sm text-gray-600 py-2">11:30 AM</div>
                                        <div class="text-sm text-gray-600 py-2">12:00 PM</div>
                                        <div class="text-sm text-gray-600 py-2">12:30 PM</div>
                                        <div class="text-sm text-gray-600 py-2">01:00 PM</div>
                                        <div class="text-sm text-gray-600 py-2">01:30 PM</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Employee Appraisal Section -->
                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-800">Employee Appraisal</h3>
                                <button class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 text-sm flex items-center space-x-2">
                                    <span>Open Form</span>
                                    <i class="fas fa-external-link-alt"></i>
                                </button>
                            </div>

                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-800 mb-2">1 Year, 0 Months</p>
                                <p class="text-xs text-gray-500 mb-4">03 April 2025</p>
                                <p class="text-sm text-gray-600">
                                    Please complete the form to assist us in evaluating your appraisal and submit it by April 8, 2025. Your input is valuable,
                                    and we appreciate your cooperation. If you need any assistance, feel free to reach out, and we'll be happy to help.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Sidebar -->
                    <div class="w-80 space-y-6">
                        <!-- Birthday Card -->
                        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                            <div class="p-4 border-b">
                                <h3 class="font-semibold text-gray-800">Birthday</h3>
                            </div>
                            <div class="p-4">
                                <div class="flex items-center space-x-3">
                                    <img src="https://placehold.co/60x60/8B5CF6/FFFFFF?text=ZB" alt="Zachary birthday celebration" class="w-15 h-15 rounded-lg object-cover">
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-800">Happy Birthday, Zachary! 🎉</p>
                                        <p class="text-sm text-gray-600 mt-1">Wishing you a wonderful day filled with happiness and joy!</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Work Anniversary Card -->
                        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                            <div class="p-4 border-b">
                                <h3 class="font-semibold text-gray-800">Work Anniversary</h3>
                            </div>
                            <div class="p-4">
                                <div class="flex items-center space-x-3">
                                    <img src="https://placehold.co/60x60/F59E0B/FFFFFF?text=AM" alt="Anna work anniversary celebration" class="w-15 h-15 rounded-lg object-cover">
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-800">Happy 5th Work Anniversary Anna! 🎊</p>
                                        <p class="text-sm text-gray-600 mt-1">Celebrating five amazing years of dedication and excellence!</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Upcoming Events Card -->
                        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                            <div class="p-4 border-b">
                                <h3 class="font-semibold text-gray-800">Upcoming Events</h3>
                            </div>
                            <div class="p-4 space-y-4">
                                <div class="flex items-center space-x-3">
                                    <img src="https://placehold.co/60x60/10B981/FFFFFF?text=NY" alt="New Year celebration event" class="w-15 h-15 rounded-lg object-cover">
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-800">1st January | New Year's Day</p>
                                        <p class="text-sm text-gray-600">Join us for our New Year celebration!</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <img src="https://placehold.co/60x60/EF4444/FFFFFF?text=NY" alt="New Year celebration event" class="w-15 h-15 rounded-lg object-cover">
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-800">1st January | New Year's Day</p>
                                        <p class="text-sm text-gray-600">Join us for our New Year celebration!</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</body>

</html>
