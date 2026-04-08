@extends('layouts.app')
@section('title', 'Mes commandes - Lumo Plateforme')
@section('content')
<style>
@keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
@keyframes slideDown { from { opacity: 0; max-height: 0; } to { opacity: 1; max-height: 500px; } }
.fade-in-up { animation: fadeInUp 0.6s ease forwards; }
</style>

<div x-data="{
    filter: 'toutes',
    orders: [
        { id:'LM-1088', date:'15 Jan 2025', items:3, total:66220, status:'attente', details:['Calculatrice CASIO fx-991 x1 — 15 000 FCFA','Dictionnaire Larousse 2025 x2 — 17 000 FCFA','Sac à dos scolaire x1 — 22 000 FCFA'], expanded:false },
        { id:'LM-1042', date:'12 Jan 2025', items:2, total:38500, status:'livraison', details:['Crayon de couleur 36 pièces x1 — 5 500 FCFA','Manuel de Mathématiques CM2 x2 — 16 000 FCFA'], expanded:false },
        { id:'LM-0987', date:'05 Jan 2025', items:1, total:22000, status:'livree', details:['Sac à dos scolaire Premium x1 — 22 000 FCFA'], expanded:false },
        { id:'LM-0956', date:'28 Déc 2024', items:4, total:95000, status:'livree', details:['Pack fournitures scolaires x2 — 45 000 FCFA','Calculatrice CASIO x2 — 30 000 FCFA'], expanded:false },
        { id:'LM-0923', date:'20 Déc 2024', items:1, total:8500, status:'confirmee', details:['Dictionnaire Larousse 2025 x1 — 8 500 FCFA'], expanded:false },
        { id:'LM-0895', date:'10 Déc 2024', items:2, total:17000, status:'annulee', details:['Agenda scolaire x1 — 3 500 FCFA','Stylos set professionnel x1 — 4 500 FCFA'], expanded:false }
    ],
    get filtered() {
        if (this.filter === 'toutes') return this.orders;
        return this.orders.filter(o => o.status === this.filter);
    },
    statusLabel(s) { return { attente:'En attente', confirmee:'Confirmée', livraison:'En livraison', livree:'Livrée', annulee:'Annulée' }[s] || s; },
    statusColor(s) { return { attente:'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200', confirmee:'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200', livraison:'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200', livree:'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200', annulee:'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }[s] || ''; }
}">
    <!-- Header -->
    <div class="mb-6 fade-in-up flex items-center justify-between">
        <div class="flex items-center gap-3">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">📦 Mes commandes</h1>
            <span class="bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 px-2.5 py-1 rounded-full text-sm font-medium">6</span>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="flex flex-wrap gap-2 mb-6 fade-in-up" style="animation-delay:0.1s">
        <template x-for="f in ['toutes','attente','confirmee','livraison','livree','annulee']">
            <button @click="filter = f"
                :class="filter === f ? 'bg-indigo-600 text-white shadow' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-indigo-50'"
                class="px-3 py-1.5 rounded-xl text-sm font-medium transition"
                x-text="{ toutes:'Toutes', attente:'En attente', confirmee:'Confirmées', livraison:'En livraison', livree:'Livrées', annulee:'Annulées' }[f]">
            </button>
        </template>
    </div>

    <!-- Orders List -->
    <div class="space-y-4">
        <template x-for="(o, i) in filtered" :key="o.id">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden fade-in-up" :style="`animation-delay:${i*0.05}s`">
                <div class="p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="font-mono font-semibold text-indigo-600 dark:text-indigo-400" x-text="o.id"></span>
                                <span :class="statusColor(o.status)" class="text-xs px-2 py-0.5 rounded-full font-medium" x-text="statusLabel(o.status)"></span>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400" x-text="o.date + ' · ' + o.items + ' article(s)'"></p>
                            <p class="font-bold text-gray-900 dark:text-white mt-1" x-text="o.total.toLocaleString('fr-FR') + ' FCFA'"></p>
                        </div>
                        <div class="flex flex-col gap-2 flex-shrink-0">
                            <button x-show="o.status === 'livraison'" class="text-xs bg-orange-100 dark:bg-orange-900 text-orange-700 dark:text-orange-300 px-3 py-1.5 rounded-lg hover:bg-orange-200 transition">📍 Suivre</button>
                            <button x-show="o.status === 'livree'" class="text-xs bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 px-3 py-1.5 rounded-lg hover:bg-blue-200 transition">📄 Facture</button>
                            <button x-show="['livree','annulee'].includes(o.status)" class="text-xs bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 px-3 py-1.5 rounded-lg hover:bg-indigo-200 transition">🔄 Recommander</button>
                            <button @click="o.expanded = !o.expanded" class="text-xs text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 underline" x-text="o.expanded ? 'Masquer' : 'Voir détails'"></button>
                        </div>
                    </div>

                    <!-- Delivery Timeline for in-transit orders -->
                    <div x-show="o.status === 'livraison'" class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-between text-xs">
                            <div class="flex flex-col items-center"><div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center text-white text-xs">✓</div><span class="mt-1 text-green-600 font-medium">Confirmée</span></div>
                            <div class="flex-1 h-0.5 bg-green-400 mx-1"></div>
                            <div class="flex flex-col items-center"><div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center text-white text-xs">✓</div><span class="mt-1 text-green-600 font-medium">Préparée</span></div>
                            <div class="flex-1 h-0.5 bg-orange-400 mx-1"></div>
                            <div class="flex flex-col items-center"><div class="w-6 h-6 bg-orange-500 rounded-full flex items-center justify-center text-white text-xs animate-pulse">→</div><span class="mt-1 text-orange-600 font-medium">En route</span></div>
                            <div class="flex-1 h-0.5 bg-gray-200 dark:bg-gray-700 mx-1"></div>
                            <div class="flex flex-col items-center"><div class="w-6 h-6 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center text-gray-400 text-xs">🏠</div><span class="mt-1 text-gray-400">Livrée</span></div>
                        </div>
                    </div>

                    <!-- Expanded Details -->
                    <div x-show="o.expanded" class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-medium mb-2">Détails de la commande</p>
                        <template x-for="detail in o.details">
                            <p class="text-sm text-gray-700 dark:text-gray-300" x-text="'• ' + detail"></p>
                        </template>
                    </div>
                </div>
            </div>
        </template>

        <div x-show="filtered.length === 0" class="text-center py-12 bg-white dark:bg-gray-800 rounded-2xl">
            <p class="text-4xl mb-2">📭</p>
            <p class="text-gray-500 dark:text-gray-400">Aucune commande dans cette catégorie</p>
        </div>
    </div>
</div>
@endsection
