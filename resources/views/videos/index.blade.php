@extends('layouts.app')

@section('title', 'Vidéos - Lumo Plateforme')

@section('content')
<div x-data="{ search: '', module: '' }">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">🎥 Vidéothèque</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Contenu éducatif et de formation</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="flex flex-col sm:flex-row gap-4 mb-6">
        <input x-model="search" type="text" placeholder="Rechercher une vidéo..."
            class="flex-1 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
        <select x-model="module" class="border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
            <option value="">Tous les modules</option>
            <option value="ecoles">Écoles</option>
            <option value="universites">Universités</option>
            <option value="emploi">Emploi</option>
            <option value="ecommerce">E-commerce</option>
        </select>
    </div>

    <!-- Video Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach([
            ['title' => 'Introduction aux mathématiques CE2', 'module' => 'Écoles', 'duration' => '25:30', 'views' => 1250, 'premium' => false],
            ['title' => 'Cours de physique-chimie - Terminale', 'module' => 'Universités', 'duration' => '45:00', 'views' => 890, 'premium' => true],
            ['title' => 'Comment rédiger un CV parfait', 'module' => 'Emploi', 'duration' => '18:45', 'views' => 3400, 'premium' => false],
            ['title' => 'Créer sa boutique en ligne', 'module' => 'E-commerce', 'duration' => '55:20', 'views' => 2100, 'premium' => true],
            ['title' => 'Histoire de l\'Afrique subsaharienne', 'module' => 'Universités', 'duration' => '38:00', 'views' => 760, 'premium' => false],
            ['title' => 'Développement web avec Laravel', 'module' => 'Formation', 'duration' => '1:20:00', 'views' => 4500, 'premium' => true],
        ] as $video)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition border border-gray-100 dark:border-gray-700 overflow-hidden">
            <!-- Thumbnail -->
            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 h-44 flex items-center justify-center relative">
                <span class="text-6xl">🎬</span>
                <div class="absolute bottom-2 right-2 bg-black/70 text-white text-xs px-2 py-1 rounded">
                    {{ $video['duration'] }}
                </div>
                @if($video['premium'])
                <div class="absolute top-2 left-2 bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-1 rounded">
                    ⭐ Premium
                </div>
                @endif
            </div>
            <div class="p-4">
                <span class="text-xs text-indigo-600 dark:text-indigo-400 font-medium">{{ $video['module'] }}</span>
                <h3 class="font-semibold text-gray-900 dark:text-white text-sm mt-1 leading-snug">{{ $video['title'] }}</h3>
                <div class="flex items-center justify-between mt-3">
                    <span class="text-xs text-gray-500 dark:text-gray-400">👁️ {{ number_format($video['views']) }} vues</span>
                    <button class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs px-3 py-1 rounded-lg transition">
                        ▶ Regarder
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
