@extends('layouts.app')

@section('title', 'Boutique - Lumo Plateforme')

@section('content')
<div x-data="{ search: '', cartCount: 0 }">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">🛒 Boutique</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Découvrez nos produits et services</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="bg-indigo-600 text-white rounded-full px-3 py-1 text-sm">
                🛒 <span x-text="cartCount">0</span> article(s)
            </span>
        </div>
    </div>

    <!-- Categories -->
    <div class="flex flex-wrap gap-2 mb-6">
        @foreach(['Tous', 'Livres scolaires', 'Électronique', 'Vêtements', 'Alimentaire', 'Fournitures'] as $cat)
        <button class="px-4 py-2 rounded-full text-sm border border-gray-300 dark:border-gray-600 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition dark:text-gray-300">
            {{ $cat }}
        </button>
        @endforeach
    </div>

    <!-- Search -->
    <div class="mb-6">
        <input x-model="search" type="text" placeholder="Rechercher un produit..."
            class="w-full max-w-md border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach([
            ['name' => 'Manuel de Mathématiques CE2', 'price' => 3500, 'currency' => 'FCFA', 'category' => 'Livres', 'stock' => 45],
            ['name' => 'Calculatrice scientifique', 'price' => 15000, 'currency' => 'FCFA', 'category' => 'Électronique', 'stock' => 12],
            ['name' => 'Dictionnaire Français-Fon', 'price' => 8000, 'currency' => 'FCFA', 'category' => 'Livres', 'stock' => 30],
            ['name' => 'Blouse scolaire bleue', 'price' => 5000, 'currency' => 'FCFA', 'category' => 'Vêtements', 'stock' => 80],
            ['name' => 'Pack fournitures complètes', 'price' => 12000, 'currency' => 'FCFA', 'category' => 'Fournitures', 'stock' => 25],
            ['name' => 'Tablette éducative', 'price' => 85000, 'currency' => 'FCFA', 'category' => 'Électronique', 'stock' => 8],
            ['name' => 'Livre de Physique-Chimie', 'price' => 4500, 'currency' => 'FCFA', 'category' => 'Livres', 'stock' => 60],
            ['name' => 'Cartable résistant', 'price' => 18000, 'currency' => 'FCFA', 'category' => 'Fournitures', 'stock' => 35],
        ] as $product)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="bg-indigo-50 dark:bg-indigo-900/30 h-40 flex items-center justify-center text-6xl">
                🛍️
            </div>
            <div class="p-4">
                <span class="text-xs text-indigo-600 dark:text-indigo-400 font-medium">{{ $product['category'] }}</span>
                <h3 class="font-semibold text-gray-900 dark:text-white text-sm mt-1">{{ $product['name'] }}</h3>
                <div class="flex items-center justify-between mt-3">
                    <span class="text-lg font-bold text-indigo-600 dark:text-indigo-400">
                        {{ number_format($product['price']) }} {{ $product['currency'] }}
                    </span>
                </div>
                <div class="mt-3 flex items-center justify-between">
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $product['stock'] }} en stock</span>
                    <button @click="cartCount++" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs px-3 py-1 rounded-lg transition">
                        Ajouter
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
