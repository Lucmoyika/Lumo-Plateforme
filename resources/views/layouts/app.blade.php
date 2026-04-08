<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{ dark: localStorage.getItem('dark') === 'true', sidebarOpen: false, notifOpen: false, notifCount: 3 }"
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
                    <a href="/logistics" class="text-indigo-100 hover:text-white hover:bg-indigo-700 px-3 py-2 rounded-md text-sm font-medium transition">🚚 Logistique</a>
                    <a href="/analytics" class="text-indigo-100 hover:text-white hover:bg-indigo-700 px-3 py-2 rounded-md text-sm font-medium transition">📊 Analytique</a>
                </div>

                <!-- Right side -->
                <div class="flex items-center space-x-3">
                    <!-- Notification Bell -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open; notifCount = 0" class="relative text-white p-2 rounded-full hover:bg-indigo-700 transition">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <span x-show="notifCount > 0" x-text="notifCount"
                                class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center font-bold animate-bounce"></span>
                        </button>
                        <div x-show="open" x-transition @click.away="open = false"
                            class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 z-50">
                            <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                                <h3 class="font-semibold text-gray-900 dark:text-white">Notifications</h3>
                                <a href="/notifications" class="text-xs text-indigo-600 hover:underline">Voir tout</a>
                            </div>
                            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                                <div class="p-3 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                                    <p class="text-sm text-gray-800 dark:text-gray-200">💼 Nouvelle offre d'emploi chez TechAfrique</p>
                                    <p class="text-xs text-gray-500 mt-1">Il y a 5 min</p>
                                </div>
                                <div class="p-3 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                                    <p class="text-sm text-gray-800 dark:text-gray-200">🛒 Votre commande #1042 a été expédiée</p>
                                    <p class="text-xs text-gray-500 mt-1">Il y a 1h</p>
                                </div>
                                <div class="p-3 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                                    <p class="text-sm text-gray-800 dark:text-gray-200">🏫 Nouvel élève inscrit à votre école</p>
                                    <p class="text-xs text-gray-500 mt-1">Il y a 3h</p>
                                </div>
                            </div>
                        </div>
                    </div>

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
            <a href="/logistics" class="block text-indigo-100 hover:text-white py-2 text-sm">🚚 Logistique</a>
            <a href="/analytics" class="block text-indigo-100 hover:text-white py-2 text-sm">📊 Analytique</a>
            <a href="/notifications" class="block text-indigo-100 hover:text-white py-2 text-sm">🔔 Notifications</a>
            <a href="/admin" class="block text-indigo-100 hover:text-white py-2 text-sm">⚙️ Admin</a>
            <a href="/profile" class="block text-indigo-100 hover:text-white py-2 text-sm">👤 Profil</a>
            <a href="/settings" class="block text-indigo-100 hover:text-white py-2 text-sm">⚙️ Paramètres</a>
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
