<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - Organice</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Livewire -->
    @livewireStyles

    <style>
        .login-animation {
            animation: fadeInUp 0.8s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .floating {
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-15px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        .bg-gradient {
            background: linear-gradient(135deg, #4F46E5 0%, #9333EA 100%);
        }

        .input-focus-effect:focus-within {
            transform: scale(1.02);
            transition: transform 0.3s ease;
        }
    </style>
</head>

<body class="font-sans antialiased" style="font-family: 'Poppins', sans-serif;">
    <div class="min-h-screen flex flex-col sm:flex-row">
        <!-- Left Panel - Illustration and Welcome Text -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient items-center justify-center p-12 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient opacity-90"></div>
            <div class="relative z-10 text-center">
                <div class="floating mb-8">
                    <svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="text-white mx-auto">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h1 class="text-4xl font-extrabold text-white mb-2">Welcome to Organice</h1>
                <p class="text-xl text-white opacity-80 mb-8">Your complete attendance & leave management solution</p>
                <div class="flex flex-col space-y-4">
                    <div class="flex items-center text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Track your attendance with ease</span>
                    </div>
                    <div class="flex items-center text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Manage leave applications</span>
                    </div>
                    <div class="flex items-center text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Track project time efficiently</span>
                    </div>
                </div>
            </div>

            <!-- Decorative Elements -->
            <div class="absolute top-10 right-10 h-20 w-20 bg-white rounded-full opacity-10"></div>
            <div class="absolute bottom-10 left-10 h-32 w-32 bg-white rounded-full opacity-10"></div>
            <div class="absolute top-1/2 left-1/4 h-16 w-16 bg-white rounded-full opacity-10"></div>
        </div>

        <!-- Right Panel - Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
            <div class="w-full max-w-md login-animation">
                @livewire('auth.login')
            </div>
        </div>
    </div>

    @livewireScripts
</body>

</html>
