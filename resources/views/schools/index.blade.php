@extends('layouts.app')

@section('title', 'Écoles - Lumo Plateforme')

@section('content')
<div x-data="schoolsPage()" x-init="init()" @keydown.escape.window="closeAllModals()">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-indigo-600 dark:text-indigo-300">Éducation</p>
            <h1 class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">Écoles</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1 max-w-2xl">Sélectionnez un établissement et ouvrez un portail adapté à sa structure: Maternelle & Primaire, Primaire & Secondaire, Secondaire & Humanités, ou le portail complet.</p>
        </div>
        <a x-show="can('schools.create')" href="/schools/create" class="min-h-11 inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 rounded-2xl font-semibold transition inline-block text-center shadow-sm">
            + Ajouter une école
        </a>
    </div>

    <div class="mb-6 rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm p-4 md:p-5">
        <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-indigo-600 dark:text-indigo-300">Parcours école</p>
                <h2 class="mt-1 text-lg md:text-xl font-bold text-gray-900 dark:text-white">Des portails différents selon la structure de l’école</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-300 max-w-3xl">Chaque établissement peut exposer un sous-module adapté. Le rendu affiche clairement le périmètre couvert et ce que l’équipe peut piloter.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="/schools/my" class="min-h-11 inline-flex items-center justify-center rounded-2xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 transition">Mon établissement</a>
                <a href="/dashboard" class="min-h-11 inline-flex items-center justify-center rounded-2xl border border-gray-300 dark:border-gray-600 px-4 py-3 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition">Tableau de bord</a>
            </div>
        </div>

        <div class="mt-5 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-3 md:gap-4">
            <template x-for="module in portalModules" :key="module.key">
                <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-gradient-to-b from-gray-50 to-white dark:from-gray-800 dark:to-gray-800 p-4 shadow-sm">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-indigo-600 dark:text-indigo-300" x-text="module.key"></p>
                            <h3 class="mt-2 text-base font-bold text-gray-900 dark:text-white" x-text="module.label"></h3>
                        </div>
                        <span class="rounded-full px-2.5 py-1 text-[11px] font-semibold" :class="module.key === 'full' ? 'bg-indigo-600 text-white' : 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-200'" x-text="module.badge"></span>
                    </div>
                    <p class="mt-3 text-sm text-gray-600 dark:text-gray-300" x-text="module.description"></p>
                    <div class="mt-4 flex flex-wrap gap-2 text-[11px] text-gray-500 dark:text-gray-400">
                        <template x-for="level in module.levels" :key="`${module.key}-${level}`">
                            <span class="rounded-full bg-white dark:bg-gray-900 px-2.5 py-1 border border-gray-200 dark:border-gray-700" x-text="level"></span>
                        </template>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <div x-show="flash.message" x-transition class="mb-4 px-4 py-3 rounded-lg text-sm"
        :class="flash.type === 'error' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'"
        x-text="flash.message"></div>

    <!-- Search -->
    <div class="mb-6 flex flex-col md:flex-row gap-3 md:items-center md:justify-between">
        <input x-model="search" type="text" placeholder="Rechercher une école..."
            @input.debounce.400ms="loadSchools(1)"
            class="w-full md:max-w-md border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
        <select x-model="typeFilter" @change="loadSchools(1)"
            class="w-full md:w-52 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
            <option value="">Tous les types</option>
            <option value="maternelle">Maternelle</option>
            <option value="primaire">Primaire</option>
            <option value="secondaire">Secondaire</option>
            <option value="humanites">Humanités</option>
        </select>
    </div>

    <div x-show="loading" class="text-sm text-gray-500 dark:text-gray-400 mb-4">Chargement des écoles...</div>
    <div x-show="!loading && schools.length === 0" class="text-sm text-gray-500 dark:text-gray-400 mb-4">Aucune école trouvée.</div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <template x-for="school in schools" :key="school.id">
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm hover:shadow-lg transition border border-gray-200 dark:border-gray-700 overflow-hidden group">
                <a :href="`/schools/${school.id}`" class="block p-5 md:p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-indigo-600 dark:text-indigo-300" x-text="formatLevelTypes(school.level_types || (school.type ? [school.type] : []))"></p>
                            <h3 class="mt-2 font-bold text-gray-900 dark:text-white text-lg group-hover:text-indigo-600 transition" x-text="school.name"></h3>
                            <p class="mt-1 text-gray-500 dark:text-gray-400 text-sm" x-text="`📍 ${school.city || 'Ville non définie'}`"></p>
                        </div>
                        <div class="rounded-2xl bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-200 px-3 py-2 text-sm font-semibold">Ouvrir</div>
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-3 text-xs">
                        <div class="rounded-2xl border border-gray-200 dark:border-gray-700 px-3 py-3 bg-gray-50 dark:bg-gray-900/30">
                            <p class="text-gray-500 dark:text-gray-400">Élèves</p>
                            <p class="mt-1 text-lg font-bold text-gray-900 dark:text-white" x-text="school.students_count ?? 0"></p>
                        </div>
                        <div class="rounded-2xl border border-gray-200 dark:border-gray-700 px-3 py-3 bg-gray-50 dark:bg-gray-900/30">
                            <p class="text-gray-500 dark:text-gray-400">Enseignants</p>
                            <p class="mt-1 text-lg font-bold text-gray-900 dark:text-white" x-text="school.teachers_count ?? 0"></p>
                        </div>
                    </div>

                    <div class="mt-4 flex items-center justify-between gap-2 border-t border-gray-100 dark:border-gray-700 pt-4">
                        <span class="text-sm text-gray-500 dark:text-gray-400" x-text="school.status === 'active' ? 'Actif' : school.status"></span>
                        <span class="text-sm font-semibold text-indigo-600 dark:text-indigo-300">Accès établissement</span>
                    </div>
                </a>

                <div class="px-5 pb-5 md:px-6 md:pb-6">
                    <button @click.stop="openSchoolOptions(school)" class="w-full min-h-11 rounded-2xl border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 font-semibold hover:bg-gray-50 dark:hover:bg-gray-700 transition">Options rapides</button>
                </div>
            </div>
        </template>
    </div>

    <div class="mt-6 flex items-center justify-between" x-show="meta.last_page > 1">
        <button @click="loadSchools(meta.current_page - 1)" :disabled="meta.current_page <= 1"
            class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 text-sm disabled:opacity-50">Précédent</button>
        <span class="text-sm text-gray-500 dark:text-gray-400" x-text="`Page ${meta.current_page} / ${meta.last_page}`"></span>
        <button @click="loadSchools(meta.current_page + 1)" :disabled="meta.current_page >= meta.last_page"
            class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 text-sm disabled:opacity-50">Suivant</button>
    </div>

    <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4" @click.self="closeModal()">
        <div class="w-full max-w-2xl bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-600 via-blue-600 to-cyan-600 px-6 py-5 text-white flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold" x-text="editingId ? 'Modifier une école' : 'Créer une école'"></h2>
                    <p class="text-sm text-indigo-100 mt-1">Formulaire rapide de gestion des établissements</p>
                </div>
                <button @click="closeModal()" class="w-9 h-9 rounded-full bg-white/20 hover:bg-white/30 transition">✕</button>
            </div>

            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input x-model="form.name" type="text" placeholder="Nom de l'école" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                    <select x-model="form.type" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                        <option value="primaire">Primaire</option>
                        <option value="secondaire">Secondaire</option>
                        <option value="humanites">Humanités</option>
                    </select>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input x-model="form.city" type="text" placeholder="Ville" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                    <input x-model="form.phone" type="text" placeholder="Téléphone" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <input x-model="form.address" type="text" placeholder="Adresse" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                <input x-model="form.email" type="email" placeholder="Email" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">

                <p x-show="formError" class="text-sm text-red-600 dark:text-red-400" x-text="formError"></p>
            </div>

            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/40 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-3">
                <button @click="closeModal()" class="px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 text-sm">Annuler</button>
                <button @click="saveSchool()" :disabled="saving" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium disabled:opacity-50" x-text="saving ? 'Enregistrement...' : 'Enregistrer'"></button>
            </div>
        </div>
    </div>

    <div x-show="showOptionsModal" x-transition.opacity class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4" @click.self="closeOptionsModal()">
        <div class="w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Options de l'école</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-5" x-text="selectedSchool ? selectedSchool.name : ''"></p>

            <div class="space-y-2">
                <a :href="`/schools/${selectedSchool?.id}`" class="block w-full text-left px-4 py-3 rounded-xl bg-green-100 dark:bg-green-900/40 hover:bg-green-200 dark:hover:bg-green-900/60 transition text-sm font-medium text-green-700 dark:text-green-200">
                    🚀 Accéder à l'espace école
                </a>
                <button @click="openSchoolStats(selectedSchool)" class="w-full text-left px-4 py-3 rounded-xl bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 transition text-sm font-medium text-slate-700 dark:text-slate-100">
                    Statistiques rapides
                </button>
                <button x-show="can('schools.update')" @click="openEdit(selectedSchool)" class="w-full text-left px-4 py-3 rounded-xl bg-indigo-100 dark:bg-indigo-900/40 hover:bg-indigo-200 dark:hover:bg-indigo-900/60 transition text-sm font-medium text-indigo-700 dark:text-indigo-200">
                    Modifier cette école
                </button>
                <button x-show="can('schools.delete')" @click="promptDeleteSchool(selectedSchool)" class="w-full text-left px-4 py-3 rounded-xl bg-red-100 dark:bg-red-900/40 hover:bg-red-200 dark:hover:bg-red-900/60 transition text-sm font-medium text-red-700 dark:text-red-200">
                    Supprimer cette école
                </button>
            </div>

            <div class="mt-5 flex justify-end">
                <button @click="closeOptionsModal()" class="px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 text-sm">Fermer</button>
            </div>
        </div>
    </div>

    <div x-show="showStatsModal" x-transition.opacity class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4" @click.self="closeStatsModal()">
        <div class="w-full max-w-xl bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-start justify-between mb-5">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Statistiques de l'école</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400" x-text="selectedSchool ? selectedSchool.name : ''"></p>
                </div>
                <button @click="closeStatsModal()" class="w-9 h-9 rounded-full bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition">✕</button>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="rounded-2xl bg-blue-50 dark:bg-blue-900/20 p-4 border border-blue-200 dark:border-blue-800">
                    <p class="text-xs uppercase tracking-wide text-blue-600 dark:text-blue-300">Eleves</p>
                    <p class="text-2xl font-bold text-blue-700 dark:text-blue-200" x-text="selectedSchool?.students_count ?? 0"></p>
                </div>
                <div class="rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 p-4 border border-emerald-200 dark:border-emerald-800">
                    <p class="text-xs uppercase tracking-wide text-emerald-600 dark:text-emerald-300">Enseignants</p>
                    <p class="text-2xl font-bold text-emerald-700 dark:text-emerald-200" x-text="selectedSchool?.teachers_count ?? 0"></p>
                </div>
            </div>

            <div class="mt-4 rounded-xl bg-gray-50 dark:bg-gray-900/40 p-4 border border-gray-200 dark:border-gray-700 text-sm text-gray-600 dark:text-gray-300 space-y-1">
                <p><span class="font-medium">Ville:</span> <span x-text="selectedSchool?.city || 'Non définie'"></span></p>
                <p><span class="font-medium">Statut:</span> <span x-text="selectedSchool?.status || 'n/a'"></span></p>
            </div>
        </div>
    </div>

    <div x-show="showDeleteModal" x-transition.opacity class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center p-4" @click.self="closeDeleteModal()">
        <div class="w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-red-200 dark:border-red-800 overflow-hidden">
            <div class="px-6 py-4 bg-red-600 text-white">
                <h3 class="text-lg font-bold">Confirmer la suppression</h3>
                <p class="text-sm text-red-100 mt-1">Cette action est irreversible.</p>
            </div>

            <div class="p-6 space-y-3">
                <p class="text-sm text-gray-700 dark:text-gray-200">
                    Vous allez supprimer l'ecole <span class="font-semibold" x-text="deleteTarget?.name || ''"></span>.
                </p>
                <div class="text-xs text-gray-500 dark:text-gray-400 rounded-lg bg-gray-50 dark:bg-gray-900/40 p-3 border border-gray-200 dark:border-gray-700">
                    <p>Eleves: <span class="font-semibold" x-text="deleteTarget?.students_count ?? 0"></span></p>
                    <p>Enseignants: <span class="font-semibold" x-text="deleteTarget?.teachers_count ?? 0"></span></p>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/40 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-3">
                <button @click="closeDeleteModal()" class="px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 text-sm">Annuler</button>
                <button @click="confirmRemoveSchool()" class="px-4 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-medium">Supprimer</button>
            </div>
        </div>
    </div>
