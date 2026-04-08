@extends('layouts.app')
@section('title', 'Analytique - Lumo Plateforme')
@section('content')
<style>
@keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
@keyframes slideInLeft { from { opacity: 0; transform: translateX(-30px); } to { opacity: 1; transform: translateX(0); } }
@keyframes growBar { from { height: 0; } to { } }
.fade-in-up { animation: fadeInUp 0.6s ease forwards; }
.bar-grow { animation: growBar 1.2s ease forwards; }
</style>

<div x-data="{ period: '30j' }">
    <!-- Header -->
    <div class="mb-8 fade-in-up">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">📊 Analytique</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">Vue d'ensemble de la performance de la plateforme</p>
    </div>

    <!-- Period Selector -->
    <div class="flex gap-2 mb-6 fade-in-up" style="animation-delay:0.1s">
        <template x-for="p in ['7j','30j','90j','1an']">
            <button @click="period = p"
                :class="period === p ? 'bg-indigo-600 text-white shadow-lg' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-indigo-50'"
                class="px-4 py-2 rounded-xl text-sm font-medium transition"
                x-text="p"></button>
        </template>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-2xl p-5 text-white shadow-lg fade-in-up" style="animation-delay:0.1s">
            <p class="text-indigo-200 text-sm">Utilisateurs</p>
            <p class="text-3xl font-bold mt-1" data-counter="12847">0</p>
            <p class="text-indigo-200 text-xs mt-2">↑ +8.3% ce mois</p>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-green-700 rounded-2xl p-5 text-white shadow-lg fade-in-up" style="animation-delay:0.2s">
            <p class="text-green-200 text-sm">Revenus</p>
            <p class="text-2xl font-bold mt-1" data-counter-fcfa="45200000">0</p>
            <p class="text-green-200 text-xs mt-2">↑ +23% ce mois</p>
        </div>
        <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl p-5 text-white shadow-lg fade-in-up" style="animation-delay:0.3s">
            <p class="text-purple-200 text-sm">Croissance</p>
            <p class="text-3xl font-bold mt-1">+23%</p>
            <p class="text-purple-200 text-xs mt-2">↑ Objectif atteint</p>
        </div>
        <div class="bg-gradient-to-br from-orange-500 to-orange-700 rounded-2xl p-5 text-white shadow-lg fade-in-up" style="animation-delay:0.4s">
            <p class="text-orange-200 text-sm">Transactions</p>
            <p class="text-3xl font-bold mt-1" data-counter="3891">0</p>
            <p class="text-orange-200 text-xs mt-2">↑ +15.7% ce mois</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Bar Chart -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm fade-in-up" style="animation-delay:0.3s">
            <h2 class="font-semibold text-gray-900 dark:text-white mb-1">Revenus mensuels (FCFA)</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-6">Évolution sur 12 mois</p>
            <div class="flex items-end justify-around h-40 gap-1">
                <div class="flex flex-col items-center gap-1">
                    <span class="text-xs text-gray-400">2.8M</span>
                    <div class="w-8 bg-indigo-200 dark:bg-indigo-900 rounded-t-lg bar-grow" style="height:45%"></div>
                    <span class="text-xs text-gray-500">Jan</span>
                </div>
                <div class="flex flex-col items-center gap-1">
                    <span class="text-xs text-gray-400">3.2M</span>
                    <div class="w-8 bg-indigo-300 dark:bg-indigo-800 rounded-t-lg bar-grow" style="height:52%;animation-delay:0.1s"></div>
                    <span class="text-xs text-gray-500">Fév</span>
                </div>
                <div class="flex flex-col items-center gap-1">
                    <span class="text-xs text-gray-400">2.9M</span>
                    <div class="w-8 bg-indigo-300 dark:bg-indigo-800 rounded-t-lg bar-grow" style="height:47%;animation-delay:0.2s"></div>
                    <span class="text-xs text-gray-500">Mar</span>
                </div>
                <div class="flex flex-col items-center gap-1">
                    <span class="text-xs text-gray-400">4.1M</span>
                    <div class="w-8 bg-indigo-400 dark:bg-indigo-700 rounded-t-lg bar-grow" style="height:66%;animation-delay:0.3s"></div>
                    <span class="text-xs text-gray-500">Avr</span>
                </div>
                <div class="flex flex-col items-center gap-1">
                    <span class="text-xs text-gray-400">3.8M</span>
                    <div class="w-8 bg-indigo-400 dark:bg-indigo-700 rounded-t-lg bar-grow" style="height:61%;animation-delay:0.4s"></div>
                    <span class="text-xs text-gray-500">Mai</span>
                </div>
                <div class="flex flex-col items-center gap-1">
                    <span class="text-xs text-gray-400">4.7M</span>
                    <div class="w-8 bg-indigo-500 dark:bg-indigo-600 rounded-t-lg bar-grow" style="height:76%;animation-delay:0.5s"></div>
                    <span class="text-xs text-gray-500">Jun</span>
                </div>
                <div class="flex flex-col items-center gap-1">
                    <span class="text-xs text-gray-400">5.1M</span>
                    <div class="w-8 bg-indigo-500 dark:bg-indigo-600 rounded-t-lg bar-grow" style="height:82%;animation-delay:0.6s"></div>
                    <span class="text-xs text-gray-500">Jul</span>
                </div>
                <div class="flex flex-col items-center gap-1">
                    <span class="text-xs text-gray-400">4.9M</span>
                    <div class="w-8 bg-indigo-600 dark:bg-indigo-500 rounded-t-lg bar-grow" style="height:79%;animation-delay:0.7s"></div>
                    <span class="text-xs text-gray-500">Aoû</span>
                </div>
                <div class="flex flex-col items-center gap-1">
                    <span class="text-xs text-gray-400">5.6M</span>
                    <div class="w-8 bg-indigo-600 dark:bg-indigo-500 rounded-t-lg bar-grow" style="height:90%;animation-delay:0.8s"></div>
                    <span class="text-xs text-gray-500">Sep</span>
                </div>
                <div class="flex flex-col items-center gap-1">
                    <span class="text-xs text-gray-400">5.3M</span>
                    <div class="w-8 bg-indigo-700 dark:bg-indigo-400 rounded-t-lg bar-grow" style="height:86%;animation-delay:0.9s"></div>
                    <span class="text-xs text-gray-500">Oct</span>
                </div>
                <div class="flex flex-col items-center gap-1">
                    <span class="text-xs text-gray-400">6.0M</span>
                    <div class="w-8 bg-purple-600 rounded-t-lg bar-grow" style="height:97%;animation-delay:1s"></div>
                    <span class="text-xs text-gray-500">Nov</span>
                </div>
                <div class="flex flex-col items-center gap-1">
                    <span class="text-xs text-gray-400">6.2M</span>
                    <div class="w-8 bg-gradient-to-t from-indigo-600 to-purple-500 rounded-t-lg bar-grow" style="height:100%;animation-delay:1.1s"></div>
                    <span class="text-xs text-gray-500">Déc</span>
                </div>
            </div>
        </div>

        <!-- Activity Feed -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm fade-in-up" style="animation-delay:0.4s">
            <h2 class="font-semibold text-gray-900 dark:text-white mb-4">🔴 Activité récente</h2>
            <div class="space-y-3">
                <div class="flex items-start gap-3">
                    <div class="w-2 h-2 bg-green-400 rounded-full mt-2 flex-shrink-0 animate-pulse"></div>
                    <div>
                        <p class="text-sm text-gray-800 dark:text-gray-200">Nouvelle inscription — Kouassi A.</p>
                        <p class="text-xs text-gray-400">Il y a 2 min</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-2 h-2 bg-blue-400 rounded-full mt-2 flex-shrink-0"></div>
                    <div>
                        <p class="text-sm text-gray-800 dark:text-gray-200">Commande #1087 passée — 45,000 FCFA</p>
                        <p class="text-xs text-gray-400">Il y a 8 min</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-2 h-2 bg-orange-400 rounded-full mt-2 flex-shrink-0"></div>
                    <div>
                        <p class="text-sm text-gray-800 dark:text-gray-200">Candidature déposée — Développeur</p>
                        <p class="text-xs text-gray-400">Il y a 12 min</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-2 h-2 bg-purple-400 rounded-full mt-2 flex-shrink-0"></div>
                    <div>
                        <p class="text-sm text-gray-800 dark:text-gray-200">Vidéo regardée — 1.2k vues</p>
                        <p class="text-xs text-gray-400">Il y a 18 min</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-2 h-2 bg-yellow-400 rounded-full mt-2 flex-shrink-0"></div>
                    <div>
                        <p class="text-sm text-gray-800 dark:text-gray-200">Livraison complétée — Lagos → Abidjan</p>
                        <p class="text-xs text-gray-400">Il y a 25 min</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-2 h-2 bg-red-400 rounded-full mt-2 flex-shrink-0"></div>
                    <div>
                        <p class="text-sm text-gray-800 dark:text-gray-200">Paiement reçu — 120,000 FCFA</p>
                        <p class="text-xs text-gray-400">Il y a 32 min</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Modules Table -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden fade-in-up" style="animation-delay:0.5s">
        <div class="p-5 border-b border-gray-100 dark:border-gray-700">
            <h2 class="font-semibold text-gray-900 dark:text-white">Performance par module</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-750">
                    <tr>
                        <th class="text-left text-xs font-medium text-gray-500 dark:text-gray-400 px-5 py-3">Module</th>
                        <th class="text-right text-xs font-medium text-gray-500 dark:text-gray-400 px-5 py-3">Utilisateurs</th>
                        <th class="text-right text-xs font-medium text-gray-500 dark:text-gray-400 px-5 py-3">Revenus</th>
                        <th class="text-left text-xs font-medium text-gray-500 dark:text-gray-400 px-5 py-3 hidden md:table-cell">Part</th>
                        <th class="text-right text-xs font-medium text-gray-500 dark:text-gray-400 px-5 py-3">Croissance</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-750">
                        <td class="px-5 py-3 text-sm text-gray-900 dark:text-white font-medium">🏫 École</td>
                        <td class="px-5 py-3 text-sm text-right text-gray-600 dark:text-gray-300">4,201</td>
                        <td class="px-5 py-3 text-sm text-right text-gray-900 dark:text-white font-semibold">12.4M FCFA</td>
                        <td class="px-5 py-3 hidden md:table-cell">
                            <div class="h-2 bg-gray-100 dark:bg-gray-700 rounded-full w-32"><div class="h-full bg-indigo-500 rounded-full" style="width:74%"></div></div>
                        </td>
                        <td class="px-5 py-3 text-sm text-right text-green-600 font-medium">+18%</td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-750">
                        <td class="px-5 py-3 text-sm text-gray-900 dark:text-white font-medium">🛒 Boutique</td>
                        <td class="px-5 py-3 text-sm text-right text-gray-600 dark:text-gray-300">3,850</td>
                        <td class="px-5 py-3 text-sm text-right text-gray-900 dark:text-white font-semibold">10.8M FCFA</td>
                        <td class="px-5 py-3 hidden md:table-cell">
                            <div class="h-2 bg-gray-100 dark:bg-gray-700 rounded-full w-32"><div class="h-full bg-green-500 rounded-full" style="width:65%"></div></div>
                        </td>
                        <td class="px-5 py-3 text-sm text-right text-green-600 font-medium">+25%</td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-750">
                        <td class="px-5 py-3 text-sm text-gray-900 dark:text-white font-medium">💼 Emplois</td>
                        <td class="px-5 py-3 text-sm text-right text-gray-600 dark:text-gray-300">2,940</td>
                        <td class="px-5 py-3 text-sm text-right text-gray-900 dark:text-white font-semibold">8.2M FCFA</td>
                        <td class="px-5 py-3 hidden md:table-cell">
                            <div class="h-2 bg-gray-100 dark:bg-gray-700 rounded-full w-32"><div class="h-full bg-orange-500 rounded-full" style="width:49%"></div></div>
                        </td>
                        <td class="px-5 py-3 text-sm text-right text-green-600 font-medium">+12%</td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-750">
                        <td class="px-5 py-3 text-sm text-gray-900 dark:text-white font-medium">🚚 Logistique</td>
                        <td class="px-5 py-3 text-sm text-right text-gray-600 dark:text-gray-300">1,856</td>
                        <td class="px-5 py-3 text-sm text-right text-gray-900 dark:text-white font-semibold">7.1M FCFA</td>
                        <td class="px-5 py-3 hidden md:table-cell">
                            <div class="h-2 bg-gray-100 dark:bg-gray-700 rounded-full w-32"><div class="h-full bg-purple-500 rounded-full" style="width:43%"></div></div>
                        </td>
                        <td class="px-5 py-3 text-sm text-right text-green-600 font-medium">+31%</td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-750">
                        <td class="px-5 py-3 text-sm text-gray-900 dark:text-white font-medium">🎥 Vidéos</td>
                        <td class="px-5 py-3 text-sm text-right text-gray-600 dark:text-gray-300">5,120</td>
                        <td class="px-5 py-3 text-sm text-right text-gray-900 dark:text-white font-semibold">6.7M FCFA</td>
                        <td class="px-5 py-3 hidden md:table-cell">
                            <div class="h-2 bg-gray-100 dark:bg-gray-700 rounded-full w-32"><div class="h-full bg-blue-500 rounded-full" style="width:40%"></div></div>
                        </td>
                        <td class="px-5 py-3 text-sm text-right text-green-600 font-medium">+22%</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function animateCounter(el, target, duration = 2000, isFcfa = false) {
    let start = 0;
    const step = (timestamp) => {
        if (!start) start = timestamp;
        const progress = Math.min((timestamp - start) / duration, 1);
        const val = Math.floor(progress * target);
        el.textContent = isFcfa ? (val / 1000000).toFixed(1) + 'M FCFA' : val.toLocaleString('fr-FR');
        if (progress < 1) requestAnimationFrame(step);
    };
    requestAnimationFrame(step);
}
document.querySelectorAll('[data-counter]').forEach(el => animateCounter(el, parseInt(el.dataset.counter)));
document.querySelectorAll('[data-counter-fcfa]').forEach(el => animateCounter(el, parseInt(el.dataset.counterFcfa), 2000, true));
</script>
@endsection
