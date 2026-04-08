@extends('layouts.app')
@section('title', 'Notifications - Lumo Plateforme')
@section('content')
<style>
@keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
@keyframes slideInLeft { from { opacity: 0; transform: translateX(-30px); } to { opacity: 1; transform: translateX(0); } }
@keyframes badgePulse { 0%,100% { transform: scale(1); } 50% { transform: scale(1.15); } }
.fade-in-up { animation: fadeInUp 0.6s ease forwards; }
.notif-enter { animation: slideInLeft 0.4s ease forwards; }
.badge-pulse { animation: badgePulse 1.5s infinite; }
</style>

<div x-data="{
    filter: 'toutes',
    notifications: [
        { id:1, type:'emploi', titre:'Nouvelle offre d\'emploi', desc:'TechAfrique recherche un Développeur Full-Stack Senior à Abidjan', time:'Il y a 5 min', read:false, date:'Aujourd\'hui' },
        { id:2, type:'boutique', titre:'Commande expédiée', desc:'Votre commande #LM-1042 est en route. Livraison estimée dans 2 jours', time:'Il y a 1h', read:false, date:'Aujourd\'hui' },
        { id:3, type:'ecole', titre:'Nouvel élève inscrit', desc:'Kouassi Ange s\'est inscrit à votre établissement scolaire', time:'Il y a 3h', read:false, date:'Aujourd\'hui' },
        { id:4, type:'systeme', titre:'Mise à jour de la plateforme', desc:'Lumo Plateforme v2.4.0 est disponible avec de nouvelles fonctionnalités', time:'Il y a 5h', read:true, date:'Aujourd\'hui' },
        { id:5, type:'emploi', titre:'Candidature vue', desc:'L\'employeur Orange CI a consulté votre candidature pour le poste de Lead Developer', time:'Hier 14:30', read:true, date:'Hier' },
        { id:6, type:'boutique', titre:'Paiement confirmé', desc:'Votre paiement de 66 220 FCFA pour la commande #LM-1088 a été confirmé', time:'Hier 10:15', read:true, date:'Hier' },
        { id:7, type:'ecole', titre:'Résultats publiés', desc:'Les notes du Trimestre 1 pour votre classe CM2 ont été publiées', time:'Il y a 3 jours', read:true, date:'Cette semaine' },
        { id:8, type:'systeme', titre:'Nouveau message', desc:'Vous avez reçu un message de Prof. Assi Kouamé concernant votre cours', time:'Il y a 4 jours', read:true, date:'Cette semaine' }
    ],
    get filtered() {
        const typeMap = { emploi:'emploi', boutique:'boutique', ecole:'ecole', systeme:'systeme' };
        if (this.filter === 'toutes') return this.notifications;
        if (this.filter === 'non-lues') return this.notifications.filter(n => !n.read);
        return this.notifications.filter(n => n.type === typeMap[this.filter]);
    },
    get unreadCount() { return this.notifications.filter(n => !n.read).length; },
    markAllRead() { this.notifications.forEach(n => n.read = true); },
    markRead(id) { const n = this.notifications.find(n => n.id === id); if(n) n.read = true; },
    typeIcon(t) { return { emploi:'💼', boutique:'🛒', ecole:'🏫', systeme:'🔔' }[t] || '🔔'; },
    typeColor(t) { return { emploi:'bg-green-100 dark:bg-green-900 text-green-600', boutique:'bg-orange-100 dark:bg-orange-900 text-orange-600', ecole:'bg-blue-100 dark:bg-blue-900 text-blue-600', systeme:'bg-gray-100 dark:bg-gray-700 text-gray-600' }[t] || ''; },
    groupedDates() {
        const groups = {};
        this.filtered.forEach(n => {
            if (!groups[n.date]) groups[n.date] = [];
            groups[n.date].push(n);
        });
        return groups;
    }
}">
    <!-- Header -->
    <div class="mb-6 fade-in-up flex items-center justify-between">
        <div class="flex items-center gap-3">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">🔔 Notifications</h1>
            <span x-show="unreadCount > 0" x-text="unreadCount" class="bg-red-500 text-white text-sm font-bold px-2.5 py-1 rounded-full badge-pulse"></span>
        </div>
        <button @click="markAllRead()" x-show="unreadCount > 0" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium border border-indigo-200 dark:border-indigo-700 px-4 py-2 rounded-xl hover:bg-indigo-50 dark:hover:bg-indigo-900 transition">
            ✓ Tout marquer comme lu
        </button>
    </div>

    <!-- Filter Tabs -->
    <div class="flex flex-wrap gap-2 mb-6 fade-in-up" style="animation-delay:0.1s">
        <template x-for="f in ['toutes','non-lues','emploi','boutique','ecole','systeme']">
            <button @click="filter = f"
                :class="filter === f ? 'bg-indigo-600 text-white shadow' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-indigo-50'"
                class="px-3 py-1.5 rounded-xl text-sm font-medium transition"
                x-text="{ toutes:'Toutes', 'non-lues':'Non lues', emploi:'💼 Emplois', boutique:'🛒 Boutique', ecole:'🏫 École', systeme:'🔔 Système' }[f]">
            </button>
        </template>
    </div>

    <!-- Notifications -->
    <div class="space-y-6">
        <template x-for="date in Object.keys(groupedDates())" :key="date">
            <div>
                <h2 class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3" x-text="date"></h2>
                <div class="space-y-2">
                    <template x-for="(n, i) in groupedDates()[date]" :key="n.id">
                        <div @click="markRead(n.id)"
                            :class="!n.read ? 'bg-indigo-50 dark:bg-indigo-900 border-l-4 border-indigo-500' : 'bg-white dark:bg-gray-800 border-l-4 border-transparent'"
                            class="rounded-r-2xl p-4 shadow-sm cursor-pointer hover:shadow-md transition notif-enter"
                            :style="`animation-delay:${i*0.05}s`">
                            <div class="flex items-start gap-4">
                                <div :class="typeColor(n.type)" class="w-10 h-10 rounded-xl flex items-center justify-center text-xl flex-shrink-0" x-text="typeIcon(n.type)"></div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2">
                                        <div>
                                            <p class="font-semibold text-gray-900 dark:text-white text-sm" x-text="n.titre"></p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5" x-text="n.desc"></p>
                                        </div>
                                        <div class="flex items-center gap-2 flex-shrink-0">
                                            <div x-show="!n.read" class="w-2.5 h-2.5 bg-indigo-500 rounded-full animate-pulse"></div>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-400 mt-1.5" x-text="n.time"></p>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </template>

        <!-- Empty State -->
        <div x-show="filtered.length === 0" class="text-center py-16 bg-white dark:bg-gray-800 rounded-2xl">
            <p class="text-5xl mb-4">🔕</p>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Aucune notification</h2>
            <p class="text-gray-500 dark:text-gray-400">Pas de notifications dans cette catégorie</p>
        </div>
    </div>
</div>
@endsection
