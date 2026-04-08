@extends('layouts.app')

@section('title', 'Écoles - Lumo Plateforme')

@section('content')
<div x-data="{ search: '', showModal: false }">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">🏫 Écoles</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Liste des établissements scolaires</p>
        </div>
        <button @click="showModal = true" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-xl font-medium transition">
            + Ajouter une école
        </button>
    </div>

    <!-- Search -->
    <div class="mb-6">
        <input x-model="search" type="text" placeholder="Rechercher une école..."
            class="w-full max-w-md border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
    </div>

    <!-- Schools Grid (static demo) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach([
            ['name' => 'École Primaire Excellence Abidjan', 'city' => 'Abidjan', 'country' => 'Côte d\'Ivoire', 'students' => 450, 'type' => 'Primaire'],
            ['name' => 'Lycée Moderne de Dakar', 'city' => 'Dakar', 'country' => 'Sénégal', 'students' => 1200, 'type' => 'Secondaire'],
            ['name' => 'Collège Notre-Dame de Lomé', 'city' => 'Lomé', 'country' => 'Togo', 'students' => 680, 'type' => 'Collège'],
        ] as $school)
        <div x-show="!search || '{{ strtolower($school['name']) }}'.includes(search.toLowerCase())"
            class="bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition p-6 border border-gray-100 dark:border-gray-700">
            <div class="flex items-start justify-between mb-4">
                <div class="text-4xl">🏫</div>
                <span class="bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-300 text-xs font-medium px-2 py-1 rounded-full">
                    {{ $school['type'] }}
                </span>
            </div>
            <h3 class="font-bold text-gray-900 dark:text-white text-lg mb-1">{{ $school['name'] }}</h3>
            <p class="text-gray-500 dark:text-gray-400 text-sm">📍 {{ $school['city'] }}, {{ $school['country'] }}</p>
            <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <span class="text-sm text-gray-500 dark:text-gray-400">👥 {{ $school['students'] }} élèves</span>
                <a href="#" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 text-sm font-medium">Voir →</a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
