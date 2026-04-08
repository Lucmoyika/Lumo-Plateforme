@extends('layouts.app')
@section('title', 'Université Félix Houphouët-Boigny - Lumo Plateforme')
@section('content')
<style>
@keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
@keyframes slideInLeft { from { opacity: 0; transform: translateX(-30px); } to { opacity: 1; transform: translateX(0); } }
.fade-in-up { animation: fadeInUp 0.6s ease forwards; }
.card-hover { transition: transform 0.2s, box-shadow 0.2s; }
.card-hover:hover { transform: translateY(-4px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
</style>

<div x-data="{ activeTab: 'facultes' }">
    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-green-700 via-green-600 to-teal-600 rounded-2xl p-8 mb-6 text-white fade-in-up relative overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 25px 25px;"></div>
        <div class="relative flex items-start gap-5">
            <div class="w-18 h-18 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center text-4xl p-3">🎓</div>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold">Université Félix Houphouët-Boigny</h1>
                <p class="text-green-200 mt-1">📍 Abidjan, Côte d'Ivoire · Université publique</p>
                <div class="flex gap-3 mt-3">
                    <a href="#" class="bg-white text-green-700 px-4 py-1.5 rounded-xl text-sm font-semibold hover:bg-green-50 transition">🎓 S'inscrire</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6 fade-in-up" style="animation-delay:0.1s">
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 text-center shadow-sm">
            <p class="text-3xl font-bold text-gray-900 dark:text-white" data-counter="25000">0</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Étudiants</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 text-center shadow-sm">
            <p class="text-3xl font-bold text-gray-900 dark:text-white" data-counter="8">0</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Facultés</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 text-center shadow-sm">
            <p class="text-3xl font-bold text-gray-900 dark:text-white" data-counter="150">0</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Enseignants-chercheurs</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 text-center shadow-sm">
            <p class="text-3xl font-bold text-gray-900 dark:text-white" data-counter="65">0</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Ans d'existence</p>
        </div>
    </div>

    <!-- Tabs -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden fade-in-up" style="animation-delay:0.2s">
        <div class="flex border-b border-gray-100 dark:border-gray-700 overflow-x-auto">
            <button @click="activeTab = 'facultes'" :class="activeTab === 'facultes' ? 'border-b-2 border-green-600 text-green-600' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-3 text-sm font-medium transition whitespace-nowrap">Facultés</button>
            <button @click="activeTab = 'etudiants'" :class="activeTab === 'etudiants' ? 'border-b-2 border-green-600 text-green-600' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-3 text-sm font-medium transition whitespace-nowrap">Étudiants</button>
            <button @click="activeTab = 'cours'" :class="activeTab === 'cours' ? 'border-b-2 border-green-600 text-green-600' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-3 text-sm font-medium transition whitespace-nowrap">Cours</button>
            <button @click="activeTab = 'theses'" :class="activeTab === 'theses' ? 'border-b-2 border-green-600 text-green-600' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-3 text-sm font-medium transition whitespace-nowrap">Thèses</button>
        </div>

        <div class="p-6">
            <!-- Facultés -->
            <div x-show="activeTab === 'facultes'">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="card-hover border border-gray-100 dark:border-gray-700 rounded-2xl p-5">
                        <div class="text-3xl mb-3">⚖️</div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">Droit</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">4 200 étudiants</p>
                        <div class="mt-3 h-1.5 bg-gray-100 dark:bg-gray-700 rounded-full"><div class="h-full bg-blue-500 rounded-full" style="width:70%"></div></div>
                    </div>
                    <div class="card-hover border border-gray-100 dark:border-gray-700 rounded-2xl p-5">
                        <div class="text-3xl mb-3">🔬</div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">Sciences</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">3 800 étudiants</p>
                        <div class="mt-3 h-1.5 bg-gray-100 dark:bg-gray-700 rounded-full"><div class="h-full bg-green-500 rounded-full" style="width:63%"></div></div>
                    </div>
                    <div class="card-hover border border-gray-100 dark:border-gray-700 rounded-2xl p-5">
                        <div class="text-3xl mb-3">📖</div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">Lettres</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">3 500 étudiants</p>
                        <div class="mt-3 h-1.5 bg-gray-100 dark:bg-gray-700 rounded-full"><div class="h-full bg-orange-500 rounded-full" style="width:58%"></div></div>
                    </div>
                    <div class="card-hover border border-gray-100 dark:border-gray-700 rounded-2xl p-5">
                        <div class="text-3xl mb-3">🏥</div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">Médecine</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">2 900 étudiants</p>
                        <div class="mt-3 h-1.5 bg-gray-100 dark:bg-gray-700 rounded-full"><div class="h-full bg-red-500 rounded-full" style="width:48%"></div></div>
                    </div>
                    <div class="card-hover border border-gray-100 dark:border-gray-700 rounded-2xl p-5">
                        <div class="text-3xl mb-3">📊</div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">Économie</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">5 100 étudiants</p>
                        <div class="mt-3 h-1.5 bg-gray-100 dark:bg-gray-700 rounded-full"><div class="h-full bg-purple-500 rounded-full" style="width:85%"></div></div>
                    </div>
                    <div class="card-hover border border-gray-100 dark:border-gray-700 rounded-2xl p-5">
                        <div class="text-3xl mb-3">💻</div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">Informatique</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">5 500 étudiants</p>
                        <div class="mt-3 h-1.5 bg-gray-100 dark:bg-gray-700 rounded-full"><div class="h-full bg-indigo-500 rounded-full" style="width:92%"></div></div>
                    </div>
                </div>
            </div>

            <!-- Étudiants -->
            <div x-show="activeTab === 'etudiants'">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Inscriptions par faculté</h3>
                <div class="space-y-3">
                    <div class="flex items-center gap-3"><span class="text-sm text-gray-700 dark:text-gray-300 w-28 flex-shrink-0">Informatique</span><div class="flex-1 h-6 bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden"><div class="h-full bg-indigo-500 rounded-lg flex items-center justify-end pr-2" style="width:92%"><span class="text-white text-xs font-medium">5 500</span></div></div></div>
                    <div class="flex items-center gap-3"><span class="text-sm text-gray-700 dark:text-gray-300 w-28 flex-shrink-0">Économie</span><div class="flex-1 h-6 bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden"><div class="h-full bg-purple-500 rounded-lg flex items-center justify-end pr-2" style="width:85%"><span class="text-white text-xs font-medium">5 100</span></div></div></div>
                    <div class="flex items-center gap-3"><span class="text-sm text-gray-700 dark:text-gray-300 w-28 flex-shrink-0">Droit</span><div class="flex-1 h-6 bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden"><div class="h-full bg-blue-500 rounded-lg flex items-center justify-end pr-2" style="width:70%"><span class="text-white text-xs font-medium">4 200</span></div></div></div>
                    <div class="flex items-center gap-3"><span class="text-sm text-gray-700 dark:text-gray-300 w-28 flex-shrink-0">Sciences</span><div class="flex-1 h-6 bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden"><div class="h-full bg-green-500 rounded-lg flex items-center justify-end pr-2" style="width:63%"><span class="text-white text-xs font-medium">3 800</span></div></div></div>
                    <div class="flex items-center gap-3"><span class="text-sm text-gray-700 dark:text-gray-300 w-28 flex-shrink-0">Lettres</span><div class="flex-1 h-6 bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden"><div class="h-full bg-orange-500 rounded-lg flex items-center justify-end pr-2" style="width:58%"><span class="text-white text-xs font-medium">3 500</span></div></div></div>
                    <div class="flex items-center gap-3"><span class="text-sm text-gray-700 dark:text-gray-300 w-28 flex-shrink-0">Médecine</span><div class="flex-1 h-6 bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden"><div class="h-full bg-red-500 rounded-lg flex items-center justify-end pr-2" style="width:48%"><span class="text-white text-xs font-medium">2 900</span></div></div></div>
                </div>
            </div>

            <!-- Cours -->
            <div x-show="activeTab === 'cours'">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-750">
                            <tr class="text-left text-xs text-gray-500 dark:text-gray-400">
                                <th class="px-4 py-2 font-medium">Cours</th>
                                <th class="px-4 py-2 font-medium">Faculté</th>
                                <th class="px-4 py-2 font-medium">Crédits</th>
                                <th class="px-4 py-2 font-medium">Étudiants</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-750"><td class="px-4 py-2.5 text-gray-900 dark:text-white">Algorithmique et Structures de données</td><td class="px-4 py-2.5 text-gray-500">Informatique</td><td class="px-4 py-2.5 text-gray-500">6</td><td class="px-4 py-2.5 text-gray-500">420</td></tr>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-750"><td class="px-4 py-2.5 text-gray-900 dark:text-white">Droit des affaires africain</td><td class="px-4 py-2.5 text-gray-500">Droit</td><td class="px-4 py-2.5 text-gray-500">4</td><td class="px-4 py-2.5 text-gray-500">380</td></tr>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-750"><td class="px-4 py-2.5 text-gray-900 dark:text-white">Macroéconomie africaine</td><td class="px-4 py-2.5 text-gray-500">Économie</td><td class="px-4 py-2.5 text-gray-500">5</td><td class="px-4 py-2.5 text-gray-500">510</td></tr>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-750"><td class="px-4 py-2.5 text-gray-900 dark:text-white">Biologie moléculaire</td><td class="px-4 py-2.5 text-gray-500">Sciences</td><td class="px-4 py-2.5 text-gray-500">6</td><td class="px-4 py-2.5 text-gray-500">290</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Thèses -->
            <div x-show="activeTab === 'theses'">
                <div class="space-y-4">
                    <div class="border border-gray-100 dark:border-gray-700 rounded-xl p-4 hover:shadow-sm transition">
                        <h3 class="font-medium text-gray-900 dark:text-white text-sm">Intelligence artificielle appliquée à l'agriculture en Afrique subsaharienne</h3>
                        <p class="text-xs text-gray-500 mt-1">Kofi Mensah · Informatique · Janvier 2025</p>
                        <span class="inline-block mt-2 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 text-xs px-2 py-0.5 rounded-full">Soutenue</span>
                    </div>
                    <div class="border border-gray-100 dark:border-gray-700 rounded-xl p-4 hover:shadow-sm transition">
                        <h3 class="font-medium text-gray-900 dark:text-white text-sm">Impact de la microfinance sur le développement économique en Côte d'Ivoire</h3>
                        <p class="text-xs text-gray-500 mt-1">Fatou Diallo · Économie · Décembre 2024</p>
                        <span class="inline-block mt-2 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 text-xs px-2 py-0.5 rounded-full">Soutenue</span>
                    </div>
                    <div class="border border-gray-100 dark:border-gray-700 rounded-xl p-4 hover:shadow-sm transition">
                        <h3 class="font-medium text-gray-900 dark:text-white text-sm">Droit international et protection des données personnelles en Afrique</h3>
                        <p class="text-xs text-gray-500 mt-1">Amara Traoré · Droit · Mars 2025 (prévu)</p>
                        <span class="inline-block mt-2 bg-yellow-100 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-300 text-xs px-2 py-0.5 rounded-full">En cours</span>
                    </div>
                    <div class="border border-gray-100 dark:border-gray-700 rounded-xl p-4 hover:shadow-sm transition">
                        <h3 class="font-medium text-gray-900 dark:text-white text-sm">Biotechnologies végétales pour la sécurité alimentaire en Afrique de l'Ouest</h3>
                        <p class="text-xs text-gray-500 mt-1">Ngozi Adeyemi · Sciences · Avril 2025 (prévu)</p>
                        <span class="inline-block mt-2 bg-yellow-100 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-300 text-xs px-2 py-0.5 rounded-full">En cours</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function animateCounter(el, target, duration = 2000) {
    let start = 0;
    const step = (timestamp) => {
        if (!start) start = timestamp;
        const progress = Math.min((timestamp - start) / duration, 1);
        el.textContent = Math.floor(progress * target).toLocaleString('fr-FR');
        if (progress < 1) requestAnimationFrame(step);
    };
    requestAnimationFrame(step);
}
document.querySelectorAll('[data-counter]').forEach(el => animateCounter(el, parseInt(el.dataset.counter)));
</script>
@endsection
