@extends('layouts.app')

@section('title', 'Entreprises - Lumo Plateforme')

@section('content')
<div x-data="{ search: '' }">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">🏢 Entreprises</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Annuaire des entreprises africaines</p>
        </div>
        <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-xl font-medium transition">
            + Ajouter mon entreprise
        </button>
    </div>

    <div class="mb-6">
        <input x-model="search" type="text" placeholder="Rechercher une entreprise..."
            class="w-full max-w-md border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach([
            ['name' => 'TechAfrique SARL', 'sector' => 'Technologie', 'city' => 'Abidjan', 'country' => 'Côte d\'Ivoire', 'size' => '50-200', 'jobs' => 5],
            ['name' => 'SenDigital SA', 'sector' => 'Marketing Digital', 'city' => 'Dakar', 'country' => 'Sénégal', 'size' => '10-50', 'jobs' => 3],
            ['name' => 'BTP Afrique', 'sector' => 'Construction', 'city' => 'Lomé', 'country' => 'Togo', 'size' => '200-500', 'jobs' => 8],
            ['name' => 'Finance Plus', 'sector' => 'Finance', 'city' => 'Cotonou', 'country' => 'Bénin', 'size' => '50-200', 'jobs' => 2],
            ['name' => 'AgriCongo', 'sector' => 'Agriculture', 'city' => 'Kinshasa', 'country' => 'RD Congo', 'size' => '100-500', 'jobs' => 12],
            ['name' => 'MediHealth', 'sector' => 'Santé', 'city' => 'Nairobi', 'country' => 'Kenya', 'size' => '50-200', 'jobs' => 6],
        ] as $company)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition p-6 border border-gray-100 dark:border-gray-700">
            <div class="flex items-start gap-4 mb-4">
                <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900 rounded-xl flex items-center justify-center text-2xl">🏢</div>
                <div>
                    <h3 class="font-bold text-gray-900 dark:text-white">{{ $company['name'] }}</h3>
                    <p class="text-indigo-600 dark:text-indigo-400 text-sm">{{ $company['sector'] }}</p>
                </div>
            </div>
            <p class="text-gray-500 dark:text-gray-400 text-sm">📍 {{ $company['city'] }}, {{ $company['country'] }}</p>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">👥 {{ $company['size'] }} employés</p>
            <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <span class="text-sm text-indigo-600 dark:text-indigo-400">💼 {{ $company['jobs'] }} offres d'emploi</span>
                <a href="#" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 text-sm font-medium">Voir →</a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
