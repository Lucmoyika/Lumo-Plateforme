<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{ dark: localStorage.getItem('dark') === 'true', sidebarOpen: false }"
      x-init="$watch('dark', val => localStorage.setItem('dark', val))"
      :class="{ 'dark': dark }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Lumo Plateforme')</title>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#4f46e5">
    <meta name="description" content="Lumo Plateforme - Éducation, Emploi, Commerce et plus pour l'Afrique">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-white min-h-screen">

    <!-- Navigation -->
    <nav class="bg-indigo-600 dark:bg-indigo-900 shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- Logo -->
                <div class="flex items-center space-x-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-white lg:hidden focus:outline-none">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            <path x-show="sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                    <a href="/" class="flex items-center space-x-2">
                        <span class="text-2xl font-bold text-white">🌟 Lumo</span>
                    </a>
                </div>

                <!-- Desktop Nav Links -->
                <div class="hidden lg:flex items-center space-x-1">
                    <a href="/schools" class="text-indigo-100 hover:text-white hover:bg-indigo-700 px-3 py-2 rounded-md text-sm font-medium transition">🏫 Écoles</a>
                    <a href="/universities" class="text-indigo-100 hover:text-white hover:bg-indigo-700 px-3 py-2 rounded-md text-sm font-medium transition">🎓 Universités</a>
                    <a href="/jobs" class="text-indigo-100 hover:text-white hover:bg-indigo-700 px-3 py-2 rounded-md text-sm font-medium transition">💼 Emplois</a>
                    <a href="/companies" class="text-indigo-100 hover:text-white hover:bg-indigo-700 px-3 py-2 rounded-md text-sm font-medium transition">🏢 Entreprises</a>
                    <a href="/shop" class="text-indigo-100 hover:text-white hover:bg-indigo-700 px-3 py-2 rounded-md text-sm font-medium transition">🛒 Boutique</a>
                    <a href="/chat" class="text-indigo-100 hover:text-white hover:bg-indigo-700 px-3 py-2 rounded-md text-sm font-medium transition">💬 Chat</a>
                    <a href="/wallet" class="text-indigo-100 hover:text-white hover:bg-indigo-700 px-3 py-2 rounded-md text-sm font-medium transition">💳 Wallet</a>
                    <a href="/videos" class="text-indigo-100 hover:text-white hover:bg-indigo-700 px-3 py-2 rounded-md text-sm font-medium transition">🎥 Vidéos</a>
                </div>

                <!-- Right side -->
                <div class="flex items-center space-x-3">
                    <button @click="dark = !dark" class="text-white p-2 rounded-full hover:bg-indigo-700 transition">
                        <svg x-show="!dark" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                        <svg x-show="dark" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </button>
                    <a href="/login" class="bg-white text-indigo-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-50 transition">Connexion</a>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="sidebarOpen" x-transition class="lg:hidden bg-indigo-700 dark:bg-indigo-900 px-4 pb-4">
            <a href="/schools" class="block text-indigo-100 hover:text-white py-2 text-sm">🏫 Écoles</a>
            <a href="/universities" class="block text-indigo-100 hover:text-white py-2 text-sm">🎓 Universités</a>
            <a href="/jobs" class="block text-indigo-100 hover:text-white py-2 text-sm">💼 Emplois</a>
            <a href="/companies" class="block text-indigo-100 hover:text-white py-2 text-sm">🏢 Entreprises</a>
            <a href="/shop" class="block text-indigo-100 hover:text-white py-2 text-sm">🛒 Boutique</a>
            <a href="/chat" class="block text-indigo-100 hover:text-white py-2 text-sm">💬 Chat</a>
            <a href="/wallet" class="block text-indigo-100 hover:text-white py-2 text-sm">💳 Wallet</a>
            <a href="/videos" class="block text-indigo-100 hover:text-white py-2 text-sm">🎥 Vidéos</a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="mb-4 bg-green-100 dark:bg-green-800 border border-green-400 text-green-700 dark:text-green-200 px-4 py-3 rounded relative">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 bg-red-100 dark:bg-red-800 border border-red-400 text-red-700 dark:text-red-200 px-4 py-3 rounded relative">
                {{ session('error') }}
            </div>
        @endif
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 dark:bg-gray-950 text-gray-300 mt-16 py-8">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-sm">© {{ date('Y') }} Lumo Plateforme — Éducation, Emploi, Commerce pour l'Afrique 🌍</p>
        </div>
    </footer>

    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js');
        }
    </script>
</body>
</html>
