@extends('layouts.app')

@section('title', 'Paramètres - Lumo Plateforme')

@section('content')
<div class="max-w-3xl mx-auto" x-data="{ activeTab: 'general', dark: localStorage.getItem('dark') === 'true', locale: 'fr' }">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">⚙️ Paramètres</h1>

    <div class="flex flex-col md:flex-row gap-6">
        <!-- Sidebar Tabs -->
        <div class="md:w-48 flex-shrink-0">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-100 dark:border-gray-700 p-2">
                @foreach([
                    ['id' => 'general', 'label' => '🌐 Général'],
                    ['id' => 'notifications', 'label' => '🔔 Notifications'],
                    ['id' => 'privacy', 'label' => '🔒 Confidentialité'],
                    ['id' => 'appearance', 'label' => '🎨 Apparence'],
                ] as $tab)
                <button @click="activeTab = '{{ $tab['id'] }}'"
                    :class="activeTab === '{{ $tab['id'] }}' ? 'bg-indigo-50 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700'"
                    class="w-full text-left px-4 py-3 rounded-lg text-sm font-medium transition mb-1">
                    {{ $tab['label'] }}
                </button>
                @endforeach
            </div>
        </div>

        <!-- Tab Content -->
        <div class="flex-1 bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-100 dark:border-gray-700 p-8">
            <!-- General -->
            <div x-show="activeTab === 'general'">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Paramètres généraux</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Langue</label>
                        <select x-model="locale" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                            <option value="fr">Français</option>
                            <option value="en">English</option>
                            <option value="ar">العربية</option>
                            <option value="sw">Kiswahili</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fuseau horaire</label>
                        <select class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                            <option>UTC+0 - Abidjan, Dakar</option>
                            <option>UTC+1 - Lagos, Kinshasa</option>
                            <option>UTC+2 - Johannesburg, Nairobi</option>
                            <option>UTC+3 - Addis-Abeba</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Devise</label>
                        <select class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                            <option>FCFA - Franc CFA</option>
                            <option>NGN - Naira nigérian</option>
                            <option>KES - Shilling kenyan</option>
                            <option>ZAR - Rand sud-africain</option>
                        </select>
                    </div>
                    <button class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-3 rounded-xl transition mt-2">
                        Sauvegarder
                    </button>
                </div>
            </div>

            <!-- Notifications -->
            <div x-show="activeTab === 'notifications'">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Préférences de notifications</h2>
                <div class="space-y-4">
                    @foreach(['Notifications par email', 'Notifications push', 'Alertes SMS', 'Résumé hebdomadaire'] as $notif)
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-gray-700 dark:text-gray-300 text-sm">{{ $notif }}</span>
                        <button x-data="{ on: true }" @click="on = !on" :class="on ? 'bg-indigo-600' : 'bg-gray-300 dark:bg-gray-600'" class="relative w-12 h-6 rounded-full transition-colors duration-200">
                            <span :class="on ? 'translate-x-6' : 'translate-x-1'" class="absolute top-1 left-0 w-4 h-4 bg-white rounded-full transition-transform duration-200"></span>
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Privacy -->
            <div x-show="activeTab === 'privacy'">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Confidentialité</h2>
                <div class="space-y-4">
                    @foreach(['Profil public', 'Afficher les activités', 'Partager les statistiques'] as $priv)
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-gray-700 dark:text-gray-300 text-sm">{{ $priv }}</span>
                        <button x-data="{ on: false }" @click="on = !on" :class="on ? 'bg-indigo-600' : 'bg-gray-300 dark:bg-gray-600'" class="relative w-12 h-6 rounded-full transition-colors duration-200">
                            <span :class="on ? 'translate-x-6' : 'translate-x-1'" class="absolute top-1 left-0 w-4 h-4 bg-white rounded-full transition-transform duration-200"></span>
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Appearance -->
            <div x-show="activeTab === 'appearance'">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Apparence</h2>
                <div class="space-y-4">
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-gray-700 dark:text-gray-300 text-sm">Mode sombre</span>
                        <button @click="dark = !dark; localStorage.setItem('dark', dark); document.documentElement.classList.toggle('dark', dark)"
                            :class="dark ? 'bg-indigo-600' : 'bg-gray-300 dark:bg-gray-600'" class="relative w-12 h-6 rounded-full transition-colors duration-200">
                            <span :class="dark ? 'translate-x-6' : 'translate-x-1'" class="absolute top-1 left-0 w-4 h-4 bg-white rounded-full transition-transform duration-200"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
