<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Jobdesk Management System - Mechanical Engineering School</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #1e3a8a 0%, #0c4a6e 100%);
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>
<body class="antialiased">
    <div class="gradient-bg min-h-screen flex flex-col">
        <!-- Header -->
        <div class="container mx-auto px-4 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-white">⚙️ Jobdesk Management System</h1>
                    <p class="text-blue-100">Mechanical Engineering School</p>
                </div>
                @auth
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center space-x-2 text-white">
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" 
                             class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="bg-white text-blue-800 font-semibold py-2 px-4 rounded-lg hover:bg-blue-50 transition">
                        Log in
                    </a>
                @endauth
            </div>
        </div>

        <!-- Hero Section -->
        <div class="container mx-auto px-4 py-12 flex-grow">
            <div class="max-w-4xl mx-auto text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-extrabold text-white mb-6">
                    Streamline Instructor <span class="text-blue-300">Jobdesk</span> Management
                </h2>
                <p class="text-xl text-blue-100 max-w-2xl mx-auto">
                    Monitor practical sessions, theoretical classes, production activities, and training services in one unified platform.
                </p>
            </div>

            <!-- Role-Based Dashboard Card -->
            <div class="max-w-2xl mx-auto">
                @guest
                    <div class="bg-white rounded-xl shadow-xl p-8 text-center card-hover">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Welcome!</h3>
                        <p class="text-gray-600 mb-6">Please log in to access the system.</p>
                        <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition">
                            Go to Login
                        </a>
                    </div>
                @else
                    @php
                        $user = Auth::user();
                        if ($user->isSuperadmin()) {
                            $role = 'Head of Study Program (Superadmin)';
                            $dashboardRoute = 'master.instructors.index';
                            $color = 'bg-red-500';
                            $icon = '👑';
                            $description = 'Manage master data: instructors, courses, production, and training services.';
                        } elseif ($user->isAdmin()) {
                            $role = 'K.UPT (Admin)';
                            $dashboardRoute = 'jobdesks.entries.index';
                            $color = 'bg-blue-500';
                            $icon = '📋';
                            $description = 'Record and manage instructor jobdesk entries.';
                        } else {
                            $role = 'Director (User)';
                            $dashboardRoute = 'dashboard';
                            $color = 'bg-green-500';
                            $icon = '📊';
                            $description = 'Monitor all instructor activities with live filters and exports.';
                        }
                    @endphp

                    <div class="bg-white rounded-xl shadow-xl p-8 text-center card-hover">
                        <div class="w-16 h-16 {{ $color }} bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl">{{ $icon }}</span>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Hello, {{ $user->name }}!</h3>
                        <p class="text-gray-600 mb-2">{{ $role }}</p>
                        <p class="text-gray-500 mb-6">{{ $description }}</p>
                        <a href="{{ route($dashboardRoute) }}" class="{{ $color }} hover:{{ str_replace('bg-', 'bg-', $color) . 'opacity-90' }} text-white font-medium py-2 px-6 rounded-lg transition">
                            Go to Your Dashboard
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Features Grid -->
            <div class="mt-20">
                <h3 class="text-2xl font-bold text-white text-center mb-10">System Features</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto">
                    <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-lg p-6 text-center border border-white border-opacity-20">
                        <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-white text-xl">👨‍🏫</span>
                        </div>
                        <h4 class="text-white font-semibold mb-2">Instructor Management</h4>
                        <p class="text-blue-100 text-sm">Add, edit, and track instructor profiles with expertise and status.</p>
                    </div>
                    <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-lg p-6 text-center border border-white border-opacity-20">
                        <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-white text-xl">📚</span>
                        </div>
                        <h4 class="text-white font-semibold mb-2">Activity Tracking</h4>
                        <p class="text-blue-100 text-sm">Log practical, theoretical, production, and training activities by date.</p>
                    </div>
                    <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-lg p-6 text-center border border-white border-opacity-20">
                        <div class="w-12 h-12 bg-amber-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-white text-xl">📈</span>
                        </div>
                        <h4 class="text-white font-semibold mb-2">Monitoring Dashboard</h4>
                        <p class="text-blue-100 text-sm">Real-time charts, filters, and export to Excel/PDF.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="py-6 text-center text-blue-200 text-sm">
            <p>© {{ date('Y') }} Mechanical Engineering School Jobdesk Management System - Created by Nico Prasetyo</p>
        </footer>
    </div>
</body>
</html>