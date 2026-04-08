@extends('layouts.app')
@section('title', 'TechAfrique SARL - Lumo Plateforme')
@section('content')
<style>
@keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
.fade-in-up { animation: fadeInUp 0.6s ease forwards; }
</style>

<div x-data="{ activeTab: 'apropos', following: false }">
    <!-- Company Banner -->
    <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-blue-600 rounded-2xl p-8 mb-6 relative overflow-hidden fade-in-up">
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 20px 20px;"></div>
        <div class="relative flex items-start justify-between">
            <div class="flex items-center gap-5">
                <div class="w-20 h-20 bg-white rounded-2xl flex items-center justify-center text-4xl shadow-lg">🏢</div>
                <div class="text-white">
                    <h1 class="text-3xl font-bold">TechAfrique SARL</h1>
                    <p class="text-indigo-200 mt-1">Solutions numériques pour l'Afrique</p>
                </div>
            </div>
            <button @click="following = !following"
                :class="following ? 'bg-white text-indigo-700 border-2 border-white' : 'bg-transparent text-white border-2 border-white hover:bg-white hover:text-indigo-700'"
                class="px-5 py-2 rounded-xl font-medium transition text-sm flex-shrink-0">
                <span x-show="!following">+ Suivre</span>
                <span x-show="following">✓ Suivi</span>
            </button>
        </div>
    </div>

    <!-- Info Bar -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm mb-6 fade-in-up" style="animation-delay:0.1s">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="text-center">
                <p class="text-2xl font-bold text-gray-900 dark:text-white">42</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Offres publiées</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-gray-900 dark:text-white">1 200</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Candidatures</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-gray-900 dark:text-white">85%</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Taux de réponse</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-gray-900 dark:text-white">4.7⭐</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Note employés</p>
            </div>
        </div>
        <div class="flex flex-wrap gap-4 mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 text-sm text-gray-600 dark:text-gray-400">
            <span>🏭 Technologie</span>
            <span>👥 50-200 employés</span>
            <span>📅 Fondée en 2015</span>
            <span>📍 Abidjan, Côte d'Ivoire</span>
        </div>
    </div>

    <!-- Tabs -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden fade-in-up" style="animation-delay:0.2s">
        <div class="flex border-b border-gray-100 dark:border-gray-700">
            <button @click="activeTab = 'apropos'" :class="activeTab === 'apropos' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500 hover:text-gray-700'" class="px-6 py-3 text-sm font-medium transition">À propos</button>
            <button @click="activeTab = 'offres'" :class="activeTab === 'offres' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500 hover:text-gray-700'" class="px-6 py-3 text-sm font-medium transition">Offres d'emploi</button>
            <button @click="activeTab = 'contact'" :class="activeTab === 'contact' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500 hover:text-gray-700'" class="px-6 py-3 text-sm font-medium transition">Contact</button>
        </div>

        <div class="p-6">
            <!-- À propos -->
            <div x-show="activeTab === 'apropos'">
                <h2 class="font-semibold text-gray-900 dark:text-white mb-3">Notre mission</h2>
                <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">TechAfrique SARL est une entreprise technologique de premier plan spécialisée dans le développement de solutions numériques innovantes pour les marchés africains. Fondée en 2015 à Abidjan, nous avons rapidement étendu notre présence dans 8 pays d'Afrique de l'Ouest.</p>
                <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">Notre plateforme dessert plus de 500 000 utilisateurs actifs et nous collaborons avec des entreprises, des institutions éducatives et des gouvernements pour accélérer la transformation numérique du continent.</p>
                <h3 class="font-semibold text-gray-900 dark:text-white mb-3 mt-5">Nos valeurs</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    <div class="bg-indigo-50 dark:bg-indigo-900 rounded-xl p-3 text-sm text-center"><span class="text-xl block mb-1">🌍</span>Impact africain</div>
                    <div class="bg-green-50 dark:bg-green-900 rounded-xl p-3 text-sm text-center"><span class="text-xl block mb-1">🚀</span>Innovation</div>
                    <div class="bg-orange-50 dark:bg-orange-900 rounded-xl p-3 text-sm text-center"><span class="text-xl block mb-1">🤝</span>Collaboration</div>
                </div>
            </div>

            <!-- Offres d'emploi -->
            <div x-show="activeTab === 'offres'">
                <div class="space-y-4">
                    <div class="border border-gray-100 dark:border-gray-700 rounded-xl p-4 hover:border-indigo-300 hover:shadow-sm transition">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="font-medium text-gray-900 dark:text-white">Développeur Full-Stack Senior</h3>
                                <p class="text-sm text-gray-500 mt-1">Abidjan · CDI · 800 000 – 1 200 000 FCFA/mois</p>
                            </div>
                            <a href="/jobs/1" class="bg-indigo-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-indigo-700 transition">Postuler</a>
                        </div>
                        <div class="flex gap-2 mt-2">
                            <span class="text-xs bg-indigo-50 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 px-2 py-0.5 rounded-full">React</span>
                            <span class="text-xs bg-green-50 dark:bg-green-900 text-green-700 dark:text-green-300 px-2 py-0.5 rounded-full">Node.js</span>
                        </div>
                    </div>
                    <div class="border border-gray-100 dark:border-gray-700 rounded-xl p-4 hover:border-indigo-300 hover:shadow-sm transition">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="font-medium text-gray-900 dark:text-white">Chef de Projet IT</h3>
                                <p class="text-sm text-gray-500 mt-1">Abidjan · CDI · 700 000 – 900 000 FCFA/mois</p>
                            </div>
                            <a href="/jobs/1" class="bg-indigo-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-indigo-700 transition">Postuler</a>
                        </div>
                    </div>
                    <div class="border border-gray-100 dark:border-gray-700 rounded-xl p-4 hover:border-indigo-300 hover:shadow-sm transition">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="font-medium text-gray-900 dark:text-white">Designer UX/UI</h3>
                                <p class="text-sm text-gray-500 mt-1">Abidjan · CDI · 500 000 – 700 000 FCFA/mois</p>
                            </div>
                            <a href="/jobs/1" class="bg-indigo-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-indigo-700 transition">Postuler</a>
                        </div>
                    </div>
                    <div class="border border-gray-100 dark:border-gray-700 rounded-xl p-4 hover:border-indigo-300 hover:shadow-sm transition">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="font-medium text-gray-900 dark:text-white">Responsable Marketing Digital</h3>
                                <p class="text-sm text-gray-500 mt-1">Abidjan · CDI · 450 000 – 600 000 FCFA/mois</p>
                            </div>
                            <a href="/jobs/1" class="bg-indigo-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-indigo-700 transition">Postuler</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact -->
            <div x-show="activeTab === 'contact'">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 bg-indigo-100 dark:bg-indigo-900 rounded-xl flex items-center justify-center text-lg flex-shrink-0">📍</div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Adresse</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Cocody Riviera 2, Rue des Jardins, Abidjan, Côte d'Ivoire</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 bg-green-100 dark:bg-green-900 rounded-xl flex items-center justify-center text-lg flex-shrink-0">📞</div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Téléphone</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">+225 07 08 09 10 11</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 bg-blue-100 dark:bg-blue-900 rounded-xl flex items-center justify-center text-lg flex-shrink-0">✉️</div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Email</p>
                                <p class="text-sm text-indigo-600">contact@techafrique.ci</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 bg-purple-100 dark:bg-purple-900 rounded-xl flex items-center justify-center text-lg flex-shrink-0">🌐</div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Site web</p>
                                <a href="#" class="text-sm text-indigo-600 hover:underline">www.techafrique.ci</a>
                            </div>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white mb-3">Réseaux sociaux</p>
                        <div class="flex gap-3">
                            <a href="#" class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white hover:bg-blue-700 transition">in</a>
                            <a href="#" class="w-10 h-10 bg-blue-400 rounded-xl flex items-center justify-center text-white hover:bg-blue-500 transition">𝕏</a>
                            <a href="#" class="w-10 h-10 bg-blue-800 rounded-xl flex items-center justify-center text-white hover:bg-blue-900 transition">f</a>
                            <a href="#" class="w-10 h-10 bg-pink-600 rounded-xl flex items-center justify-center text-white hover:bg-pink-700 transition">ig</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
