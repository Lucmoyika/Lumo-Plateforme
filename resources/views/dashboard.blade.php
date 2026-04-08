@extends('layouts.app')

@section('title', 'Tableau de bord - Lumo Plateforme')

@section('content')
<div x-data="dashboard()" x-init="loadData()">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Tableau de bord</h1>
    <p class="text-gray-500 dark:text-gray-400 mb-8">Bienvenue sur Lumo Plateforme 🌍</p>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 border-l-4 border-indigo-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Écoles</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">3</p>
                </div>
                <div class="text-4xl">🏫</div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Universités</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">5</p>
                </div>
                <div class="text-4xl">🎓</div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Offres d'emploi</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">42</p>
                </div>
                <div class="text-4xl">💼</div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Produits</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">128</p>
                </div>
                <div class="text-4xl">🛒</div>
            </div>
        </div>
    </div>

    <!-- Quick Access -->
    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Accès rapide</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach([
            ['href' => '/schools', 'emoji' => '🏫', 'label' => 'Écoles'],
            ['href' => '/universities', 'emoji' => '🎓', 'label' => 'Universités'],
            ['href' => '/jobs', 'emoji' => '💼', 'label' => 'Emplois'],
            ['href' => '/shop', 'emoji' => '🛒', 'label' => 'Boutique'],
            ['href' => '/chat', 'emoji' => '💬', 'label' => 'Messages'],
            ['href' => '/wallet', 'emoji' => '💳', 'label' => 'Wallet'],
            ['href' => '/videos', 'emoji' => '🎥', 'label' => 'Vidéos'],
            ['href' => '/profile', 'emoji' => '👤', 'label' => 'Profil'],
        ] as $item)
        <a href="{{ $item['href'] }}" class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 text-center hover:shadow-lg hover:-translate-y-1 transition transform border border-gray-100 dark:border-gray-700">
            <div class="text-3xl mb-2">{{ $item['emoji'] }}</div>
            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $item['label'] }}</p>
        </a>
        @endforeach
    </div>
</div>

<script>
function dashboard() {
    return {
        loadData() {}
    }
}
</script>
@endsection
