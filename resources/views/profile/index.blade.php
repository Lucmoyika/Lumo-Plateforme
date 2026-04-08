@extends('layouts.app')

@section('title', 'Mon Profil - Lumo Plateforme')

@section('content')
<div class="max-w-3xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">👤 Mon Profil</h1>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-100 dark:border-gray-700 overflow-hidden">
        <!-- Profile Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-8 flex items-center gap-6">
            <div class="w-20 h-20 rounded-full bg-white/20 flex items-center justify-center text-5xl">
                👤
            </div>
            <div>
                <h2 class="text-2xl font-bold text-white">Utilisateur Lumo</h2>
                <p class="text-indigo-200">user@lumo.app</p>
                <span class="mt-2 inline-block bg-white/20 text-white text-xs px-3 py-1 rounded-full">Étudiant</span>
            </div>
        </div>

        <!-- Profile Form -->
        <div class="p-8" x-data="{ saving: false }">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Informations personnelles</h3>
            <form class="space-y-6" @submit.prevent="saving = true; setTimeout(() => saving = false, 1000)">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Prénom</label>
                        <input type="text" value="Jean" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nom</label>
                        <input type="text" value="Kouassi" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                        <input type="email" value="jean@lumo.app" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Téléphone</label>
                        <input type="tel" placeholder="+225 07 XX XX XX XX" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bio</label>
                        <textarea rows="3" placeholder="Parlez de vous..." class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none resize-none"></textarea>
                    </div>
                </div>
                <button type="submit" :disabled="saving" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-3 rounded-xl transition disabled:opacity-50">
                    <span x-show="!saving">Sauvegarder les modifications</span>
                    <span x-show="saving">Sauvegarde...</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Change Password -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-100 dark:border-gray-700 p-8 mt-6">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">🔒 Changer le mot de passe</h3>
        <form class="space-y-4" x-data="{}">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mot de passe actuel</label>
                <input type="password" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nouveau mot de passe</label>
                <input type="password" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-3 rounded-xl transition">
                Changer le mot de passe
            </button>
        </form>
    </div>
</div>
@endsection
