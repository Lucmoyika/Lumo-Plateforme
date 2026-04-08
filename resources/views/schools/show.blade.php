@extends('layouts.app')
@section('title', 'École Primaire Publique de Cocody - Lumo Plateforme')
@section('content')
<style>
@keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
@keyframes slideInLeft { from { opacity: 0; transform: translateX(-30px); } to { opacity: 1; transform: translateX(0); } }
.fade-in-up { animation: fadeInUp 0.6s ease forwards; }
.row-enter { animation: slideInLeft 0.4s ease forwards; }
</style>

<div x-data="{
    activeTab: 'overview',
    showModal: false,
    search: '',
    students: [
        { nom:'Kouassi Ange', classe:'CM2', age:12, statut:'actif' },
        { nom:'Adjoua Marie', classe:'CM1', age:11, statut:'actif' },
        { nom:'Yao Pierre', classe:'CE2', age:9, statut:'actif' },
        { nom:'Koffi Jean', classe:'CP1', age:6, statut:'actif' },
        { nom:'Amenan Constance', classe:'CE1', age:8, statut:'inactif' },
        { nom:'Konan Brice', classe:'CM2', age:13, statut:'actif' },
        { nom:'Aya Florence', classe:'CP2', age:7, statut:'actif' },
        { nom:'Eba Rodrigue', classe:'CE2', age:10, statut:'actif' }
    ],
    get filteredStudents() {
        return this.students.filter(s => s.nom.toLowerCase().includes(this.search.toLowerCase()) || s.classe.toLowerCase().includes(this.search.toLowerCase()));
    }
}">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-8 mb-6 text-white fade-in-up">
        <div class="flex items-start gap-5">
            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center text-4xl">🏫</div>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold">École Primaire Publique de Cocody</h1>
                <p class="text-blue-200 mt-1">📍 Abidjan, Côte d'Ivoire · École publique</p>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden fade-in-up" style="animation-delay:0.1s">
        <div class="flex border-b border-gray-100 dark:border-gray-700 overflow-x-auto">
            <button @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-3 text-sm font-medium transition whitespace-nowrap">Vue d'ensemble</button>
            <button @click="activeTab = 'classes'" :class="activeTab === 'classes' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-3 text-sm font-medium transition whitespace-nowrap">Classes</button>
            <button @click="activeTab = 'eleves'" :class="activeTab === 'eleves' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-3 text-sm font-medium transition whitespace-nowrap">Élèves</button>
            <button @click="activeTab = 'enseignants'" :class="activeTab === 'enseignants' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-3 text-sm font-medium transition whitespace-nowrap">Enseignants</button>
            <button @click="activeTab = 'notes'" :class="activeTab === 'notes' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-3 text-sm font-medium transition whitespace-nowrap">Notes</button>
        </div>

        <div class="p-6">
            <!-- Vue d'ensemble -->
            <div x-show="activeTab === 'overview'">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <div class="bg-blue-50 dark:bg-blue-900 rounded-2xl p-4 text-center">
                        <p class="text-3xl font-bold text-blue-700 dark:text-blue-300" data-counter="320">0</p>
                        <p class="text-sm text-blue-600 dark:text-blue-400 mt-1">Élèves</p>
                    </div>
                    <div class="bg-green-50 dark:bg-green-900 rounded-2xl p-4 text-center">
                        <p class="text-3xl font-bold text-green-700 dark:text-green-300" data-counter="18">0</p>
                        <p class="text-sm text-green-600 dark:text-green-400 mt-1">Enseignants</p>
                    </div>
                    <div class="bg-purple-50 dark:bg-purple-900 rounded-2xl p-4 text-center">
                        <p class="text-3xl font-bold text-purple-700 dark:text-purple-300" data-counter="12">0</p>
                        <p class="text-sm text-purple-600 dark:text-purple-400 mt-1">Classes</p>
                    </div>
                    <div class="bg-orange-50 dark:bg-orange-900 rounded-2xl p-4 text-center">
                        <p class="text-3xl font-bold text-orange-700 dark:text-orange-300">94%</p>
                        <p class="text-sm text-orange-600 dark:text-orange-400 mt-1">Taux de réussite</p>
                    </div>
                </div>
                <div class="bg-indigo-50 dark:bg-indigo-900 rounded-2xl p-5">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-3">À propos de l'école</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">L'École Primaire Publique de Cocody est l'un des établissements d'enseignement primaire les plus réputés d'Abidjan. Fondée en 1985, elle accueille des élèves de CP1 à CM2 et est reconnue pour son excellence pédagogique et ses activités parascolaires.</p>
                </div>
            </div>

            <!-- Classes -->
            <div x-show="activeTab === 'classes'">
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div class="bg-white dark:bg-gray-750 border border-gray-200 dark:border-gray-700 rounded-2xl p-5 hover:shadow-md hover:border-indigo-300 transition">
                        <div class="text-center mb-3"><span class="text-3xl">📚</span></div>
                        <h3 class="font-semibold text-center text-gray-900 dark:text-white">CP1</h3>
                        <p class="text-sm text-center text-gray-500 mt-1">28 élèves</p>
                        <p class="text-xs text-center text-indigo-600 mt-1">Mme Kouamé</p>
                    </div>
                    <div class="bg-white dark:bg-gray-750 border border-gray-200 dark:border-gray-700 rounded-2xl p-5 hover:shadow-md hover:border-indigo-300 transition">
                        <div class="text-center mb-3"><span class="text-3xl">📚</span></div>
                        <h3 class="font-semibold text-center text-gray-900 dark:text-white">CP2</h3>
                        <p class="text-sm text-center text-gray-500 mt-1">31 élèves</p>
                        <p class="text-xs text-center text-indigo-600 mt-1">M. Traoré</p>
                    </div>
                    <div class="bg-white dark:bg-gray-750 border border-gray-200 dark:border-gray-700 rounded-2xl p-5 hover:shadow-md hover:border-indigo-300 transition">
                        <div class="text-center mb-3"><span class="text-3xl">📚</span></div>
                        <h3 class="font-semibold text-center text-gray-900 dark:text-white">CE1</h3>
                        <p class="text-sm text-center text-gray-500 mt-1">27 élèves</p>
                        <p class="text-xs text-center text-indigo-600 mt-1">Mme Diallo</p>
                    </div>
                    <div class="bg-white dark:bg-gray-750 border border-gray-200 dark:border-gray-700 rounded-2xl p-5 hover:shadow-md hover:border-indigo-300 transition">
                        <div class="text-center mb-3"><span class="text-3xl">📚</span></div>
                        <h3 class="font-semibold text-center text-gray-900 dark:text-white">CE2</h3>
                        <p class="text-sm text-center text-gray-500 mt-1">29 élèves</p>
                        <p class="text-xs text-center text-indigo-600 mt-1">M. Bamba</p>
                    </div>
                    <div class="bg-white dark:bg-gray-750 border border-gray-200 dark:border-gray-700 rounded-2xl p-5 hover:shadow-md hover:border-indigo-300 transition">
                        <div class="text-center mb-3"><span class="text-3xl">📚</span></div>
                        <h3 class="font-semibold text-center text-gray-900 dark:text-white">CM1</h3>
                        <p class="text-sm text-center text-gray-500 mt-1">30 élèves</p>
                        <p class="text-xs text-center text-indigo-600 mt-1">Mme Assi</p>
                    </div>
                    <div class="bg-white dark:bg-gray-750 border border-gray-200 dark:border-gray-700 rounded-2xl p-5 hover:shadow-md hover:border-indigo-300 transition">
                        <div class="text-center mb-3"><span class="text-3xl">📚</span></div>
                        <h3 class="font-semibold text-center text-gray-900 dark:text-white">CM2</h3>
                        <p class="text-sm text-center text-gray-500 mt-1">32 élèves</p>
                        <p class="text-xs text-center text-indigo-600 mt-1">M. Yao</p>
                    </div>
                </div>
            </div>

            <!-- Élèves -->
            <div x-show="activeTab === 'eleves'">
                <div class="flex items-center justify-between mb-4">
                    <input x-model="search" type="text" placeholder="Rechercher un élève..." class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none w-64">
                    <button @click="showModal = true" class="bg-indigo-600 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-indigo-700 transition">+ Ajouter un élève</button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left text-xs text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700">
                                <th class="pb-2 font-medium">Nom</th>
                                <th class="pb-2 font-medium">Classe</th>
                                <th class="pb-2 font-medium">Âge</th>
                                <th class="pb-2 font-medium">Statut</th>
                                <th class="pb-2 font-medium text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                            <template x-for="(s, i) in filteredStudents" :key="i">
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 row-enter" :style="`animation-delay:${i*0.04}s`">
                                    <td class="py-3 text-sm font-medium text-gray-900 dark:text-white" x-text="s.nom"></td>
                                    <td class="py-3 text-sm text-gray-600 dark:text-gray-400" x-text="s.classe"></td>
                                    <td class="py-3 text-sm text-gray-600 dark:text-gray-400" x-text="s.age + ' ans'"></td>
                                    <td class="py-3">
                                        <span :class="s.statut === 'actif' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400'" class="text-xs px-2 py-0.5 rounded-full font-medium" x-text="s.statut === 'actif' ? 'Actif' : 'Inactif'"></span>
                                    </td>
                                    <td class="py-3 text-right">
                                        <button class="text-indigo-600 hover:text-indigo-700 text-sm">Voir</button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Enseignants -->
            <div x-show="activeTab === 'enseignants'">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="bg-gray-50 dark:bg-gray-750 rounded-2xl p-4 flex items-center gap-3">
                        <div class="w-12 h-12 bg-indigo-500 rounded-xl flex items-center justify-center text-white font-bold text-lg">MK</div>
                        <div><p class="font-medium text-gray-900 dark:text-white text-sm">Mme Kouamé</p><p class="text-xs text-gray-500">CP1 · Directrice adjointe</p></div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-750 rounded-2xl p-4 flex items-center gap-3">
                        <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center text-white font-bold text-lg">KT</div>
                        <div><p class="font-medium text-gray-900 dark:text-white text-sm">M. Traoré</p><p class="text-xs text-gray-500">CP2 · Mathématiques</p></div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-750 rounded-2xl p-4 flex items-center gap-3">
                        <div class="w-12 h-12 bg-orange-500 rounded-xl flex items-center justify-center text-white font-bold text-lg">AD</div>
                        <div><p class="font-medium text-gray-900 dark:text-white text-sm">Mme Diallo</p><p class="text-xs text-gray-500">CE1 · Français</p></div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-750 rounded-2xl p-4 flex items-center gap-3">
                        <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center text-white font-bold text-lg">BB</div>
                        <div><p class="font-medium text-gray-900 dark:text-white text-sm">M. Bamba</p><p class="text-xs text-gray-500">CE2 · Sciences</p></div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-750 rounded-2xl p-4 flex items-center gap-3">
                        <div class="w-12 h-12 bg-pink-500 rounded-xl flex items-center justify-center text-white font-bold text-lg">NA</div>
                        <div><p class="font-medium text-gray-900 dark:text-white text-sm">Mme Assi</p><p class="text-xs text-gray-500">CM1 · Histoire-Géo</p></div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-750 rounded-2xl p-4 flex items-center gap-3">
                        <div class="w-12 h-12 bg-teal-500 rounded-xl flex items-center justify-center text-white font-bold text-lg">EY</div>
                        <div><p class="font-medium text-gray-900 dark:text-white text-sm">M. Yao</p><p class="text-xs text-gray-500">CM2 · Directeur</p></div>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div x-show="activeTab === 'notes'">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Saisie des notes — Trimestre 1 · 2024-2025</p>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-750">
                            <tr class="text-left text-xs text-gray-500 dark:text-gray-400">
                                <th class="px-3 py-2 font-medium">Élève</th>
                                <th class="px-3 py-2 font-medium text-center">Français</th>
                                <th class="px-3 py-2 font-medium text-center">Maths</th>
                                <th class="px-3 py-2 font-medium text-center">Sciences</th>
                                <th class="px-3 py-2 font-medium text-center">Histoire</th>
                                <th class="px-3 py-2 font-medium text-center">Moyenne</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-750">
                                <td class="px-3 py-2 font-medium text-gray-900 dark:text-white">Kouassi Ange</td>
                                <td class="px-3 py-2 text-center"><input type="number" value="16" aria-label="Note de Français — Kouassi Ange" class="w-14 text-center border border-gray-200 dark:border-gray-600 rounded px-1 py-0.5 text-sm dark:bg-gray-700"></td>
                                <td class="px-3 py-2 text-center"><input type="number" value="18" aria-label="Note de Mathématiques — Kouassi Ange" class="w-14 text-center border border-gray-200 dark:border-gray-600 rounded px-1 py-0.5 text-sm dark:bg-gray-700"></td>
                                <td class="px-3 py-2 text-center"><input type="number" value="15" aria-label="Note de Sciences — Kouassi Ange" class="w-14 text-center border border-gray-200 dark:border-gray-600 rounded px-1 py-0.5 text-sm dark:bg-gray-700"></td>
                                <td class="px-3 py-2 text-center"><input type="number" value="14" aria-label="Note d'Histoire — Kouassi Ange" class="w-14 text-center border border-gray-200 dark:border-gray-600 rounded px-1 py-0.5 text-sm dark:bg-gray-700"></td>
                                <td class="px-3 py-2 text-center font-semibold text-green-600">15.75</td>
                            </tr>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-750">
                                <td class="px-3 py-2 font-medium text-gray-900 dark:text-white">Adjoua Marie</td>
                                <td class="px-3 py-2 text-center"><input type="number" value="14" aria-label="Note de Français — Adjoua Marie" class="w-14 text-center border border-gray-200 dark:border-gray-600 rounded px-1 py-0.5 text-sm dark:bg-gray-700"></td>
                                <td class="px-3 py-2 text-center"><input type="number" value="12" aria-label="Note de Mathématiques — Adjoua Marie" class="w-14 text-center border border-gray-200 dark:border-gray-600 rounded px-1 py-0.5 text-sm dark:bg-gray-700"></td>
                                <td class="px-3 py-2 text-center"><input type="number" value="16" aria-label="Note de Sciences — Adjoua Marie" class="w-14 text-center border border-gray-200 dark:border-gray-600 rounded px-1 py-0.5 text-sm dark:bg-gray-700"></td>
                                <td class="px-3 py-2 text-center"><input type="number" value="15" aria-label="Note d'Histoire — Adjoua Marie" class="w-14 text-center border border-gray-200 dark:border-gray-600 rounded px-1 py-0.5 text-sm dark:bg-gray-700"></td>
                                <td class="px-3 py-2 text-center font-semibold text-green-600">14.25</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <button class="mt-4 bg-indigo-600 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-indigo-700 transition">💾 Enregistrer les notes</button>
            </div>
        </div>
    </div>

    <!-- Add Student Modal -->
    <div x-show="showModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">
        <div @click.away="showModal = false" class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">👤 Ajouter un élève</h2>
                <button @click="showModal = false" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Prénom</label>
                        <input type="text" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nom</label>
                        <input type="text" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Classe</label>
                        <select class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                            <option>CP1</option><option>CP2</option><option>CE1</option><option>CE2</option><option>CM1</option><option>CM2</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Âge</label>
                        <input type="number" min="5" max="15" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                </div>
            </div>
            <div class="p-6 border-t border-gray-100 dark:border-gray-700 flex gap-3">
                <button @click="showModal = false" class="flex-1 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 py-2.5 rounded-xl font-medium hover:bg-gray-50 transition">Annuler</button>
                <button @click="showModal = false" class="flex-1 bg-indigo-600 text-white py-2.5 rounded-xl font-medium hover:bg-indigo-700 transition">Inscrire l'élève</button>
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
