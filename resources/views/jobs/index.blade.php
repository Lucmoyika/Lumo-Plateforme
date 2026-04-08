@extends('layouts.app')

@section('title', 'Offres d\'emploi - Lumo Plateforme')

@section('content')
<div x-data="{ search: '', type: '' }">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">💼 Offres d'emploi</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Trouvez votre prochain emploi en Afrique</p>
        </div>
        <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-xl font-medium transition">
            + Publier une offre
        </button>
    </div>

    <!-- Filters -->
    <div class="flex flex-col sm:flex-row gap-4 mb-6">
        <input x-model="search" type="text" placeholder="Rechercher un poste..."
            class="flex-1 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
        <select x-model="type" class="border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
            <option value="">Tous types</option>
            <option value="cdi">CDI</option>
            <option value="cdd">CDD</option>
            <option value="stage">Stage</option>
            <option value="freelance">Freelance</option>
        </select>
    </div>

    <!-- Job Cards -->
    <div class="space-y-4">
        @foreach([
            ['title' => 'Développeur Full Stack Laravel', 'company' => 'TechAfrique', 'city' => 'Abidjan', 'type' => 'CDI', 'salary' => '800 000 - 1 200 000 FCFA', 'tags' => ['Laravel', 'Vue.js', 'MySQL']],
            ['title' => 'Responsable Marketing Digital', 'company' => 'SenDigital', 'city' => 'Dakar', 'type' => 'CDI', 'salary' => '600 000 - 900 000 FCFA', 'tags' => ['SEO', 'Social Media', 'Google Ads']],
            ['title' => 'Comptable Senior', 'company' => 'Finance Plus', 'city' => 'Lomé', 'type' => 'CDD', 'salary' => '500 000 FCFA', 'tags' => ['OHADA', 'Excel', 'SYSCOHADA']],
            ['title' => 'Ingénieur en Génie Civil', 'company' => 'BTP Afrique', 'city' => 'Cotonou', 'type' => 'CDI', 'salary' => 'Négociable', 'tags' => ['AutoCAD', 'Béton armé', 'Topographie']],
        ] as $job)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition p-6 border border-gray-100 dark:border-gray-700">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                <div>
                    <h3 class="font-bold text-gray-900 dark:text-white text-lg">{{ $job['title'] }}</h3>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">🏢 {{ $job['company'] }} · 📍 {{ $job['city'] }}</p>
                    <div class="flex flex-wrap gap-2 mt-3">
                        @foreach($job['tags'] as $tag)
                        <span class="bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 text-xs px-2 py-1 rounded-full">{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="flex flex-col items-start sm:items-end gap-2">
                    <span class="bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 text-xs font-medium px-3 py-1 rounded-full">{{ $job['type'] }}</span>
                    <span class="text-sm text-gray-600 dark:text-gray-400">💰 {{ $job['salary'] }}</span>
                    <button class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-4 py-2 rounded-lg transition mt-1">
                        Postuler →
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
