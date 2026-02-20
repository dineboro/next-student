
<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' || false }"
      :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'NextStudent - Auto Shop Helper')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Configure Tailwind for dark mode -->
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>

    <!-- Alpine.js for interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('styles')
</head>
<body class="bg-gray-100 dark:bg-gray-900 transition-colors duration-200">

<!-- Enhanced Navigation -->
<nav class="bg-white dark:bg-gray-800 shadow-lg transition-colors duration-200"
     x-data="{
             mobileMenuOpen: false,
             notificationsOpen: false,
             profileOpen: false,
             unreadNotifications: 3
         }">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo and primary nav -->
            <div class="flex">
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ auth()->check() ? (auth()->user()->role === 'student' ? route('student.dashboard') : route('instructor.dashboard')) : '/' }}"
                       class="text-xl font-bold text-gray-800 dark:text-white flex items-center">
                        <span class="text-2xl mr-2">🔧</span>
                        <span class="hidden sm:block">NextStudent</span>
                    </a>
                </div>

                @auth
                    <!-- Desktop Navigation Links -->
                    <div class="hidden md:ml-6 md:flex md:space-x-8">
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}"
                               class="border-transparent text-gray-600 dark:text-gray-300 hover:border-blue-500 hover:text-gray-900 dark:hover:text-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition">
                                Admin Dashboard
                            </a>
                            <a href="{{ route('admin.school-requests.pending') }}"
                               class="border-transparent text-gray-600 dark:text-gray-300 hover:border-blue-500 hover:text-gray-900 dark:hover:text-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition">
                                School Requests
                            </a>
                        @elseif(auth()->user()->role === 'student')
                            <a href="{{ route('student.dashboard') }}"
                               class="border-transparent text-gray-600 dark:text-gray-300 hover:border-blue-500 hover:text-gray-900 dark:hover:text-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition">
                                Dashboard
                            </a>
                            <a href="{{ route('student.my-requests') }}"
                               class="border-transparent text-gray-600 dark:text-gray-300 hover:border-blue-500 hover:text-gray-900 dark:hover:text-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition">
                                My Requests
                            </a>
                            <a href="{{ route('help-requests.create') }}"
                               class="border-transparent text-gray-600 dark:text-gray-300 hover:border-blue-500 hover:text-gray-900 dark:hover:text-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition">
                                New Request
                            </a>
                        @else
                            <a href="{{ route('instructor.dashboard') }}"
                               class="border-transparent text-gray-600 dark:text-gray-300 hover:border-blue-500 hover:text-gray-900 dark:hover:text-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition">
                                Dashboard
                            </a>
                            <a href="{{ route('instructor.active-requests') }}"
                               class="border-transparent text-gray-600 dark:text-gray-300 hover:border-blue-500 hover:text-gray-900 dark:hover:text-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition">
                                Active Requests
                            </a>
                            <a href="{{ route('instructor.instructors-list') }}"
                               class="border-transparent text-gray-600 dark:text-gray-300 hover:border-blue-500 hover:text-gray-900 dark:hover:text-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition">
                                Instructors
                            </a>
                            <a href="{{ route('instructor.history') }}"
                               class="border-transparent text-gray-600 dark:text-gray-300 hover:border-blue-500 hover:text-gray-900 dark:hover:text-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition">
                                History
                            </a>
                        @endif
                    </div>
                @endauth
            </div>

            <!-- Right side - Dark Mode, Notifications, Profile -->
            <div class="flex items-center space-x-4">
                @auth
                    <!-- Dark Mode Toggle -->
                    <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)"
                            class="p-2 rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                        </svg>
                        <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </button>

                    <!-- Notifications Bell -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                                class="p-2 rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 relative transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <!-- Notification Badge -->
                            <span x-show="unreadNotifications > 0"
                                  class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full"
                                  x-text="unreadNotifications"></span>
                        </button>

                        <!-- Notifications Dropdown -->
                        <div x-show="open"
                             @click.away="open = false"
                             x-transition
                             class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-xl z-50 overflow-hidden">
                            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Notifications</h3>
                            </div>
                            <div class="max-h-96 overflow-y-auto">
                                <!-- Sample Notifications -->
                                <a href="#" class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <div class="w-2 h-2 bg-blue-600 rounded-full mt-2"></div>
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <p class="text-sm text-gray-900 dark:text-white">New request assigned</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">5 minutes ago</p>
                                        </div>
                                    </div>
                                </a>
                                <div class="px-4 py-3 text-center text-sm text-gray-500 dark:text-gray-400">
                                    No more notifications
                                </div>
                            </div>
                            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 text-center">
                                <a href="#" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">View all notifications</a>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                                class="flex items-center space-x-3 focus:outline-none">
                            <div class="hidden md:block text-right">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ ucfirst(auth()->user()->role) }}
                                    @if(auth()->user()->role === 'instructor')
                                        <span class="ml-1 px-2 py-0.5 text-xs rounded {{ auth()->user()->is_available ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                                {{ auth()->user()->is_available ? 'Available' : 'Unavailable' }}
                                            </span>
                                    @endif
                                </p>
                            </div>
                            <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-semibold">
                                {{ substr(auth()->user()->first_name, 0, 1) }}{{ substr(auth()->user()->last_name, 0, 1) }}
                            </div>
                        </button>

                        <!-- Profile Dropdown Menu -->
                        <div x-show="open"
                             @click.away="open = false"
                             x-transition
                             class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-xl z-50 overflow-hidden">
                            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                    {{ auth()->user()->email }}
                                </p>
                            </div>

                            <div class="py-1">
                                <a href="{{ route('profile.show') }}"
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Profile
                                </a>

                                <a href="{{ route('settings.index') }}"
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Settings
                                </a>

                                @if(auth()->user()->role === 'instructor')
                                    <form method="POST" action="{{ route('instructor.toggle-availability') }}" class="w-full">
                                        @csrf
                                        <button type="submit"
                                                class="w-full flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition text-left">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ auth()->user()->is_available ? 'Mark Unavailable' : 'Mark Available' }}
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <div class="py-1 border-t border-gray-200 dark:border-gray-700">
                                <form method="POST" action="{{ route('logout') }}" class="w-full">
                                    @csrf
                                    <button type="submit"
                                            class="w-full flex items-center px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition text-left">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile menu button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen"
                            class="md:hidden p-2 rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                @else
                    <a href="{{ route('login') }}"
                        @class([
                            'text-sm px-4 py-2 rounded transition',
                            'text-white bg-blue-600 hover:bg-blue-700' => request()->routeIs('login'),
                            'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' => !request()->routeIs('login')
                        ])>
                        Login
                    </a>

                    <a href="{{ route('register') }}"
                        @class([
                            'text-sm px-4 py-2 rounded transition',
                            'text-white bg-blue-600 hover:bg-blue-700' => request()->routeIs('register'),
                            'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' => !request()->routeIs('register')
                        ])>
                        Register
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    @auth
        <div x-show="mobileMenuOpen"
             x-transition
             class="md:hidden border-t border-gray-200 dark:border-gray-700">
            <div class="pt-2 pb-3 space-y-1">
                @if(auth()->user()->role === 'student')
                    <a href="{{ route('student.dashboard') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">Dashboard</a>
                    <a href="{{ route('student.my-requests') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">My Requests</a>
                    <a href="{{ route('help-requests.create') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">New Request</a>
                @else
                    <a href="{{ route('instructor.dashboard') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">Dashboard</a>
                    <a href="{{ route('instructor.active-requests') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">Active Requests</a>
                    <a href="{{ route('instructor.instructors-list') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">Instructors</a>
                    <a href="{{ route('instructor.history') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">History</a>
                @endif
            </div>
        </div>
    @endauth
</nav>

<!-- Flash Messages -->
@if(session('success'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-200 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-200 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    </div>
@endif

<!-- Main Content -->
<main class="py-6">
    @yield('content')
</main>

<!-- Footer -->
<footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 mt-12 transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <p class="text-center text-sm text-gray-500 dark:text-gray-400">
            © {{ date('Y') }} NextStudent. All rights reserved.
        </p>
    </div>
</footer>

@stack('scripts')
</body>
</html>
