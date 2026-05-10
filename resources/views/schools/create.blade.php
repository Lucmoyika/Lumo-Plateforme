@extends('layouts.app')

@section('title', 'Ajouter une école - Lumo Plateforme')

@section('content')
<div x-data="createSchoolPage()" x-init="init()">
    <div class="mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">🏫 Ajouter une école</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Créer une nouvelle école et désigner son administrateur</p>
        </div>
    </div>

    <div x-show="flash.message" x-transition class="mb-4 px-4 py-3 rounded-lg text-sm"
        :class="flash.type === 'error' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400'"
        x-text="flash.message"></div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulaire -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-8 border border-gray-100 dark:border-gray-700">
                
                <!-- Tabs -->
                <div class="flex border-b border-gray-200 dark:border-gray-700 mb-6">
                    <button @click="currentTab = 'school'" :class="currentTab === 'school' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-600 dark:text-gray-400'"
                        class="px-4 py-3 font-medium transition">
                        📋 Informations École
                    </button>
                    <button @click="currentTab = 'director'" :class="currentTab === 'director' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-600 dark:text-gray-400'"
                        class="px-4 py-3 font-medium transition">
                        👤 Administrateur
                    </button>
                </div>

                <!-- TAB 1: Informations École -->
                <div x-show="currentTab === 'school'" x-transition class="space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                                Nom de l'école <span class="text-red-500">*</span>
                            </label>
                            <input x-model="form.school.name" type="text" placeholder="Ex: Collège Sainte-Marie"
                                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none transition">
                            <p x-show="errors.school?.name" class="text-red-600 dark:text-red-400 text-xs mt-1" x-text="errors.school?.name || ''"></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                                Niveaux d'enseignement <span class="text-red-500">*</span>
                            </label>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mb-2">Sélectionnez tous les niveaux que l'école propose</p>
                            <div class="space-y-2">
                                <label class="flex items-center gap-2 p-2.5 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <input type="checkbox" value="primaire" x-model="form.school.level_types" class="rounded">
                                    <span class="text-sm text-gray-900 dark:text-white">📚 Primaire</span>
                                </label>
                                <label class="flex items-center gap-2 p-2.5 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <input type="checkbox" value="secondaire" x-model="form.school.level_types" class="rounded">
                                    <span class="text-sm text-gray-900 dark:text-white">🎓 Secondaire</span>
                                </label>
                                <label class="flex items-center gap-2 p-2.5 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <input type="checkbox" value="humanites" x-model="form.school.level_types" class="rounded">
                                    <span class="text-sm text-gray-900 dark:text-white">🏛️ Humanités</span>
                                </label>
                            </div>
                            <p x-show="errors.school?.level_types" class="text-red-600 dark:text-red-400 text-xs mt-1" x-text="errors.school?.level_types || ''"></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Ville</label>
                            <input x-model="form.school.city" type="text" placeholder="Ex: Kinshasa"
                                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none transition">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Province</label>
                            <input x-model="form.school.province" type="text" placeholder="Ex: Kasai"
                                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Adresse complète</label>
                        <input x-model="form.school.address" type="text" placeholder="Ex: 123 Rue de l'Éducation"
                            class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none transition">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Email</label>
                            <input x-model="form.school.email" type="email" placeholder="contact@ecole.cd"
                                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none transition">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Téléphone</label>
                            <input x-model="form.school.phone" type="text" placeholder="+243 123 456 789"
                                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Description</label>
                        <textarea x-model="form.school.description" placeholder="Présentation de l'école..."
                            rows="3" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none transition"></textarea>
                    </div>
                </div>

                <!-- TAB 2: Administrateur -->
                <div x-show="currentTab === 'director'" x-transition class="space-y-5">
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
                        <p class="text-sm text-blue-700 dark:text-blue-300">
                            Choisissez comment désigner l'administrateur de cette école:
                        </p>
                    </div>

                    <div class="space-y-4">
                        <!-- Option 1: Sélectionner un utilisateur existant -->
                        <label class="flex items-start gap-3 p-4 border-2 rounded-lg cursor-pointer transition" 
                            :class="directorMode === 'existing' ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20' : 'border-gray-300 dark:border-gray-600'"
                            @click="directorMode = 'existing'">
                            <input type="radio" x-model="directorMode" value="existing" class="mt-1">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900 dark:text-white mb-2">Sélectionner un utilisateur existant</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Assigner un utilisateur déjà présent dans le système</p>
                            </div>
                        </label>

                        <div x-show="directorMode === 'existing'" x-transition class="ml-9 space-y-3">
                            <input x-model="directorSearch" type="text" placeholder="Rechercher par nom ou email..."
                                @input.debounce.300ms="searchDirectors()"
                                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none transition">

                            <div x-show="searchingDirectors" class="text-sm text-gray-500 dark:text-gray-400">
                                <span>🔍 Recherche en cours...</span>
                            </div>

                            <div x-show="availableDirectors.length > 0" class="space-y-2 max-h-48 overflow-y-auto">
                                <template x-for="user in availableDirectors" :key="user.id">
                                    <div @click="selectExistingDirector(user)" class="flex items-center gap-3 p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition"
                                        :class="form.director_id === user.id ? 'bg-indigo-50 dark:bg-indigo-900/20 border-indigo-500' : ''">
                                        <input type="radio" :checked="form.director_id === user.id" class="cursor-pointer">
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-900 dark:text-white" x-text="user.name"></p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400" x-text="user.email"></p>
                                        </div>
                                        <span class="text-xs bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded" x-text="user.role"></span>
                                    </div>
                                </template>
                            </div>

                            <p x-show="searchingDirectors === false && availableDirectors.length === 0 && directorSearch"
                                class="text-sm text-gray-500 dark:text-gray-400">Aucun utilisateur trouvé.</p>
                        </div>

                        <!-- Option 2: Créer nouvel administrateur -->
                        <label class="flex items-start gap-3 p-4 border-2 rounded-lg cursor-pointer transition" 
                            :class="directorMode === 'create' ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20' : 'border-gray-300 dark:border-gray-600'"
                            @click="directorMode = 'create'">
                            <input type="radio" x-model="directorMode" value="create" class="mt-1">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900 dark:text-white mb-2">Créer un nouvel administrateur</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Créer un compte utilisateur directement pour cette école</p>
                            </div>
                        </label>

                        <div x-show="directorMode === 'create'" x-transition class="ml-9 space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                                    Nom complet <span class="text-red-500">*</span>
                                </label>
                                <input x-model="form.create_director.name" type="text" placeholder="Jean Dupont"
                                    class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none transition">
                                <p x-show="errors.create_director?.name" class="text-red-600 dark:text-red-400 text-xs mt-1" x-text="errors.create_director?.name || ''"></p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input x-model="form.create_director.email" type="email" placeholder="directeur@ecole.cd"
                                    class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none transition">
                                <p x-show="errors.create_director?.email" class="text-red-600 dark:text-red-400 text-xs mt-1" x-text="errors.create_director?.email || ''"></p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Téléphone</label>
                                <input x-model="form.create_director.phone" type="text" placeholder="+243 123 456 789"
                                    class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none transition">
                            </div>

                            <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-3">
                                <p class="text-xs text-amber-700 dark:text-amber-300">
                                    ℹ️ Un mot de passe temporaire sera généré et envoyé à l'email fourni.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="mt-8 flex justify-end gap-3 border-t border-gray-200 dark:border-gray-700 pt-6">
                    <a href="/schools" class="px-6 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        Annuler
                    </a>
                    <button @click="createSchool()" :disabled="saving" class="px-6 py-2.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-medium disabled:opacity-50 transition"
                        x-text="saving ? '⏳ Création...' : '✅ Créer l\'école'">
                    </button>
                </div>
            </div>
        </div>

        <!-- Récapitulatif -->
        <div class="lg:col-span-1">
            <div class="bg-gradient-to-br from-indigo-50 to-blue-50 dark:from-indigo-900/20 dark:to-blue-900/20 rounded-2xl p-6 border border-indigo-200 dark:border-indigo-800 sticky top-6">
                <h3 class="font-bold text-gray-900 dark:text-white mb-4">📋 Récapitulatif</h3>

                <div class="space-y-4 text-sm">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 mb-1">École</p>
                        <p class="font-medium text-gray-900 dark:text-white" x-text="form.school.name || 'Non renseigné'"></p>
                    </div>

                    <div>
                        <p class="text-gray-600 dark:text-gray-400 mb-1">Niveaux d'enseignement</p>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="level in form.school.level_types" :key="level">
                                <span class="inline-flex items-center gap-1 bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 px-2 py-1 rounded text-xs font-medium">
                                    <span x-text="level === 'primaire' ? '📚 Primaire' : (level === 'secondaire' ? '🎓 Secondaire' : '🏛️ Humanités')"></span>
                                </span>
                            </template>
                        </div>
                    </div>

                    <div>
                        <p class="text-gray-600 dark:text-gray-400 mb-1">Localisation</p>
                        <p class="font-medium text-gray-900 dark:text-white" x-text="(form.school.city || 'Non défini') + ' - ' + (form.school.province || '')"></p>
                    </div>

                    <hr class="border-indigo-200 dark:border-indigo-800 my-4">

                    <div>
                        <p class="text-gray-600 dark:text-gray-400 mb-1">Administrateur</p>
                        <template x-if="directorMode === 'existing'">
                            <p class="font-medium text-gray-900 dark:text-white text-xs bg-blue-100 dark:bg-blue-900/40 px-2 py-1 rounded inline-block"
                                x-text="selectedDirector ? selectedDirector.name : 'À sélectionner'"></p>
                        </template>
                        <template x-if="directorMode === 'create'">
                            <p class="font-medium text-gray-900 dark:text-white text-xs bg-amber-100 dark:bg-amber-900/40 px-2 py-1 rounded inline-block"
                                x-text="form.create_director.name || 'À créer'"></p>
                        </template>
                    </div>
                </div>

                <div class="mt-6 p-3 bg-white dark:bg-gray-800 rounded-lg border border-indigo-200 dark:border-indigo-800">
                    <p class="text-xs text-gray-600 dark:text-gray-400">
                        ✅ Une fois l'école créée, l'administrateur aura accès au tableau de bord de gestion de son école.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function createSchoolPage() {
    return {
        currentTab: 'school',
        directorMode: 'existing',
        saving: false,
        searchingDirectors: false,
        availableDirectors: [],
        selectedDirector: null,
        directorSearch: '',
        flash: { message: '', type: 'success' },
        errors: {},
        form: {
            school: {
                name: '',
                level_types: [],
                city: '',
                province: '',
                address: '',
                email: '',
                phone: '',
                description: '',
            },
            director_id: null,
            create_director: {
                name: '',
                email: '',
                phone: '',
            }
        },

        init() {
            this.loadAvailableDirectors();
        },

        get token() {
            return localStorage.getItem('token') || '';
        },

        authHeaders() {
            return {
                'Accept': 'application/json',
                'Authorization': `Bearer ${this.token}`,
            };
        },

        formatType(type) {
            const types = {
                'maternelle': 'Maternelle',
                'primaire': 'Primaire',
                'secondaire': 'Secondaire',
                'humanites': 'Humanités'
            };
            return types[type] || type;
        },

        async loadAvailableDirectors() {
            try {
                const response = await fetch('/api/schools/available-directors', {
                    headers: this.authHeaders()
                });
                const result = await response.json();
                this.availableDirectors = result.data || [];
            } catch (error) {
                console.error('Erreur lors du chargement des directeurs:', error);
            }
        },

        async searchDirectors() {
            if (!this.directorSearch.trim()) {
                await this.loadAvailableDirectors();
                return;
            }

            this.searchingDirectors = true;
            try {
                const response = await fetch(`/api/schools/available-directors?search=${this.directorSearch}`, {
                    headers: this.authHeaders()
                });
                const result = await response.json();
                this.availableDirectors = result.data || [];
            } catch (error) {
                console.error('Erreur lors de la recherche:', error);
            } finally {
                this.searchingDirectors = false;
            }
        },

        selectExistingDirector(user) {
            this.form.director_id = user.id;
            this.selectedDirector = user;
        },

        async createSchool() {
            this.saving = true;
            this.errors = {};

            const payload = {
                ...this.form.school,
            };

            if (this.directorMode === 'existing' && this.form.director_id) {
                payload.director_id = this.form.director_id;
            } else if (this.directorMode === 'create') {
                payload.create_director = this.form.create_director;
            }

            try {
                const response = await fetch('/api/schools', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        ...this.authHeaders(),
                    },
                    body: JSON.stringify(payload)
                });

                const result = await response.json();

                if (!response.ok) {
                    if (result.errors) {
                        this.errors = result.errors;
                    }
                    throw new Error(result.message || 'Erreur lors de la création');
                }

                this.flash.message = '✅ École créée avec succès!';
                this.flash.type = 'success';
                setTimeout(() => window.location.href = `/schools/${result.data.id}`, 2000);
            } catch (error) {
                this.flash.message = '❌ ' + error.message;
                this.flash.type = 'error';
            } finally {
                this.saving = false;
            }
        }
    };
}
</script>
@endsection
