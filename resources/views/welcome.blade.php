@extends('layouts.app')

@section('title', 'Lumo Plateforme - Accueil')

@section('content')
<!-- Hero Section -->
<div class="text-center py-16">
    <h1 class="text-5xl font-extrabold text-gray-900 dark:text-white mb-4">
        🌟 Bienvenue sur <span class="text-indigo-600">Lumo Plateforme</span>
    </h1>
    <p class="text-xl text-gray-600 dark:text-gray-300 mb-8 max-w-2xl mx-auto">
        La plateforme tout-en-un pour l'Afrique : Éducation, Emploi, Commerce, Paiements, Logistique et Communication.
    </p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="/register" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-8 py-3 rounded-xl transition shadow-lg">
            Commencer gratuitement →
        </a>
        <a href="/login" class="border-2 border-indigo-600 text-indigo-600 dark:text-indigo-400 font-semibold px-8 py-3 rounded-xl hover:bg-indigo-50 dark:hover:bg-indigo-900 transition">
            Se connecter
        </a>
    </div>
</div>

<!-- Modules Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-8">
    @foreach([
        ['emoji' => '🏫', 'title' => 'Écoles', 'desc' => 'Gestion scolaire complète : classes, élèves, notes, présences', 'href' => '/schools', 'color' => 'indigo'],
        ['emoji' => '🎓', 'title' => 'Universités', 'desc' => 'Facultés, départements, cours, thèses et étudiants', 'href' => '/universities', 'color' => 'purple'],
        ['emoji' => '💼', 'title' => 'Emploi', 'desc' => 'Offres d\'emploi, candidatures et recrutement', 'href' => '/jobs', 'color' => 'blue'],
        ['emoji' => '🏢', 'title' => 'Entreprises', 'desc' => 'Profils d\'entreprises et offres d\'emploi', 'href' => '/companies', 'color' => 'cyan'],
        ['emoji' => '🛒', 'title' => 'Boutique', 'desc' => 'E-commerce avec produits, commandes et paiements', 'href' => '/shop', 'color' => 'green'],
        ['emoji' => '💬', 'title' => 'Chat', 'desc' => 'Messagerie instantanée et conversations', 'href' => '/chat', 'color' => 'yellow'],
        ['emoji' => '💳', 'title' => 'Wallet', 'desc' => 'Portefeuille électronique et transactions', 'href' => '/wallet', 'color' => 'orange'],
        ['emoji' => '🎥', 'title' => 'Vidéos', 'desc' => 'Bibliothèque de vidéos éducatives et de formation', 'href' => '/videos', 'color' => 'red'],
    ] as $module)
    <a href="{{ $module['href'] }}" class="group bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-xl p-6 transition transform hover:-translate-y-1 border border-gray-100 dark:border-gray-700">
        <div class="text-4xl mb-3">{{ $module['emoji'] }}</div>
        <h3 class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">
            {{ $module['title'] }}
        </h3>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">{{ $module['desc'] }}</p>
    </a>
    @endforeach
</div>

<!-- Stats Section -->
<div class="mt-16 bg-indigo-600 dark:bg-indigo-800 rounded-2xl p-8 text-white text-center">
    <h2 class="text-2xl font-bold mb-6">Lumo en chiffres</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div><div class="text-3xl font-extrabold">500+</div><div class="text-indigo-200 text-sm mt-1">Écoles</div></div>
        <div><div class="text-3xl font-extrabold">50+</div><div class="text-indigo-200 text-sm mt-1">Universités</div></div>
        <div><div class="text-3xl font-extrabold">10K+</div><div class="text-indigo-200 text-sm mt-1">Offres d'emploi</div></div>
        <div><div class="text-3xl font-extrabold">100K+</div><div class="text-indigo-200 text-sm mt-1">Utilisateurs</div></div>
    </div>
</div>
@endsection
