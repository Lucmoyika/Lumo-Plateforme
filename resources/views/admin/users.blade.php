@extends('layouts.app')
@section('title', 'Gestion des utilisateurs - Lumo Plateforme')
@section('content')
<style>
@keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
@keyframes slideInLeft { from { opacity: 0; transform: translateX(-30px); } to { opacity: 1; transform: translateX(0); } }
.fade-in-up { animation: fadeInUp 0.6s ease forwards; }
.row-enter { animation: slideInLeft 0.4s ease forwards; }
</style>

<div x-data="{
    showModal: false,
    search: '',
    roleFilter: 'tous',
    users: [
        { id:1, nom:'Kofi Mensah', email:'kofi.mensah@gmail.com', role:'admin', statut:'actif', date:'01 Jan 2024', initials:'KM', color:'bg-indigo-500' },
        { id:2, nom:'Fatou Diallo', email:'fatou.diallo@yahoo.fr', role:'enseignant', statut:'actif', date:'05 Jan 2024', initials:'FD', color:'bg-green-500' },
        { id:3, nom:'Amara Traoré', email:'amara.traore@outlook.com', role:'etudiant', statut:'actif', date:'08 Jan 2024', initials:'AT', color:'bg-orange-500' },
        { id:4, nom:'Ngozi Adeyemi', email:'ngozi.a@techafrique.ci', role:'employeur', statut:'actif', date:'10 Jan 2024', initials:'NA', color:'bg-purple-500' },
        { id:5, nom:'Moussa Coulibaly', email:'m.coulibaly@gmail.com', role:'etudiant', statut:'inactif', date:'12 Jan 2024', initials:'MC', color:'bg-red-500' },
        { id:6, nom:'Aïsha Touré', email:'aisha.toure@gmail.com', role:'enseignant', statut:'actif', date:'13 Jan 2024', initials:'AT', color:'bg-pink-500' },
        { id:7, nom:'Kwame Asante', email:'kwame.asante@yahoo.com', role:'etudiant', statut:'actif', date:'14 Jan 2024', initials:'KA', color:'bg-teal-500' },
        { id:8, nom:'Seun Okonkwo', email:'seun.o@lagostech.ng', role:'employeur', statut:'actif', date:'15 Jan 2024', initials:'SO', color:'bg-blue-500' },
        { id:9, nom:'Mariama Barry', email:'mariama.barry@gmail.com', role:'etudiant', statut:'inactif', date:'15 Jan 2024', initials:'MB', color:'bg-yellow-500' },
        { id:10, nom:'Ibrahim Diop', email:'ibrahim.diop@univ-dakar.sn', role:'enseignant', statut:'actif', date:'15 Jan 2024', initials:'ID', color:'bg-cyan-500' }
    ],
    get filtered() {
        return this.users.filter(u => {
            const matchSearch = u.nom.toLowerCase().includes(this.search.toLowerCase()) || u.email.toLowerCase().includes(this.search.toLowerCase());
            const matchRole = this.roleFilter === 'tous' || u.role === this.roleFilter;
            return matchSearch && matchRole;
        });
    },
    roleLabel(r) { return { admin:'Admin', enseignant:'Enseignant', etudiant:'Étudiant', employeur:'Employeur' }[r] || r; },
    roleColor(r) { return { admin:'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200', enseignant:'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200', etudiant:'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200', employeur:'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' }[r] || ''; }
}">
    <!-- Header -->
    <div class="mb-6 fade-in-up flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">👥 Gestion des utilisateurs</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Gérez tous les comptes de la plateforme</p>
        </div>
        <button @click="showModal = true" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-medium transition shadow-lg">
            + Ajouter un utilisateur
        </button>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm mb-4 fade-in-up" style="animation-delay:0.1s">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input x-model="search" type="text" placeholder="Rechercher par nom ou email..." class="w-full pl-9 pr-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
            <select x-model="roleFilter" class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                <option value="tous">Tous les rôles</option>
                <option value="admin">Admin</option>
                <option value="enseignant">Enseignant</option>
                <option value="etudiant">Étudiant</option>
                <option value="employeur">Employeur</option>
            </select>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden fade-in-up" style="animation-delay:0.2s">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-750 border-b border-gray-100 dark:border-gray-700">
                    <tr>
                        <th class="text-left text-xs font-medium text-gray-500 dark:text-gray-400 px-5 py-3 w-8">
                            <input type="checkbox" class="rounded" aria-label="Sélectionner tous les utilisateurs">
                        </th>
                        <th class="text-left text-xs font-medium text-gray-500 dark:text-gray-400 px-5 py-3">Utilisateur</th>
                        <th class="text-left text-xs font-medium text-gray-500 dark:text-gray-400 px-5 py-3 hidden md:table-cell">Rôle</th>
                        <th class="text-left text-xs font-medium text-gray-500 dark:text-gray-400 px-5 py-3 hidden lg:table-cell">Statut</th>
                        <th class="text-left text-xs font-medium text-gray-500 dark:text-gray-400 px-5 py-3 hidden lg:table-cell">Inscription</th>
                        <th class="text-right text-xs font-medium text-gray-500 dark:text-gray-400 px-5 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                    <template x-for="(u, i) in filtered" :key="u.id">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition row-enter" :style="`animation-delay: ${i * 0.04}s`">
                            <td class="px-5 py-3"><input type="checkbox" class="rounded"></td>
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <div :class="u.color" class="w-9 h-9 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0" x-text="u.initials"></div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white" x-text="u.nom"></p>
                                        <p class="text-xs text-gray-400" x-text="u.email"></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3 hidden md:table-cell">
                                <span :class="roleColor(u.role)" class="text-xs px-2.5 py-1 rounded-full font-medium" x-text="roleLabel(u.role)"></span>
                            </td>
                            <td class="px-5 py-3 hidden lg:table-cell">
                                <div class="flex items-center gap-1.5">
                                    <div :class="u.statut === 'actif' ? 'bg-green-400' : 'bg-gray-400'" class="w-2 h-2 rounded-full"></div>
                                    <span class="text-sm" :class="u.statut === 'actif' ? 'text-green-700 dark:text-green-400' : 'text-gray-500'" x-text="u.statut === 'actif' ? 'Actif' : 'Inactif'"></span>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-sm text-gray-500 dark:text-gray-400 hidden lg:table-cell" x-text="u.date"></td>
                            <td class="px-5 py-3 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button class="p-1.5 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900 rounded-lg transition" title="Modifier">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button class="p-1.5 text-yellow-600 hover:bg-yellow-50 dark:hover:bg-yellow-900 rounded-lg transition text-xs font-medium" title="Désactiver">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                    </button>
                                    <button class="p-1.5 text-red-600 hover:bg-red-50 dark:hover:bg-red-900 rounded-lg transition" title="Supprimer">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="filtered.length === 0">
                        <td colspan="6" class="text-center py-10 text-gray-400">
                            <p class="text-4xl mb-2">🔍</p>
                            <p>Aucun utilisateur trouvé</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="p-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <p class="text-sm text-gray-500 dark:text-gray-400">Affichage de <span x-text="filtered.length"></span> utilisateur(s)</p>
            <div class="flex gap-1">
                <button class="px-3 py-1.5 text-sm bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">‹ Précédent</button>
                <button class="px-3 py-1.5 text-sm bg-indigo-600 text-white rounded-lg">1</button>
                <button class="px-3 py-1.5 text-sm bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">2</button>
                <button class="px-3 py-1.5 text-sm bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">Suivant ›</button>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div x-show="showModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">
        <div @click.away="showModal = false" class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">👤 Ajouter un utilisateur</h2>
                <button @click="showModal = false" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nom complet</label>
                    <input type="text" placeholder="Prénom Nom" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                    <input type="email" placeholder="email@exemple.com" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rôle</label>
                    <select class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                        <option>Étudiant</option><option>Enseignant</option><option>Employeur</option><option>Admin</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mot de passe</label>
                    <input type="password" placeholder="••••••••" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
            </div>
            <div class="p-6 border-t border-gray-100 dark:border-gray-700 flex gap-3">
                <button @click="showModal = false" class="flex-1 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 py-2.5 rounded-xl font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition">Annuler</button>
                <button @click="showModal = false" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white py-2.5 rounded-xl font-medium transition">Créer le compte</button>
            </div>
        </div>
    </div>
</div>
@endsection
