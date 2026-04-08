@extends('layouts.app')

@section('title', 'Universités - Lumo Plateforme')

@section('content')
<div x-data="{ search: '' }">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">🎓 Universités</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Établissements d'enseignement supérieur</p>
        </div>
        <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-xl font-medium transition">
            + Ajouter une université
        </button>
    </div>

    <div class="mb-6">
        <input x-model="search" type="text" placeholder="Rechercher une université..."
            class="w-full max-w-md border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach([
            ['name' => 'Université Felix Houphouët-Boigny', 'city' => 'Abidjan', 'country' => 'Côte d\'Ivoire', 'students' => 60000, 'faculties' => 12],
            ['name' => 'Université Cheikh Anta Diop', 'city' => 'Dakar', 'country' => 'Sénégal', 'students' => 80000, 'faculties' => 15],
            ['name' => 'Université de Lomé', 'city' => 'Lomé', 'country' => 'Togo', 'students' => 35000, 'faculties' => 8],
            ['name' => 'Université de Ouagadougou', 'city' => 'Ouagadougou', 'country' => 'Burkina Faso', 'students' => 45000, 'faculties' => 10],
            ['name' => 'Université d\'Antananarivo', 'city' => 'Antananarivo', 'country' => 'Madagascar', 'students' => 25000, 'faculties' => 7],
        ] as $uni)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition p-6 border border-gray-100 dark:border-gray-700">
            <div class="text-4xl mb-4">🎓</div>
            <h3 class="font-bold text-gray-900 dark:text-white text-lg mb-1">{{ $uni['name'] }}</h3>
            <p class="text-gray-500 dark:text-gray-400 text-sm">📍 {{ $uni['city'] }}, {{ $uni['country'] }}</p>
            <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 grid grid-cols-2 gap-2 text-sm">
                <div class="text-gray-500 dark:text-gray-400">👥 {{ number_format($uni['students']) }} étudiants</div>
                <div class="text-gray-500 dark:text-gray-400">🏛️ {{ $uni['faculties'] }} facultés</div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
