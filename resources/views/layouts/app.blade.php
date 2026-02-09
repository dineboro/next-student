<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Auto Shop Help System')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js for interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('styles')
</head>
<body class="bg-gray-100">
<!-- Navigation -->
<nav class="bg-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="flex-shrink-0 flex items-center">
                    <h1 class="text-xl font-bold text-gray-800">🔧 NEXT STUDENT</h1>
                </div>

                @auth
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        @if(auth()->user()->role === 'student')
                            <a href="{{ route('student.dashboard') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Dashboard
                            </a>
                            <a href="{{ route('student.my-requests') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                My Requests
                            </a>
                            <a href="{{ route('help-requests.create') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                New Request
                            </a>
                        @else
                            <a href="{{ route('instructor.dashboard') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Dashboard
                            </a>
                            <a href="{{ route('instructor.active-requests') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Active Requests
                            </a>
                            <a href="{{ route('instructor.history') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                History
                            </a>
                        @endif
                    </div>
                @endauth
            </div>

            <div class="flex items-center">
                @auth
                    <span class="text-sm text-gray-700 mr-4">
                            {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
                        @if(auth()->user()->role === 'instructor')
                            <span class="ml-2 px-2 py-1 text-xs rounded {{ auth()->user()->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ auth()->user()->is_available ? 'Available' : 'Unavailable' }}
                                </span>
                        @endif
                        </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-gray-700 hover:text-gray-900">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-gray-900 mr-4">Login</a>
                    <a href="{{ route('register') }}" class="text-sm text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded">Register</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<!-- Flash Messages -->
@if(session('success'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    </div>
@endif

<!-- Main Content -->
<main class="py-6">
    @yield('content')
</main>

@stack('scripts')
</body>
</html>