</div>

<script>
function schoolsPage() {
    return {
        loading: false,
        saving: false,
        search: '',
        typeFilter: '',
        schools: [],
        meta: { current_page: 1, last_page: 1 },
        flash: { message: '', type: 'success' },
        showModal: false,
        showOptionsModal: false,
        showStatsModal: false,
        showDeleteModal: false,
        editingId: null,
        selectedSchool: null,
        deleteTarget: null,
        formError: '',
        portalModules: [
            {
                key: 'mp',
                label: 'Maternelle & Primaire',
                badge: 'Cycle 1',
                description: 'Portail adapté aux petits cycles avec des parcours simplifiés et des flux plus légers.',
                levels: ['maternelle', 'primaire'],
            },
            {
                key: 'ps',
                label: 'Primaire & Secondaire',
                badge: 'Cycle mixte',
                description: 'Convient aux établissements qui basculent entre les classes de primaire et les débuts du secondaire.',
                levels: ['primaire', 'secondaire'],
            },
            {
                key: 'sh',
                label: 'Secondaire & Humanités',
                badge: 'Cycle 2',
                description: 'Pensé pour les niveaux supérieurs avec un suivi plus académique et plus structuré.',
                levels: ['secondaire', 'humanites'],
            },
            {
                key: 'full',
                label: 'Maternelle, Primaire, Secondaire & Humanités',
                badge: 'Complet',
                description: 'Portail complet pour les écoles qui couvrent tous les niveaux dans une seule organisation.',
                levels: ['maternelle', 'primaire', 'secondaire', 'humanites'],
            },
        ],
        form: {
            name: '',
            type: 'primaire',
            city: '',
            address: '',
            email: '',
            phone: '',
        },
        init() {
            this.loadSchools(1);
        },
        get token() {
            return localStorage.getItem('token');
        },
        apiHeaders() {
            return {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${this.token}`,
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
            };
        },
        showFlash(message, type = 'success') {
            this.flash = { message, type };
            setTimeout(() => {
                this.flash.message = '';
            }, 3500);
        },
        formatType(type) {
            const labels = {
                maternelle: 'Maternelle',
                primaire: 'Primaire',
                secondaire: 'Secondaire',
                humanites: 'Humanités',
            };

            return labels[type] ?? type;
        },
        formatLevelTypes(levelTypes = []) {
            const labels = {
                maternelle: 'Maternelle',
                primaire: 'Primaire',
                secondaire: 'Secondaire',
                humanites: 'Humanités',
            };

            if (!Array.isArray(levelTypes) || levelTypes.length === 0) {
                return 'Niveau non défini';
            }

            return levelTypes.map((level) => labels[level] ?? level).join(' · ');
        },
        async loadSchools(page = 1) {
            this.loading = true;
            try {
                const params = new URLSearchParams({
                    page: String(page),
                    per_page: '9',
                });

                if (this.search) params.set('search', this.search);
                if (this.typeFilter) params.set('type', this.typeFilter);

                const response = await fetch(`/api/schools?${params.toString()}`, {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${this.token}`,
                    },
                });

                const payload = await response.json();
                if (!response.ok || !payload.success) {
                    throw new Error(payload.message || 'Impossible de charger les écoles');
                }

                this.schools = payload.data || [];
                this.meta = payload.meta || { current_page: 1, last_page: 1 };
            } catch (error) {
                this.showFlash(error.message || 'Erreur de chargement', 'error');
            } finally {
                this.loading = false;
            }
        },
        resetForm() {
            this.form = {
                name: '',
                type: 'primaire',
                city: '',
                address: '',
                email: '',
                phone: '',
            };
            this.formError = '';
            this.editingId = null;
        },
        openCreate() {
            this.resetForm();
            this.showModal = true;
        },
        openSchoolOptions(school) {
            this.selectedSchool = school;
            this.showOptionsModal = true;
        },
        closeOptionsModal() {
            this.showOptionsModal = false;
        },
        openSchoolStats(school) {
            this.selectedSchool = school;
            this.showOptionsModal = false;
            this.showStatsModal = true;
        },
        closeStatsModal() {
            this.showStatsModal = false;
        },
        openEdit(school) {
            this.resetForm();
            this.editingId = school.id;
            this.showOptionsModal = false;
            this.form = {
                name: school.name || '',
                type: school.type || 'primaire',
                city: school.city || '',
                address: school.address || '',
                email: school.email || '',
                phone: school.phone || '',
            };
            this.showModal = true;
        },
        closeModal() {
            this.showModal = false;
        },
        closeAllModals() {
            this.showModal = false;
            this.showOptionsModal = false;
            this.showStatsModal = false;
            this.showDeleteModal = false;
        },
        promptDeleteSchool(school) {
            this.deleteTarget = school;
            this.showOptionsModal = false;
            this.showDeleteModal = true;
        },
        closeDeleteModal() {
            this.showDeleteModal = false;
            this.deleteTarget = null;
        },
        async saveSchool() {
            this.saving = true;
            this.formError = '';

            try {
                const isUpdate = !!this.editingId;
                const url = isUpdate ? `/api/schools/${this.editingId}` : '/api/schools';
                const method = isUpdate ? 'PUT' : 'POST';

                const response = await fetch(url, {
                    method,
                    headers: this.apiHeaders(),
                    body: JSON.stringify(this.form),
                });

                const payload = await response.json();
                if (!response.ok || !payload.success) {
                    throw new Error(payload.message || 'Enregistrement impossible');
                }

                this.showFlash(isUpdate ? 'École modifiée.' : 'École créée.');
                this.closeModal();
                this.loadSchools(this.meta.current_page || 1);
            } catch (error) {
                this.formError = error.message || 'Erreur de validation';
            } finally {
                this.saving = false;
            }
        },
        async confirmRemoveSchool() {
            const id = this.deleteTarget?.id;
            if (!id) return;

            try {
                const response = await fetch(`/api/schools/${id}`, {
                    method: 'DELETE',
                    headers: this.apiHeaders(),
                });

                const payload = await response.json();
                if (!response.ok || !payload.success) {
                    throw new Error(payload.message || 'Suppression impossible');
                }

                this.showFlash('École supprimée.');
                this.closeDeleteModal();
                this.loadSchools(this.meta.current_page || 1);
            } catch (error) {
                this.showFlash(error.message || 'Erreur de suppression', 'error');
            }
        },
    };
}
</script>
@endsection
