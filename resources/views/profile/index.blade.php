@extends('layouts.app')

@section('title', 'Mon Profil - Lumo Plateforme')

@section('content')
<div class="max-w-3xl mx-auto" x-data="profilePage()" x-init="init()">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-6">👤 Mon Profil</h1>

    <div x-show="flash.message" x-transition class="mb-4 px-4 py-3 rounded-lg text-sm"
        :class="flash.type === 'error' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'"
        x-text="flash.message"></div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-600 to-blue-600 p-6 md:p-8 flex items-center gap-4 md:gap-6">
            <div class="w-16 h-16 md:w-20 md:h-20 rounded-full bg-white/20 flex items-center justify-center text-4xl md:text-5xl">👤</div>
            <div>
                <h2 class="text-xl md:text-2xl font-bold text-white" x-text="profile.name || 'Utilisateur'"></h2>
                <p class="text-indigo-100 text-sm" x-text="profile.email || '-' "></p>
                <span class="mt-2 inline-block bg-white/20 text-white text-xs px-3 py-1 rounded-full" x-text="formatRole(profile.role)"></span>
            </div>
        </div>

        <div class="p-5 md:p-8">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-5">Informations personnelles</h3>
            <form class="space-y-5" @submit.prevent="saveProfile()">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-6">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nom complet</label>
                        <input x-model="form.name" type="text" required class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Téléphone</label>
                        <input x-model="form.phone" type="tel" placeholder="+225..." class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ville</label>
                        <input x-model="form.city" type="text" placeholder="Abidjan" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pays</label>
                        <input x-model="form.country" type="text" placeholder="Côte d'Ivoire" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Adresse</label>
                        <input x-model="form.address" type="text" placeholder="Adresse" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bio</label>
                        <textarea x-model="form.bio" rows="3" placeholder="Parlez de vous..." class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none resize-none"></textarea>
                    </div>
                </div>
                <button type="submit" :disabled="savingProfile" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-3 rounded-xl transition disabled:opacity-50">
                    <span x-show="!savingProfile">Sauvegarder les modifications</span>
                    <span x-show="savingProfile">Sauvegarde...</span>
                </button>
            </form>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-100 dark:border-gray-700 p-5 md:p-8 mt-6">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-5">🔒 Changer le mot de passe</h3>
        <form class="space-y-4" @submit.prevent="changePassword()">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mot de passe actuel</label>
                <input x-model="passwordForm.current_password" type="password" required class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nouveau mot de passe</label>
                <input x-model="passwordForm.password" type="password" required minlength="8" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirmer le nouveau mot de passe</label>
                <input x-model="passwordForm.password_confirmation" type="password" required minlength="8" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
            <button type="submit" :disabled="savingPassword" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-3 rounded-xl transition disabled:opacity-50">
                <span x-show="!savingPassword">Changer le mot de passe</span>
                <span x-show="savingPassword">Mise à jour...</span>
            </button>
        </form>
    </div>
</div>

<script>
function profilePage() {
    return {
        loading: false,
        savingProfile: false,
        savingPassword: false,
        profile: {},
        form: {
            name: '',
            phone: '',
            city: '',
            country: '',
            address: '',
            bio: '',
            locale: 'fr',
            theme: 'light',
        },
        passwordForm: {
            current_password: '',
            password: '',
            password_confirmation: '',
        },
        flash: { message: '', type: 'success' },
        get token() {
            return localStorage.getItem('token') || '';
        },
        showFlash(message, type = 'success') {
            this.flash = { message, type };
            setTimeout(() => { this.flash.message = ''; }, 3500);
        },
        formatRole(role) {
            const labels = {
                super_admin: 'Super Admin',
                admin: 'Admin',
                school_admin: 'Gestionnaire école',
                teacher: 'Enseignant',
                student: 'Élève',
            };
            return labels[role] || role || 'Utilisateur';
        },
        async fetchJson(url, options = {}) {
            const response = await fetch(url, {
                headers: {
                    Accept: 'application/json',
                    Authorization: `Bearer ${this.token}`,
                    ...(options.headers || {}),
                },
                credentials: 'same-origin',
                ...options,
            });

            const payload = await response.json();
            if (!response.ok || !payload.success) {
                throw new Error(payload.message || 'Erreur API');
            }

            return payload;
        },
        hydrateForm() {
            this.form = {
                name: this.profile.name || '',
                phone: this.profile.phone || '',
                city: this.profile.city || '',
                country: this.profile.country || '',
                address: this.profile.address || '',
                bio: this.profile.bio || '',
                locale: this.profile.locale || 'fr',
                theme: this.profile.theme || 'light',
            };
        },
        async init() {
            this.loading = true;
            try {
                const payload = await this.fetchJson('/api/auth/me');
                this.profile = payload.data || {};
                localStorage.setItem('user', JSON.stringify(this.profile));

                this.hydrateForm();
            } catch (error) {
                this.showFlash(error.message || 'Impossible de charger le profil', 'error');
            } finally {
                this.loading = false;
            }
        },
        async saveProfile() {
            this.savingProfile = true;
            try {
                const payload = await this.fetchJson('/api/auth/profile', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    },
                    body: JSON.stringify(this.form),
                });

                this.profile = payload.data || this.profile;
                localStorage.setItem('user', JSON.stringify(this.profile));
                this.showFlash('Profil mis à jour.');
            } catch (error) {
                this.showFlash(error.message || 'Mise à jour impossible', 'error');
            } finally {
                this.savingProfile = false;
            }
        },
        async changePassword() {
            this.savingPassword = true;
            try {
                await this.fetchJson('/api/auth/password', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    },
                    body: JSON.stringify(this.passwordForm),
                });

                this.passwordForm.current_password = '';
                this.passwordForm.password = '';
                this.passwordForm.password_confirmation = '';
                this.showFlash('Mot de passe mis à jour.');
            } catch (error) {
                this.showFlash(error.message || 'Modification impossible', 'error');
            } finally {
                this.savingPassword = false;
            }
        },
    };
}
</script>
@endsection
