@extends('layouts.app')
@section('title', 'Logistique - Lumo Plateforme')
@section('content')
<style>
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes slideInLeft {
    from { opacity: 0; transform: translateX(-30px); }
    to { opacity: 1; transform: translateX(0); }
}
@keyframes pulse-ring {
    0% { transform: scale(0.8); opacity: 1; }
    100% { transform: scale(2); opacity: 0; }
}
@keyframes fillBar {
    from { width: 0; }
}
@keyframes pulseDot {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.5); opacity: 0.7; }
}
.fade-in-up { animation: fadeInUp 0.6s ease forwards; }
.slide-in-left { animation: slideInLeft 0.5s ease forwards; }
.pulse-dot { animation: pulseDot 2s infinite; }
.progress-fill { animation: fillBar 1.5s ease forwards; }
</style>

<div x-data="{
    filter: 'tous',
    showModal: false,
    shipments: [
        { id: 'LM-2401', dest: 'Kofi Mensah', from: 'Abidjan', to: 'Accra', status: 'transit', progress: 65, date: '15 Jan 2025' },
        { id: 'LM-2402', dest: 'Fatou Diallo', from: 'Dakar', to: 'Bamako', status: 'livre', progress: 100, date: '14 Jan 2025' },
        { id: 'LM-2403', dest: 'Amara Traoré', from: 'Abidjan', to: 'Lomé', status: 'attente', progress: 10, date: '15 Jan 2025' },
        { id: 'LM-2404', dest: 'Ngozi Adeyemi', from: 'Lagos', to: 'Cotonou', status: 'transit', progress: 40, date: '13 Jan 2025' },
        { id: 'LM-2405', dest: 'Moussa Coulibaly', from: 'Yaoundé', to: 'Abidjan', status: 'retour', progress: 55, date: '12 Jan 2025' },
        { id: 'LM-2406', dest: 'Aïsha Touré', from: 'Bamako', to: 'Dakar', status: 'livre', progress: 100, date: '11 Jan 2025' },
        { id: 'LM-2407', dest: 'Kwame Asante', from: 'Accra', to: 'Lomé', status: 'transit', progress: 80, date: '15 Jan 2025' },
        { id: 'LM-2408', dest: 'Seun Okonkwo', from: 'Lagos', to: 'Yaoundé', status: 'attente', progress: 5, date: '15 Jan 2025' }
    ],
    get filtered() {
        if (this.filter === 'tous') return this.shipments;
        return this.shipments.filter(s => s.status === this.filter);
    },
    statusLabel(s) {
        const m = { transit: 'En transit', livre: 'Livré', retour: 'Retour', attente: 'En attente' };
        return m[s] || s;
    },
    statusColor(s) {
        const m = { transit: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200', livre: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200', retour: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200', attente: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' };
        return m[s] || '';
    },
    barColor(s) {
        const m = { transit: 'bg-blue-500', livre: 'bg-green-500', retour: 'bg-red-500', attente: 'bg-yellow-400' };
        return m[s] || 'bg-gray-400';
    }
}">

    <!-- Header -->
    <div class="mb-8 fade-in-up">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">🚚 Logistique</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Suivi des livraisons à travers l'Afrique</p>
            </div>
            <button @click="showModal = true" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-medium transition shadow-lg">
                + Nouvelle expédition
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm fade-in-up" style="animation-delay:0.1s">
            <div class="flex items-center justify-between mb-2">
                <span class="text-2xl">📦</span>
                <span class="text-xs bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 px-2 py-1 rounded-full">Actif</span>
            </div>
            <p class="text-3xl font-bold text-gray-900 dark:text-white" data-counter="12">0</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Livraisons en cours</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm fade-in-up" style="animation-delay:0.2s">
            <div class="flex items-center justify-between mb-2">
                <span class="text-2xl">✅</span>
                <span class="text-xs bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 px-2 py-1 rounded-full">+12%</span>
            </div>
            <p class="text-3xl font-bold text-gray-900 dark:text-white" data-counter="847">0</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Livrées ce mois</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm fade-in-up" style="animation-delay:0.3s">
            <div class="flex items-center justify-between mb-2">
                <span class="text-2xl">↩️</span>
                <span class="text-xs bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 px-2 py-1 rounded-full">-3%</span>
            </div>
            <p class="text-3xl font-bold text-gray-900 dark:text-white" data-counter="23">0</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Retours</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm fade-in-up" style="animation-delay:0.4s">
            <div class="flex items-center justify-between mb-2">
                <span class="text-2xl">⏳</span>
                <span class="text-xs bg-yellow-100 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-300 px-2 py-1 rounded-full">Nouveau</span>
            </div>
            <p class="text-3xl font-bold text-gray-900 dark:text-white" data-counter="8">0</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">En attente</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Shipments List -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden">
                <div class="p-5 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="font-semibold text-gray-900 dark:text-white mb-3">Expéditions récentes</h2>
                    <!-- Filter Buttons -->
                    <div class="flex flex-wrap gap-2">
                        <template x-for="f in ['tous','transit','livre','retour','attente']">
                            <button @click="filter = f"
                                :class="filter === f ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-indigo-50'"
                                class="px-3 py-1.5 rounded-lg text-sm font-medium transition capitalize"
                                x-text="{ tous: 'Tous', transit: 'En transit', livre: 'Livré', retour: 'Retour', attente: 'En attente' }[f]">
                            </button>
                        </template>
                    </div>
                </div>
                <div class="divide-y divide-gray-50 dark:divide-gray-700">
                    <template x-for="(s, i) in filtered" :key="s.id">
                        <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-750 transition slide-in-left" :style="`animation-delay: ${i * 0.05}s`">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <span class="font-mono text-xs text-indigo-600 dark:text-indigo-400 font-semibold" x-text="s.id"></span>
                                    <p class="font-medium text-gray-900 dark:text-white text-sm" x-text="s.dest"></p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        <span x-text="s.from"></span> → <span x-text="s.to"></span>
                                    </p>
                                </div>
                                <div class="text-right">
                                    <span :class="statusColor(s.status)" class="text-xs px-2 py-1 rounded-full font-medium" x-text="statusLabel(s.status)"></span>
                                    <p class="text-xs text-gray-400 mt-1" x-text="s.date"></p>
                                </div>
                            </div>
                            <div class="mt-2">
                                <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mb-1">
                                    <span>Progression</span>
                                    <span x-text="s.progress + '%'"></span>
                                </div>
                                <div class="h-2 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                                    <div :class="barColor(s.status)" class="h-full rounded-full progress-fill" :style="`width: ${s.progress}%`"></div>
                                </div>
                            </div>
                        </div>
                    </template>
                    <div x-show="filtered.length === 0" class="p-8 text-center text-gray-400">
                        <p class="text-4xl mb-2">📭</p>
                        <p>Aucune expédition trouvée</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Map Placeholder -->
        <div class="space-y-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden fade-in-up" style="animation-delay:0.3s">
                <div class="p-4 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="font-semibold text-gray-900 dark:text-white">🗺️ Zones de livraison</h2>
                </div>
                <div class="relative bg-gradient-to-br from-green-800 to-green-600 h-64 overflow-hidden">
                    <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle, rgba(255,255,255,0.3) 1px, transparent 1px); background-size: 20px 20px;"></div>
                    <!-- Pulse dots for cities -->
                    <div class="absolute" style="top:40%;left:25%">
                        <div class="relative">
                            <div class="absolute inset-0 bg-yellow-400 rounded-full pulse-dot" style="width:12px;height:12px;animation-delay:0s"></div>
                            <div class="w-3 h-3 bg-yellow-400 rounded-full"></div>
                            <span class="absolute -bottom-5 left-1/2 -translate-x-1/2 text-white text-xs whitespace-nowrap">Abidjan</span>
                        </div>
                    </div>
                    <div class="absolute" style="top:25%;left:15%">
                        <div class="relative">
                            <div class="w-3 h-3 bg-blue-400 rounded-full pulse-dot" style="animation-delay:0.3s"></div>
                            <span class="absolute -bottom-5 left-1/2 -translate-x-1/2 text-white text-xs whitespace-nowrap">Dakar</span>
                        </div>
                    </div>
                    <div class="absolute" style="top:30%;left:35%">
                        <div class="relative">
                            <div class="w-3 h-3 bg-red-400 rounded-full pulse-dot" style="animation-delay:0.6s"></div>
                            <span class="absolute -bottom-5 left-1/2 -translate-x-1/2 text-white text-xs whitespace-nowrap">Bamako</span>
                        </div>
                    </div>
                    <div class="absolute" style="top:42%;left:45%">
                        <div class="relative">
                            <div class="w-3 h-3 bg-green-300 rounded-full pulse-dot" style="animation-delay:0.9s"></div>
                            <span class="absolute -bottom-5 left-1/2 -translate-x-1/2 text-white text-xs whitespace-nowrap">Lomé</span>
                        </div>
                    </div>
                    <div class="absolute" style="top:38%;left:55%">
                        <div class="relative">
                            <div class="w-3 h-3 bg-orange-400 rounded-full pulse-dot" style="animation-delay:1.2s"></div>
                            <span class="absolute -bottom-5 left-1/2 -translate-x-1/2 text-white text-xs whitespace-nowrap">Lagos</span>
                        </div>
                    </div>
                    <div class="absolute" style="top:55%;left:60%">
                        <div class="relative">
                            <div class="w-3 h-3 bg-pink-400 rounded-full pulse-dot" style="animation-delay:1.5s"></div>
                            <span class="absolute -bottom-5 left-1/2 -translate-x-1/2 text-white text-xs whitespace-nowrap">Yaoundé</span>
                        </div>
                    </div>
                    <div class="absolute" style="top:36%;left:48%">
                        <div class="relative">
                            <div class="w-3 h-3 bg-cyan-300 rounded-full pulse-dot" style="animation-delay:1.8s"></div>
                            <span class="absolute -bottom-5 left-1/2 -translate-x-1/2 text-white text-xs whitespace-nowrap">Cotonou</span>
                        </div>
                    </div>
                    <div class="absolute bottom-3 right-3 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded">
                        🟡 En transit &nbsp; 🟢 Livré &nbsp; 🔵 Départ
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="bg-gradient-to-br from-indigo-600 to-purple-600 rounded-2xl p-5 text-white fade-in-up" style="animation-delay:0.5s">
                <h3 class="font-semibold mb-3">Performance ce mois</h3>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="opacity-80">Taux de livraison</span>
                        <span class="font-bold">96.4%</span>
                    </div>
                    <div class="h-2 bg-white bg-opacity-20 rounded-full">
                        <div class="h-full bg-white rounded-full progress-fill" style="width:96.4%"></div>
                    </div>
                    <div class="flex justify-between text-sm mt-3">
                        <span class="opacity-80">Délai moyen</span>
                        <span class="font-bold">2.3 jours</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="opacity-80">Satisfaction client</span>
                        <span class="font-bold">4.8/5 ⭐</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: New Shipment -->
    <div x-show="showModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">
        <div @click.away="showModal = false" class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-lg">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">📦 Nouvelle expédition</h2>
                <button @click="showModal = false" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Destinataire</label>
                        <input type="text" placeholder="Nom complet" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Téléphone</label>
                        <input type="tel" placeholder="+225 XX XX XX XX" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ville de départ</label>
                        <select class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                            <option>Abidjan</option><option>Dakar</option><option>Bamako</option>
                            <option>Lomé</option><option>Accra</option><option>Lagos</option>
                            <option>Cotonou</option><option>Yaoundé</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ville d'arrivée</label>
                        <select class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                            <option>Accra</option><option>Abidjan</option><option>Dakar</option>
                            <option>Lomé</option><option>Bamako</option><option>Lagos</option>
                            <option>Cotonou</option><option>Yaoundé</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Poids (kg)</label>
                        <input type="number" placeholder="0.5" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Type de colis</label>
                        <select class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                            <option>Documents</option><option>Colis standard</option><option>Fragile</option><option>Réfrigéré</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description du contenu</label>
                    <textarea rows="2" placeholder="Description du colis..." class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none resize-none"></textarea>
                </div>
            </div>
            <div class="p-6 border-t border-gray-100 dark:border-gray-700 flex gap-3">
                <button @click="showModal = false" class="flex-1 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 py-2.5 rounded-xl font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition">Annuler</button>
                <button @click="showModal = false" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white py-2.5 rounded-xl font-medium transition">Créer l'expédition</button>
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
document.querySelectorAll('[data-counter]').forEach(el => {
    const target = parseInt(el.dataset.counter);
    animateCounter(el, target);
});
</script>
@endsection
