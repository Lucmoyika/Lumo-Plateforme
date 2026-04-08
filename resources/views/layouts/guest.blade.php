<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{ dark: localStorage.getItem('dark') === 'true' }"
      x-init="$watch('dark', val => localStorage.setItem('dark', val))"
      :class="{ 'dark': dark }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Lumo Plateforme')</title>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#4f46e5">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-indigo-900 to-purple-900 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="/" class="text-3xl font-bold text-white">🌟 Lumo Plateforme</a>
            <p class="text-indigo-200 mt-1 text-sm">Plateforme tout-en-un pour l'Afrique</p>
        </div>

        <!-- Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
            @yield('content')
        </div>

        <!-- Bottom link -->
        <div class="text-center mt-6">
            @yield('footer-link')
        </div>
    </div>
</body>
</html>
