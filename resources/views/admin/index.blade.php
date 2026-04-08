@extends('layouts.app')
@section('title', 'Admin - Lumo Plateforme')
@section('content')
<style>
@keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
@keyframes pulse-ring { 0% { transform: scale(0.8); opacity: 1; } 100% { transform: scale(2.2); opacity: 0; } }
.fade-in-up { animation: fadeInUp 0.6s ease forwards; }
.pulse-ring { animation: pulse-ring 1.5s infinite; }
</style>

<div>
    <!-- Header -->
    <div class="mb-8 fade-in-up">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">⚙️ Tableau de bord Admin</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Gestion et supervision de la plateforme Lumo</p>
            </div>
            <div class="flex gap-3">
                <a href="/admin/users" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition">👥 Utilisateurs</a>
                <a href="/admin/audit-logs" class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-xl text-sm font-medium transition">📋 Logs</a>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm fade-in-up" style="animation-delay:0.1s">
            <div class="flex items-center justify-between mb-3">
                <span class="text-3xl">👥</span>
                <span class="text-xs bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 px-2 py-1 rounded-full">Total</span>
            </div>
            <p class="text-3xl font-bold text-gray-900 dark:text-white" data-counter="3241">0</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Utilisateurs inscrits</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm fade-in-up" style="animation-delay:0.2s">
            <div class="flex items-center justify-between mb-3">
                <span class="text-3xl">🧩</span>
                <span class="text-xs bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 px-2 py-1 rounded-full">Actifs</span>
            </div>
            <p class="text-3xl font-bold text-gray-900 dark:text-white" data-counter="10">0</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Modules actifs</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm fade-in-up" style="animation-delay:0.3s">
            <div class="flex items-center justify-between mb-3">
                <span class="text-3xl">💰</span>
                <span class="text-xs bg-yellow-100 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-300 px-2 py-1 rounded-full">Aujourd'hui</span>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">285 000 <span class="text-lg">FCFA</span></p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Revenus du jour</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm fade-in-up" style="animation-delay:0.4s">
            <div class="flex items-center justify-between mb-3">
                <span class="text-3xl">🚨</span>
                <span class="text-xs bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 px-2 py-1 rounded-full animate-pulse">Alerte</span>
            </div>
            <p class="text-3xl font-bold text-gray-900 dark:text-white" data-counter="2">0</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Alertes système</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Recent Activities -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden fade-in-up" style="animation-delay:0.3s">
            <div class="p-5 border-b border-gray-100 dark:border-gray-700">
                <h2 class="font-semibold text-gray-900 dark:text-white">Activités récentes</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-750">
                        <tr>
                            <th class="text-left text-xs font-medium text-gray-500 dark:text-gray-400 px-5 py-3">Action</th>
                            <th class="text-left text-xs font-medium text-gray-500 dark:text-gray-400 px-5 py-3">Utilisateur</th>
                            <th class="text-left text-xs font-medium text-gray-500 dark:text-gray-400 px-5 py-3 hidden md:table-cell">Module</th>
                            <th class="text-right text-xs font-medium text-gray-500 dark:text-gray-400 px-5 py-3">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-750">
                            <td class="px-5 py-3 text-sm text-gray-900 dark:text-white">Inscription</td>
                            <td class="px-5 py-3 text-sm text-gray-600 dark:text-gray-300">Kouassi Ange</td>
                            <td class="px-5 py-3 text-sm text-gray-500 dark:text-gray-400 hidden md:table-cell">🏫 École</td>
                            <td class="px-5 py-3 text-xs text-gray-400 text-right">15 Jan, 14:32</td>
                        </tr>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-750">
                            <td class="px-5 py-3 text-sm text-gray-900 dark:text-white">Commande passée</td>
                            <td class="px-5 py-3 text-sm text-gray-600 dark:text-gray-300">Fatou Diallo</td>
                            <td class="px-5 py-3 text-sm text-gray-500 dark:text-gray-400 hidden md:table-cell">🛒 Boutique</td>
                            <td class="px-5 py-3 text-xs text-gray-400 text-right">15 Jan, 13:15</td>
                        </tr>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-750">
                            <td class="px-5 py-3 text-sm text-gray-900 dark:text-white">Offre publiée</td>
                            <td class="px-5 py-3 text-sm text-gray-600 dark:text-gray-300">TechAfrique SARL</td>
                            <td class="px-5 py-3 text-sm text-gray-500 dark:text-gray-400 hidden md:table-cell">💼 Emplois</td>
                            <td class="px-5 py-3 text-xs text-gray-400 text-right">15 Jan, 11:50</td>
                        </tr>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-750">
                            <td class="px-5 py-3 text-sm text-gray-900 dark:text-white">Paiement reçu</td>
                            <td class="px-5 py-3 text-sm text-gray-600 dark:text-gray-300">Mamadou Bah</td>
                            <td class="px-5 py-3 text-sm text-gray-500 dark:text-gray-400 hidden md:table-cell">💳 Wallet</td>
                            <td class="px-5 py-3 text-xs text-gray-400 text-right">15 Jan, 10:22</td>
                        </tr>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-750">
                            <td class="px-5 py-3 text-sm text-gray-900 dark:text-white">Vidéo uploadée</td>
                            <td class="px-5 py-3 text-sm text-gray-600 dark:text-gray-300">Prof. Assi Kouamé</td>
                            <td class="px-5 py-3 text-sm text-gray-500 dark:text-gray-400 hidden md:table-cell">🎥 Vidéos</td>
                            <td class="px-5 py-3 text-xs text-gray-400 text-right">15 Jan, 09:05</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- System Health -->
        <div class="space-y-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm fade-in-up" style="animation-delay:0.4s">
                <h2 class="font-semibold text-gray-900 dark:text-white mb-4">🖥️ Santé du système</h2>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="relative">
                                <div class="absolute inset-0 bg-green-400 rounded-full pulse-ring"></div>
                                <div class="w-3 h-3 bg-green-500 rounded-full relative z-10"></div>
                            </div>
                            <span class="text-sm text-gray-700 dark:text-gray-300">Base de données</span>
                        </div>
                        <span class="text-xs text-green-600 font-medium">Opérationnel</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="relative">
                                <div class="absolute inset-0 bg-green-400 rounded-full pulse-ring" style="animation-delay:0.3s"></div>
                                <div class="w-3 h-3 bg-green-500 rounded-full relative z-10"></div>
                            </div>
                            <span class="text-sm text-gray-700 dark:text-gray-300">API</span>
                        </div>
                        <span class="text-xs text-green-600 font-medium">Opérationnel</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="relative">
                                <div class="absolute inset-0 bg-yellow-400 rounded-full pulse-ring" style="animation-delay:0.6s"></div>
                                <div class="w-3 h-3 bg-yellow-500 rounded-full relative z-10"></div>
                            </div>
                            <span class="text-sm text-gray-700 dark:text-gray-300">Stockage</span>
                        </div>
                        <span class="text-xs text-yellow-600 font-medium">85% utilisé</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="relative">
                                <div class="absolute inset-0 bg-green-400 rounded-full pulse-ring" style="animation-delay:0.9s"></div>
                                <div class="w-3 h-3 bg-green-500 rounded-full relative z-10"></div>
                            </div>
                            <span class="text-sm text-gray-700 dark:text-gray-300">Cache</span>
                        </div>
                        <span class="text-xs text-green-600 font-medium">Opérationnel</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm fade-in-up" style="animation-delay:0.5s">
                <h2 class="font-semibold text-gray-900 dark:text-white mb-3">Actions rapides</h2>
                <div class="grid grid-cols-2 gap-2">
                    <a href="/admin/users" class="bg-indigo-50 dark:bg-indigo-900 hover:bg-indigo-100 dark:hover:bg-indigo-800 text-indigo-700 dark:text-indigo-300 p-3 rounded-xl text-xs font-medium text-center transition">
                        👤 Ajouter<br>utilisateur
                    </a>
                    <a href="/admin/audit-logs" class="bg-blue-50 dark:bg-blue-900 hover:bg-blue-100 dark:hover:bg-blue-800 text-blue-700 dark:text-blue-300 p-3 rounded-xl text-xs font-medium text-center transition">
                        📋 Voir<br>les logs
                    </a>
                    <a href="/analytics" class="bg-green-50 dark:bg-green-900 hover:bg-green-100 dark:hover:bg-green-800 text-green-700 dark:text-green-300 p-3 rounded-xl text-xs font-medium text-center transition">
                        📊 Gérer<br>modules
                    </a>
                    <a href="/analytics" class="bg-orange-50 dark:bg-orange-900 hover:bg-orange-100 dark:hover:bg-orange-800 text-orange-700 dark:text-orange-300 p-3 rounded-xl text-xs font-medium text-center transition">
                        📈 Voir<br>rapports
                    </a>
                </div>
            </div>

            <!-- User Growth Mini Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm fade-in-up" style="animation-delay:0.6s">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-3 text-sm">Croissance utilisateurs</h3>
                <div class="flex items-end gap-1 h-16">
                    <div class="flex-1 bg-indigo-200 dark:bg-indigo-900 rounded-t" style="height:40%"></div>
                    <div class="flex-1 bg-indigo-300 dark:bg-indigo-800 rounded-t" style="height:55%"></div>
                    <div class="flex-1 bg-indigo-400 dark:bg-indigo-700 rounded-t" style="height:45%"></div>
                    <div class="flex-1 bg-indigo-500 dark:bg-indigo-600 rounded-t" style="height:70%"></div>
                    <div class="flex-1 bg-indigo-500 dark:bg-indigo-600 rounded-t" style="height:65%"></div>
                    <div class="flex-1 bg-indigo-600 dark:bg-indigo-500 rounded-t" style="height:80%"></div>
                    <div class="flex-1 bg-indigo-600 dark:bg-indigo-500 rounded-t" style="height:90%"></div>
                    <div class="flex-1 bg-gradient-to-t from-indigo-600 to-purple-500 rounded-t" style="height:100%"></div>
                </div>
                <div class="flex justify-between text-xs text-gray-400 mt-1">
                    <span>Jun</span><span>Déc</span>
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
