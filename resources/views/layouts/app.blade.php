<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="appShell()"
    x-init="init()"
      :class="{ 'dark': dark }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Lumo Plateforme')</title>
    @stack('head')
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#4f46e5">
    <meta name="description" content="Lumo Plateforme - Éducation, Emploi, Commerce et plus pour l'Afrique">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-white min-h-screen">

    <!-- Navigation -->
    <nav class="bg-indigo-700 dark:bg-slate-950 border-b border-indigo-800/60 dark:border-white/10 shadow-xl sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center gap-4">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-white p-2 rounded-xl hover:bg-white/15 transition focus:outline-none" aria-label="Ouvrir le menu">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            <path x-show="sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                    <a href="/dashboard" class="flex items-center gap-3 rounded-2xl px-2 py-1 text-white transition hover:bg-white/15">
                        <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-white/15 text-lg font-black">L</span>
                        <span class="hidden sm:flex flex-col leading-tight">
                            <span class="text-sm font-semibold uppercase tracking-[0.28em] text-indigo-200">Lumo</span>
                            <span class="text-xs text-slate-300">Plateforme</span>
                        </span>
                    </a>
                    <a x-show="hasAnyRole(['school_admin','school_staff','assistant','teacher','substitute_teacher','student','parent']) || !!currentUser?.school" href="/schools/my" class="inline-flex items-center rounded-2xl border border-white/20 bg-white/10 px-2.5 py-1.5 text-xs font-semibold whitespace-nowrap text-white transition hover:bg-white/20 md:px-3 md:py-2 md:text-sm">{{ __('Mon établissement') }}</a>
                </div>

                <div class="hidden lg:flex flex-1 items-center justify-center gap-1 overflow-visible px-2 scrollbar-none">
                    <div class="relative z-40" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                        <button type="button" class="flex items-center gap-2 rounded-2xl px-3 py-2 text-sm font-semibold text-white transition hover:bg-white/15">
                            <span>{{ __('Espace') }}</span>
                            <svg class="h-4 w-4 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" x-transition x-cloak class="absolute left-0 top-full z-50 mt-3 w-72 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-2xl dark:border-white/10 dark:bg-slate-900">
                            <div class="border-b border-gray-100 px-4 py-3 dark:border-white/10">
                                <p class="text-[11px] uppercase tracking-[0.28em] text-gray-500 dark:text-slate-400">{{ __('Espace') }}</p>
                            </div>
                            <div class="p-2">
                                <a href="/dashboard" class="flex items-center justify-between rounded-xl px-3 py-2.5 text-sm text-gray-800 transition hover:bg-white hover:text-indigo-700 dark:text-slate-100 dark:hover:bg-white/10 dark:hover:text-white">{{ __('Tableau de bord') }}</a>
                                <a href="/profile" class="flex items-center justify-between rounded-xl px-3 py-2.5 text-sm text-gray-800 transition hover:bg-white hover:text-indigo-700 dark:text-slate-100 dark:hover:bg-white/10 dark:hover:text-white">{{ __('Profil') }}</a>
                                <a href="/settings" class="flex items-center justify-between rounded-xl px-3 py-2.5 text-sm text-gray-800 transition hover:bg-white hover:text-indigo-700 dark:text-slate-100 dark:hover:bg-white/10 dark:hover:text-white">{{ __('Paramètres') }}</a>
                                <a href="/notifications" class="flex items-center justify-between rounded-xl px-3 py-2.5 text-sm text-gray-800 transition hover:bg-white hover:text-indigo-700 dark:text-slate-100 dark:hover:bg-white/10 dark:hover:text-white">{{ __('Notifications') }}</a>
                            </div>
                        </div>
                    </div>

                    <div class="relative z-40" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" x-show="hasRole('school_admin') || can('schools.view') || can('universities.view')">
                        <button type="button" class="flex items-center gap-2 rounded-2xl px-3 py-2 text-sm font-semibold text-white transition hover:bg-white/15">
                            <span>{{ __('Éducation') }}</span>
                            <svg class="h-4 w-4 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" x-transition x-cloak class="absolute left-0 top-full z-50 mt-3 w-72 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-2xl dark:border-white/10 dark:bg-slate-900">
                            <div class="border-b border-gray-100 px-4 py-3 dark:border-white/10">
                                <p class="text-[11px] uppercase tracking-[0.28em] text-gray-500 dark:text-slate-400">{{ __('Éducation') }}</p>
                            </div>
                            <div class="p-2">
                                <a x-show="hasAnyRole(['school_admin','school_staff','assistant','teacher','substitute_teacher','student','parent']) || !!currentUser?.school" href="/schools/my" class="block rounded-xl px-3 py-2.5 text-sm text-gray-800 transition hover:bg-white hover:text-indigo-700 dark:text-slate-100 dark:hover:bg-white/10 dark:hover:text-white">{{ __('Mon établissement') }}</a>
                                <a x-show="can('schools.view') || currentUser?.role === 'admin' || currentUser?.role === 'super_admin'" href="/schools" class="block rounded-xl px-3 py-2.5 text-sm text-gray-800 transition hover:bg-white hover:text-indigo-700 dark:text-slate-100 dark:hover:bg-white/10 dark:hover:text-white">{{ __('Écoles') }}</a>
                                <a x-show="can('universities.view') || currentUser?.role === 'admin' || currentUser?.role === 'super_admin'" href="/universities" class="block rounded-xl px-3 py-2.5 text-sm text-gray-800 transition hover:bg-white hover:text-indigo-700 dark:text-slate-100 dark:hover:bg-white/10 dark:hover:text-white">{{ __('Universités') }}</a>
                            </div>
                        </div>
                    </div>

                    <div class="relative z-40" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" x-show="currentUser?.role === 'admin' || currentUser?.role === 'super_admin' || can('jobs.view') || can('companies.view') || can('products.view') || can('orders.view') || can('conversations.view')">
                        <button type="button" class="flex items-center gap-2 rounded-2xl px-3 py-2 text-sm font-semibold text-white transition hover:bg-white/15">
                            <span>{{ __('Business') }}</span>
                            <svg class="h-4 w-4 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" x-transition x-cloak class="absolute left-0 top-full z-50 mt-3 w-72 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-2xl dark:border-white/10 dark:bg-slate-900">
                            <div class="border-b border-gray-100 px-4 py-3 dark:border-white/10">
                                <p class="text-[11px] uppercase tracking-[0.28em] text-gray-500 dark:text-slate-400">{{ __('Business') }}</p>
                            </div>
                            <div class="p-2">
                                <a x-show="can('jobs.view') || currentUser?.role === 'admin' || currentUser?.role === 'super_admin'" href="/jobs" class="block rounded-xl px-3 py-2.5 text-sm text-gray-800 transition hover:bg-white hover:text-indigo-700 dark:text-slate-100 dark:hover:bg-white/10 dark:hover:text-white">{{ __('Emplois') }}</a>
                                <a x-show="can('companies.view') || currentUser?.role === 'admin' || currentUser?.role === 'super_admin'" href="/companies" class="block rounded-xl px-3 py-2.5 text-sm text-gray-800 transition hover:bg-white hover:text-indigo-700 dark:text-slate-100 dark:hover:bg-white/10 dark:hover:text-white">{{ __('Entreprises') }}</a>
                                <a x-show="can('products.view') || can('orders.view') || currentUser?.role === 'admin' || currentUser?.role === 'super_admin'" href="/shop" class="block rounded-xl px-3 py-2.5 text-sm text-gray-800 transition hover:bg-white hover:text-indigo-700 dark:text-slate-100 dark:hover:bg-white/10 dark:hover:text-white">{{ __('Boutique') }}</a>
                                <a x-show="can('conversations.view') || currentUser?.role === 'admin' || currentUser?.role === 'super_admin'" href="/chat" class="block rounded-xl px-3 py-2.5 text-sm text-gray-800 transition hover:bg-white hover:text-indigo-700 dark:text-slate-100 dark:hover:bg-white/10 dark:hover:text-white">{{ __('Chat') }}</a>
                            </div>
                        </div>
                    </div>

                    <div class="relative z-40" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" x-show="currentUser?.role === 'admin' || currentUser?.role === 'super_admin' || can('wallet.view') || can('videos.view') || can('shipments.view')">
                        <button type="button" class="flex items-center gap-2 rounded-2xl px-3 py-2 text-sm font-semibold text-white transition hover:bg-white/15">
                            <span>{{ __('Services') }}</span>
                            <svg class="h-4 w-4 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" x-transition x-cloak class="absolute left-0 top-full z-50 mt-3 w-72 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-2xl dark:border-white/10 dark:bg-slate-900">
                            <div class="border-b border-gray-100 px-4 py-3 dark:border-white/10">
                                <p class="text-[11px] uppercase tracking-[0.28em] text-gray-500 dark:text-slate-400">{{ __('Services') }}</p>
                            </div>
                            <div class="p-2">
                                <a x-show="can('wallet.view') || currentUser?.role === 'admin' || currentUser?.role === 'super_admin'" href="/wallet" class="block rounded-xl px-3 py-2.5 text-sm text-gray-800 transition hover:bg-white hover:text-indigo-700 dark:text-slate-100 dark:hover:bg-white/10 dark:hover:text-white">{{ __('Wallet') }}</a>
                                <a x-show="can('videos.view') || currentUser?.role === 'admin' || currentUser?.role === 'super_admin'" href="/videos" class="block rounded-xl px-3 py-2.5 text-sm text-gray-800 transition hover:bg-white hover:text-indigo-700 dark:text-slate-100 dark:hover:bg-white/10 dark:hover:text-white">{{ __('Vidéos') }}</a>
                                <a x-show="can('shipments.view') || currentUser?.role === 'admin' || currentUser?.role === 'super_admin'" href="/logistics" class="block rounded-xl px-3 py-2.5 text-sm text-gray-800 transition hover:bg-white hover:text-indigo-700 dark:text-slate-100 dark:hover:bg-white/10 dark:hover:text-white">{{ __('Logistique') }}</a>
                            </div>
                        </div>
                    </div>

                    <div class="relative z-40" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" x-show="currentUser?.role === 'admin' || currentUser?.role === 'super_admin' || can('analytics.view') || can('users.view')">
                        <button type="button" class="flex items-center gap-2 rounded-2xl px-3 py-2 text-sm font-semibold text-white transition hover:bg-white/15">
                            <span>{{ __('Administration') }}</span>
                            <svg class="h-4 w-4 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" x-transition x-cloak class="absolute left-0 top-full z-50 mt-3 w-72 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-2xl dark:border-white/10 dark:bg-slate-900">
                            <div class="border-b border-gray-100 px-4 py-3 dark:border-white/10">
                                <p class="text-[11px] uppercase tracking-[0.28em] text-gray-500 dark:text-slate-400">{{ __('Administration') }}</p>
                            </div>
                            <div class="p-2">
                                <a x-show="can('analytics.view') || currentUser?.role === 'admin' || currentUser?.role === 'super_admin'" href="/analytics" class="block rounded-xl px-3 py-2.5 text-sm text-gray-800 transition hover:bg-white hover:text-indigo-700 dark:text-slate-100 dark:hover:bg-white/10 dark:hover:text-white">{{ __('Analytique') }}</a>
                                <a x-show="can('users.view') || currentUser?.role === 'admin' || currentUser?.role === 'super_admin'" href="/admin" class="block rounded-xl px-3 py-2.5 text-sm text-gray-800 transition hover:bg-white hover:text-indigo-700 dark:text-slate-100 dark:hover:bg-white/10 dark:hover:text-white">{{ __('Admin') }}</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2 sm:gap-3 ml-auto">
                    <!-- Notification Bell -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open; notifCount = 0" class="relative p-2 rounded-full text-white hover:bg-white/15 transition" aria-label="Notifications">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <span x-show="notifCount > 0" x-text="notifCount"
                                :aria-label="notifCount + ' nouvelles notifications'"
                                class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center font-bold animate-bounce"></span>
                        </button>
                        <div x-show="open" x-transition x-cloak @click.away="open = false"
                            class="absolute right-0 mt-3 w-80 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-2xl dark:border-white/10 dark:bg-slate-900 z-50">
                            <div class="flex items-center justify-between border-b border-gray-100 px-4 py-4 dark:border-white/10">
                                <h3 class="font-semibold text-gray-900 dark:text-white">{{ __('Notifications') }}</h3>
                                <a href="/notifications" class="text-xs font-medium text-indigo-600 hover:underline dark:text-indigo-300">{{ __('Voir tout') }}</a>
                            </div>
                            <div class="divide-y divide-gray-100 dark:divide-slate-800">
                                <div class="cursor-pointer p-3 hover:bg-gray-50 dark:hover:bg-slate-800">
                                    <p class="text-sm text-gray-800 dark:text-slate-200">💼 Nouvelle offre d'emploi chez TechAfrique</p>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-slate-400">{{ __('Il y a 5 min') }}</p>
                                </div>
                                <div class="cursor-pointer p-3 hover:bg-gray-50 dark:hover:bg-slate-800">
                                    <p class="text-sm text-gray-800 dark:text-slate-200">🛒 Votre commande #1042 a été expédiée</p>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-slate-400">{{ __('Il y a 1h') }}</p>
                                </div>
                                <div class="cursor-pointer p-3 hover:bg-gray-50 dark:hover:bg-slate-800">
                                    <p class="text-sm text-gray-800 dark:text-slate-200">🏫 Nouvel élève inscrit à votre école</p>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-slate-400">{{ __('Il y a 3h') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button @click="dark = !dark" class="p-2 rounded-full text-white hover:bg-white/15 transition">
                        <svg x-show="!dark" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                        <svg x-show="dark" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </button>

                    <template x-if="!isAuthenticated">
                        <a href="/login" class="bg-white text-slate-900 px-4 py-2 rounded-lg text-sm font-medium hover:bg-slate-100 transition">{{ __('Connexion') }}</a>
                    </template>

                    <template x-if="isAuthenticated">
                        <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                            <button type="button" class="flex items-center gap-2 rounded-2xl px-2 py-1.5 text-left text-white transition hover:bg-white/15">
                                <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-white/15 text-sm font-bold uppercase" x-text="(currentUser?.name || 'L').split(' ').slice(0,2).map(v => v[0]?.toUpperCase() || '').join('') || 'L'"></span>
                                <span class="hidden md:flex flex-col leading-tight">
                                    <span class="text-[10px] uppercase tracking-[0.24em] text-slate-300">Compte</span>
                                    <span class="max-w-36 truncate text-sm font-semibold" x-text="currentUser?.name || '{{ __('Profil') }}'"></span>
                                </span>
                                <svg class="hidden sm:block h-4 w-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open" x-transition x-cloak class="absolute right-0 top-full mt-3 w-56 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-2xl z-50 dark:border-white/10 dark:bg-slate-900">
                                <a href="/profile" class="block px-4 py-3 text-sm text-gray-800 transition hover:bg-gray-50 dark:text-slate-100 dark:hover:bg-white/10">{{ __('Profil') }}</a>
                                <a href="/settings" class="block px-4 py-3 text-sm text-gray-800 transition hover:bg-gray-50 dark:text-slate-100 dark:hover:bg-white/10">{{ __('Paramètres') }}</a>
                                <button @click="logout()" class="block w-full px-4 py-3 text-left text-sm font-medium text-rose-600 transition hover:bg-rose-50 dark:text-rose-300 dark:hover:bg-rose-500/10">{{ __('Déconnexion') }}</button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="sidebarOpen" x-transition class="lg:hidden bg-white border-t border-gray-200 px-4 pb-4 dark:bg-slate-950 dark:border-white/10">
            <div class="py-3 text-xs uppercase tracking-[0.24em] text-gray-500 dark:text-slate-400">{{ __('Navigation') }}</div>

            <div class="mb-4 rounded-2xl border border-gray-200 bg-gray-50 overflow-hidden dark:border-white/10 dark:bg-white/5">
                <div class="px-4 py-3 border-b border-gray-200 dark:border-white/10">
                    <p class="text-xs uppercase tracking-[0.24em] text-gray-500 dark:text-slate-400">{{ __('Espace') }}</p>
                </div>
                <a href="/dashboard" class="block px-4 py-3 text-sm text-gray-800 hover:bg-white hover:text-indigo-700 dark:text-slate-100 dark:hover:bg-white/10 dark:hover:text-white">{{ __('Tableau de bord') }}</a>
                <a href="/profile" class="block px-4 py-3 text-sm text-gray-800 hover:bg-white hover:text-indigo-700 dark:text-slate-100 dark:hover:bg-white/10 dark:hover:text-white">{{ __('Profil') }}</a>
                <a href="/settings" class="block px-4 py-3 text-sm text-gray-800 hover:bg-white hover:text-indigo-700 dark:text-slate-100 dark:hover:bg-white/10 dark:hover:text-white">{{ __('Paramètres') }}</a>
                <a href="/notifications" class="block px-4 py-3 text-sm text-gray-800 hover:bg-white hover:text-indigo-700 dark:text-slate-100 dark:hover:bg-white/10 dark:hover:text-white">{{ __('Notifications') }}</a>
            </div>

            <div class="mb-4 rounded-2xl border border-gray-200 bg-gray-50 overflow-hidden dark:border-white/10 dark:bg-white/5">
                <div class="px-4 py-3 border-b border-gray-200 dark:border-white/10">
                    <p class="text-xs uppercase tracking-[0.24em] text-gray-500 dark:text-slate-400">{{ __('Éducation') }}</p>
                </div>
                <a x-show="hasAnyRole(['school_admin','school_staff','assistant','teacher','substitute_teacher','student','parent']) || !!currentUser?.school" href="/schools/my" class="block px-4 py-3 text-sm text-gray-800 hover:bg-white hover:text-indigo-700 dark:text-slate-100 dark:hover:bg-white/10 dark:hover:text-white">{{ __('Mon établissement') }}</a>
                <a x-show="currentUser?.role === 'admin' || currentUser?.role === 'super_admin' || can('schools.view')" href="/schools" class="block px-4 py-3 text-sm text-gray-800 hover:bg-white hover:text-indigo-700 dark:text-slate-100 dark:hover:bg-white/10 dark:hover:text-white">{{ __('Écoles') }}</a>
                <a x-show="currentUser?.role === 'admin' || currentUser?.role === 'super_admin' || can('universities.view')" href="/universities" class="block px-4 py-3 text-sm text-gray-800 hover:bg-white hover:text-indigo-700 dark:text-slate-100 dark:hover:bg-white/10 dark:hover:text-white">{{ __('Universités') }}</a>
            </div>

            <div class="mb-4 rounded-2xl border border-gray-200 bg-gray-50 overflow-hidden dark:border-white/10 dark:bg-white/5">
                <div class="px-4 py-3 border-b border-gray-200 dark:border-white/10">
                    <p class="text-xs uppercase tracking-[0.24em] text-gray-500 dark:text-slate-400">{{ __('Business') }}</p>
                </div>
                <a x-show="currentUser?.role === 'admin' || currentUser?.role === 'super_admin' || can('jobs.view')" href="/jobs" class="block px-4 py-3 text-sm text-gray-800 hover:bg-white hover:text-indigo-700 dark:text-slate-100 dark:hover:bg-white/10 dark:hover:text-white">{{ __('Emplois') }}</a>
                <a x-show="currentUser?.role === 'admin' || currentUser?.role === 'super_admin' || can('companies.view')" href="/companies" class="block px-4 py-3 text-sm text-gray-800 hover:bg-white hover:text-indigo-700 dark:text-slate-100 dark:hover:bg-white/10 dark:hover:text-white">{{ __('Entreprises') }}</a>
                <a x-show="currentUser?.role === 'admin' || currentUser?.role === 'super_admin' || can('products.view') || can('orders.view')" href="/shop" class="block px-4 py-3 text-sm text-gray-800 hover:bg-white hover:text-indigo-700 dark:text-slate-100 dark:hover:bg-white/10 dark:hover:text-white">{{ __('Boutique') }}</a>
                <a x-show="currentUser?.role === 'admin' || currentUser?.role === 'super_admin' || can('conversations.view')" href="/chat" class="block px-4 py-3 text-sm text-gray-800 hover:bg-white hover:text-indigo-700 dark:text-slate-100 dark:hover:bg-white/10 dark:hover:text-white">{{ __('Chat') }}</a>
            </div>

            <div class="mb-4 rounded-2xl border border-gray-200 bg-gray-50 overflow-hidden dark:border-white/10 dark:bg-white/5">
                <div class="px-4 py-3 border-b border-gray-200 dark:border-white/10">
                    <p class="text-xs uppercase tracking-[0.24em] text-gray-500 dark:text-slate-400">{{ __('Services') }}</p>
                </div>
                <a x-show="currentUser?.role === 'admin' || currentUser?.role === 'super_admin' || can('wallet.view')" href="/wallet" class="block px-4 py-3 text-sm text-gray-800 hover:bg-white hover:text-indigo-700 dark:text-slate-100 dark:hover:bg-white/10 dark:hover:text-white">{{ __('Wallet') }}</a>
                <a x-show="currentUser?.role === 'admin' || currentUser?.role === 'super_admin' || can('videos.view')" href="/videos" class="block px-4 py-3 text-sm text-gray-800 hover:bg-white hover:text-indigo-700 dark:text-slate-100 dark:hover:bg-white/10 dark:hover:text-white">{{ __('Vidéos') }}</a>
                <a x-show="currentUser?.role === 'admin' || currentUser?.role === 'super_admin' || can('shipments.view')" href="/logistics" class="block px-4 py-3 text-sm text-gray-800 hover:bg-white hover:text-indigo-700 dark:text-slate-100 dark:hover:bg-white/10 dark:hover:text-white">{{ __('Logistique') }}</a>
            </div>

            <div class="mb-4 rounded-2xl border border-gray-200 bg-gray-50 overflow-hidden dark:border-white/10 dark:bg-white/5">
                <div class="px-4 py-3 border-b border-gray-200 dark:border-white/10">
                    <p class="text-xs uppercase tracking-[0.24em] text-gray-500 dark:text-slate-400">{{ __('Administration') }}</p>
                </div>
                <a x-show="currentUser?.role === 'admin' || currentUser?.role === 'super_admin' || can('analytics.view')" href="/analytics" class="block px-4 py-3 text-sm text-gray-800 hover:bg-white hover:text-indigo-700 dark:text-slate-100 dark:hover:bg-white/10 dark:hover:text-white">{{ __('Analytique') }}</a>
                <a x-show="currentUser?.role === 'admin' || currentUser?.role === 'super_admin' || can('users.view')" href="/admin" class="block px-4 py-3 text-sm text-gray-800 hover:bg-white hover:text-indigo-700 dark:text-slate-100 dark:hover:bg-white/10 dark:hover:text-white">{{ __('Admin') }}</a>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-gray-50 overflow-hidden dark:border-white/10 dark:bg-white/5">
                <div class="px-4 py-3 border-b border-gray-200 dark:border-white/10">
                    <p class="text-xs uppercase tracking-[0.24em] text-gray-500 dark:text-slate-400">{{ __('Compte') }}</p>
                </div>
                <a x-show="hasAnyRole(['school_admin','school_staff','assistant','teacher','substitute_teacher','student','parent']) || !!currentUser?.school" href="/schools/my" class="block px-4 py-3 text-sm text-gray-800 hover:bg-white hover:text-indigo-700 dark:text-slate-100 dark:hover:bg-white/10 dark:hover:text-white">{{ __('Mon établissement') }}</a>
                <button x-show="isAuthenticated" @click="logout()" class="block w-full px-4 py-3 text-left text-sm font-medium text-rose-600 hover:bg-rose-50 dark:text-rose-300 dark:hover:bg-rose-500/10">{{ __('Déconnexion') }}</button>
            </div>
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
            <p class="text-sm">{{ __('© :year Lumo Plateforme — Éducation, Emploi, Commerce pour l\'Afrique 🌍', ['year' => date('Y')]) }}</p>
        </div>
    </footer>

    <script>
        function appShell() {
            return {
                dark: localStorage.getItem('dark') === 'true',
                sidebarOpen: false,
                notifOpen: false,
                notifCount: 3,
                token: localStorage.getItem('token') || null,
                currentUser: null,
                permissions: [],
                navGroups: [],
                isAuthenticated: false,
                isAdmin: false,
                inactivityTimeoutMs: 10 * 60 * 1000,
                inactivityTimer: null,
                lastActivityTick: 0,
                userCacheTtlMs: 5 * 60 * 1000,
                init() {
                    this.$watch('dark', val => localStorage.setItem('dark', val));

                    // Valider le token stocké
                    if (this.token && typeof this.token !== 'string') {
                        localStorage.removeItem('token');
                        this.token = null;
                    }

                    const cachedUser = localStorage.getItem('user');
                    if (cachedUser) {
                        try {
                            this.currentUser = JSON.parse(cachedUser);
                            
                            // Validation rapide de la structure utilisateur
                            if (!this.currentUser.id || typeof this.currentUser.role !== 'string') {
                                throw new Error('Structure utilisateur invalide');
                            }
                        } catch (error) {
                            console.warn('Cache utilisateur invalide:', error.message);
                            localStorage.removeItem('user');
                            this.currentUser = null;
                        }
                    }

                    this.isAuthenticated = !!this.token;
                    this.refreshAccessState();

                    if (this.isAuthenticated) {
                        this.initInactivityTracking();

                        if (this.isUserCacheFresh()) {
                            // Cache frais: charger en arrière-plan avec timeout
                            setTimeout(() => this.loadCurrentUser(true), 1500);
                        } else {
                            // Cache expiré ou absent: charger immédiatement
                            this.loadCurrentUser();
                        }
                    }
                },
                isUserCacheFresh() {
                    const cachedAt = Number(localStorage.getItem('user_cached_at') || '0');
                    if (!cachedAt) {
                        return false;
                    }

                    return (Date.now() - cachedAt) < this.userCacheTtlMs;
                },
                refreshAccessState() {
                    this.isAdmin = this.currentUser?.role === 'super_admin';
                    const rolePermissions = (this.currentUser?.roles ?? []).flatMap(role => (role.permissions ?? []).map(permission => permission.name));
                    const directPermissions = (this.currentUser?.permissions ?? []).map(permission => permission.name);
                    this.permissions = [...new Set([...rolePermissions, ...directPermissions])];
                    
                    // Valider que les rôles et permissions sont valides au format
                    if (this.currentUser && typeof this.currentUser.role !== 'string') {
                        console.warn('Données utilisateur potentiellement corrompues. Déconnexion.');
                        this.logout(false);
                    }
                },
                can(permission) {
                    // Les vérifications côté client sont UNI pour l'UX seulement
                    // Les vrais contrôles sont toujours côté serveur
                    if (!permission || typeof permission !== 'string') {
                        return false;
                    }

                    return this.isAdmin || this.permissions.includes(permission);
                },
                hasRole(roleName) {
                    if (!roleName || typeof roleName !== 'string') {
                        return false;
                    }

                    if (this.currentUser?.role === roleName) {
                        return true;
                    }

                    return Array.isArray(this.currentUser?.roles) && this.currentUser.roles.some((role) => role?.name === roleName);
                },
                hasAnyRole(roleNames) {
                    if (!Array.isArray(roleNames) || roleNames.length === 0) {
                        return false;
                    }

                    return roleNames.some((roleName) => this.hasRole(roleName));
                },
                initInactivityTracking() {
                    const activityEvents = ['click', 'keydown', 'mousemove', 'touchstart', 'scroll'];

                    const onActivity = () => {
                        const now = Date.now();
                        if (now - this.lastActivityTick < 1000) {
                            return;
                        }

                        this.lastActivityTick = now;
                        this.resetInactivityTimer();
                    };

                    if (!window.__lumoActivityHandlerAttached) {
                        window.__lumoActivityHandler = onActivity;
                        activityEvents.forEach((eventName) => {
                            window.addEventListener(eventName, window.__lumoActivityHandler, { passive: true });
                        });

                        window.__lumoActivityHandlerAttached = true;
                    }

                    this.resetInactivityTimer();
                },
                resetInactivityTimer() {
                    if (!this.isAuthenticated) {
                        return;
                    }

                    if (this.inactivityTimer) {
                        clearTimeout(this.inactivityTimer);
                    }

                    this.inactivityTimer = setTimeout(() => {
                        this.handleInactivityLogout();
                    }, this.inactivityTimeoutMs);
                },
                stopInactivityTracking() {
                    if (this.inactivityTimer) {
                        clearTimeout(this.inactivityTimer);
                        this.inactivityTimer = null;
                    }

                    if (window.__lumoActivityHandlerAttached && window.__lumoActivityHandler) {
                        ['click', 'keydown', 'mousemove', 'touchstart', 'scroll'].forEach((eventName) => {
                            window.removeEventListener(eventName, window.__lumoActivityHandler);
                        });

                        window.__lumoActivityHandler = null;
                        window.__lumoActivityHandlerAttached = false;
                    }
                },
                handleInactivityLogout() {
                    if (!this.isAuthenticated) {
                        return;
                    }

                    alert('Session expirée après 10 minutes d\'inactivité. Veuillez vous reconnecter.');
                    this.logout();
                },
                async loadCurrentUser(silent = false) {
                    try {
                        if (!this.token) {
                            throw new Error('Pas de token');
                        }

                        const response = await fetch('/api/auth/me', {
                            headers: {
                                'Accept': 'application/json',
                                'Authorization': `Bearer ${this.token}`,
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        const payload = await response.json();

                        if (!response.ok || !payload.success || !payload.data) {
                            throw new Error('Session invalide ou expirée');
                        }

                        const user = payload.data;

                        // Validation stricte des données utilisateur
                        if (!user.id || typeof user.role !== 'string' || !Array.isArray(user.roles) || !Array.isArray(user.permissions)) {
                            throw new Error('Données utilisateur invalides');
                        }

                        this.currentUser = user;
                        this.isAuthenticated = true;
                        this.refreshAccessState();
                        localStorage.setItem('user', JSON.stringify(this.currentUser));
                        localStorage.setItem('user_cached_at', String(Date.now()));
                    } catch (error) {
                        console.error('Erreur chargement utilisateur:', error.message);
                        if (!silent) {
                            this.logout(false);
                        }
                    }
                },
                async logout(redirect = true) {
                    try {
                        if (this.token) {
                            const csrfToken = document.querySelector('meta[name=csrf-token]')?.content;
                            if (!csrfToken) {
                                console.warn('CSRF token manquant');
                            }

                            await fetch('/api/auth/logout', {
                                method: 'POST',
                                headers: {
                                    'Accept': 'application/json',
                                    'Authorization': `Bearer ${this.token}`,
                                    'X-CSRF-TOKEN': csrfToken || '',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({})
                            });
                        }
                    } catch (error) {
                        console.error('Erreur déconnexion serveur:', error.message);
                        // Continuer avec le nettoyage client même si erreur serveur
                    }

                    // Nettoyage complet du client
                    localStorage.removeItem('token');
                    localStorage.removeItem('user');
                    localStorage.removeItem('user_cached_at');
                    sessionStorage.clear();
                    
                    this.stopInactivityTracking();
                    this.token = null;
                    this.currentUser = null;
                    this.permissions = [];
                    this.isAuthenticated = false;
                    this.isAdmin = false;
                    this.sidebarOpen = false;

                    if (redirect) {
                        // Attendre un peu que le serveur traite la déconnexion
                        setTimeout(() => {
                            window.location.href = '/login';
                        }, 300);
                    }
                }
            }
        }

        if ('serviceWorker' in navigator) {
            const isLocalhost = ['localhost', '127.0.0.1'].includes(window.location.hostname);

            if (isLocalhost) {
                navigator.serviceWorker.getRegistrations().then((registrations) => {
                    registrations.forEach((registration) => registration.unregister());
                });
            } else {
                navigator.serviceWorker.register('/sw.js');
            }
        }
    </script>
</body>
</html>