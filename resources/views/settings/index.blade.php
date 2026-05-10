@extends('layouts.app')

@section('title', 'Paramètres - Lumo Plateforme')

@section('content')
<div class="max-w-3xl mx-auto" x-data="settingsPage()" x-init="init()">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-6">{{ __('⚙️ Paramètres') }}</h1>

    <div x-show="flash.message" x-transition class="mb-4 px-4 py-3 rounded-lg text-sm"
        :class="flash.type === 'error' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'"
        x-text="flash.message"></div>

    <div x-show="loading" class="mb-4 text-sm text-gray-500 dark:text-gray-400">{{ __('Chargement des paramètres...') }}</div>

    <div class="flex flex-col md:flex-row gap-6">
        <!-- Sidebar Tabs -->
        <div class="md:w-48 flex-shrink-0">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-100 dark:border-gray-700 p-2">
                @foreach([
                    ['id' => 'general', 'label' => __('🌐 Général')],
                    ['id' => 'notifications', 'label' => __('🔔 Notifications')],
                    ['id' => 'privacy', 'label' => __('🔒 Confidentialité')],
                    ['id' => 'appearance', 'label' => __('🎨 Apparence')],
                ] as $tab)
                <button @click="activeTab = '{{ $tab['id'] }}'"
                    :class="activeTab === '{{ $tab['id'] }}' ? 'bg-indigo-50 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700'"
                    class="w-full text-left px-4 py-3 rounded-lg text-sm font-medium transition mb-1">
                    {{ $tab['label'] }}
                </button>
                @endforeach
            </div>
        </div>

        <!-- Tab Content -->
        <div class="flex-1 bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-100 dark:border-gray-700 p-8">
            <!-- General -->
            <div x-show="activeTab === 'general'">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6">{{ __('Paramètres généraux') }}</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Langue') }}</label>
                        <select x-model="settings.locale" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                            <option value="fr">{{ __('Français') }}</option>
                            <option value="en">{{ __('English') }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Fuseau horaire') }}</label>
                        <select x-model="settings['general.timezone']" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                            <option value="UTC+0">UTC+0 - Abidjan, Dakar</option>
                            <option value="UTC+1">UTC+1 - Lagos, Kinshasa</option>
                            <option value="UTC+2">UTC+2 - Johannesburg, Nairobi</option>
                            <option value="UTC+3">UTC+3 - Addis-Abeba</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Devise') }}</label>
                        <select x-model="settings['general.currency']" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                            <option value="FCFA">FCFA - Franc CFA</option>
                            <option value="NGN">NGN - Naira nigérian</option>
                            <option value="KES">KES - Shilling kenyan</option>
                            <option value="ZAR">ZAR - Rand sud-africain</option>
                        </select>
                    </div>
                    <button @click="save()" :disabled="saving || !hasChanges()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-3 rounded-xl transition mt-2 disabled:opacity-50">
                        <span x-show="!saving">{{ __('Sauvegarder') }}</span>
                        <span x-show="saving">{{ __('Sauvegarde...') }}</span>
                    </button>
                </div>
            </div>

            <!-- Notifications -->
            <div x-show="activeTab === 'notifications'">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6">{{ __('Préférences de notifications') }}</h2>
                <div class="space-y-4">
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-gray-700 dark:text-gray-300 text-sm">{{ __('Notifications par email') }}</span>
                        <button @click="toggle('notifications.email')" :class="settings['notifications.email'] ? 'bg-indigo-600' : 'bg-gray-300 dark:bg-gray-600'" class="relative w-12 h-6 rounded-full transition-colors duration-200"><span :class="settings['notifications.email'] ? 'translate-x-6' : 'translate-x-1'" class="absolute top-1 left-0 w-4 h-4 bg-white rounded-full transition-transform duration-200"></span></button>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-gray-700 dark:text-gray-300 text-sm">{{ __('Notifications push') }}</span>
                        <button @click="toggle('notifications.push')" :class="settings['notifications.push'] ? 'bg-indigo-600' : 'bg-gray-300 dark:bg-gray-600'" class="relative w-12 h-6 rounded-full transition-colors duration-200"><span :class="settings['notifications.push'] ? 'translate-x-6' : 'translate-x-1'" class="absolute top-1 left-0 w-4 h-4 bg-white rounded-full transition-transform duration-200"></span></button>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-gray-700 dark:text-gray-300 text-sm">{{ __('Alertes SMS') }}</span>
                        <button @click="toggle('notifications.sms')" :class="settings['notifications.sms'] ? 'bg-indigo-600' : 'bg-gray-300 dark:bg-gray-600'" class="relative w-12 h-6 rounded-full transition-colors duration-200"><span :class="settings['notifications.sms'] ? 'translate-x-6' : 'translate-x-1'" class="absolute top-1 left-0 w-4 h-4 bg-white rounded-full transition-transform duration-200"></span></button>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-gray-700 dark:text-gray-300 text-sm">{{ __('Résumé hebdomadaire') }}</span>
                        <button @click="toggle('notifications.weekly')" :class="settings['notifications.weekly'] ? 'bg-indigo-600' : 'bg-gray-300 dark:bg-gray-600'" class="relative w-12 h-6 rounded-full transition-colors duration-200"><span :class="settings['notifications.weekly'] ? 'translate-x-6' : 'translate-x-1'" class="absolute top-1 left-0 w-4 h-4 bg-white rounded-full transition-transform duration-200"></span></button>
                    </div>
                    <button @click="save()" :disabled="saving || !hasChanges()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-3 rounded-xl transition mt-2 disabled:opacity-50"><span x-show="!saving">{{ __('Sauvegarder') }}</span><span x-show="saving">{{ __('Sauvegarde...') }}</span></button>
                </div>
            </div>

            <!-- Privacy -->
            <div x-show="activeTab === 'privacy'">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6">{{ __('Confidentialité') }}</h2>
                <div class="space-y-4">
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-gray-700 dark:text-gray-300 text-sm">{{ __('Profil public') }}</span>
                        <button @click="toggle('privacy.profile_public')" :class="settings['privacy.profile_public'] ? 'bg-indigo-600' : 'bg-gray-300 dark:bg-gray-600'" class="relative w-12 h-6 rounded-full transition-colors duration-200"><span :class="settings['privacy.profile_public'] ? 'translate-x-6' : 'translate-x-1'" class="absolute top-1 left-0 w-4 h-4 bg-white rounded-full transition-transform duration-200"></span></button>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-gray-700 dark:text-gray-300 text-sm">{{ __('Afficher les activités') }}</span>
                        <button @click="toggle('privacy.activity_visible')" :class="settings['privacy.activity_visible'] ? 'bg-indigo-600' : 'bg-gray-300 dark:bg-gray-600'" class="relative w-12 h-6 rounded-full transition-colors duration-200"><span :class="settings['privacy.activity_visible'] ? 'translate-x-6' : 'translate-x-1'" class="absolute top-1 left-0 w-4 h-4 bg-white rounded-full transition-transform duration-200"></span></button>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-gray-700 dark:text-gray-300 text-sm">{{ __('Partager les statistiques') }}</span>
                        <button @click="toggle('privacy.share_stats')" :class="settings['privacy.share_stats'] ? 'bg-indigo-600' : 'bg-gray-300 dark:bg-gray-600'" class="relative w-12 h-6 rounded-full transition-colors duration-200"><span :class="settings['privacy.share_stats'] ? 'translate-x-6' : 'translate-x-1'" class="absolute top-1 left-0 w-4 h-4 bg-white rounded-full transition-transform duration-200"></span></button>
                    </div>
                    <button @click="save()" :disabled="saving || !hasChanges()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-3 rounded-xl transition mt-2 disabled:opacity-50"><span x-show="!saving">{{ __('Sauvegarder') }}</span><span x-show="saving">{{ __('Sauvegarde...') }}</span></button>
                </div>
            </div>

            <!-- Appearance -->
            <div x-show="activeTab === 'appearance'">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6">{{ __('Apparence') }}</h2>
                <div class="space-y-4">
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-gray-700 dark:text-gray-300 text-sm">{{ __('Mode sombre') }}</span>
                        <button @click="toggleTheme()"
                            :class="settings.theme === 'dark' ? 'bg-indigo-600' : 'bg-gray-300 dark:bg-gray-600'" class="relative w-12 h-6 rounded-full transition-colors duration-200">
                            <span :class="settings.theme === 'dark' ? 'translate-x-6' : 'translate-x-1'" class="absolute top-1 left-0 w-4 h-4 bg-white rounded-full transition-transform duration-200"></span>
                        </button>
                    </div>
                    <button @click="save()" :disabled="saving || !hasChanges()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-3 rounded-xl transition mt-2 disabled:opacity-50"><span x-show="!saving">{{ __('Sauvegarder') }}</span><span x-show="saving">{{ __('Sauvegarde...') }}</span></button>
                </div>
            </div>
        </div>
    </div>

    <p x-show="lastSavedAt" class="mt-4 text-xs text-gray-500 dark:text-gray-400" x-text="`{{ __('Dernière sauvegarde') }}: ${lastSavedAt}`"></p>
</div>

<script>
function settingsPage() {
    return {
        activeTab: 'general',
        loading: false,
        saving: false,
        lastSavedAt: '',
        originalSettingsJson: '',
        settings: {
            locale: 'fr',
            theme: localStorage.getItem('dark') === 'true' ? 'dark' : 'light',
            'general.timezone': 'UTC+0',
            'general.currency': 'FCFA',
            'notifications.email': true,
            'notifications.push': true,
            'notifications.sms': false,
            'notifications.weekly': true,
            'privacy.profile_public': false,
            'privacy.activity_visible': false,
            'privacy.share_stats': false,
        },
        flash: { message: '', type: 'success' },
        get token() {
            return localStorage.getItem('token') || '';
        },
        showFlash(message, type = 'success') {
            this.flash = { message, type };
            setTimeout(() => { this.flash.message = ''; }, 3000);
        },
        toggle(key) {
            this.settings[key] = !this.settings[key];
        },
        hasChanges() {
            return JSON.stringify(this.settings) !== this.originalSettingsJson;
        },
        toggleTheme() {
            this.settings.theme = this.settings.theme === 'dark' ? 'light' : 'dark';
            const isDark = this.settings.theme === 'dark';
            document.documentElement.classList.toggle('dark', isDark);
            localStorage.setItem('dark', String(isDark));
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
        async init() {
            this.loading = true;
            try {
                const payload = await this.fetchJson('/api/settings/me');
                this.settings = { ...this.settings, ...(payload.data || {}) };
                this.originalSettingsJson = JSON.stringify(this.settings);
                const isDark = this.settings.theme === 'dark';
                document.documentElement.classList.toggle('dark', isDark);
                localStorage.setItem('dark', String(isDark));
            } catch (error) {
                this.showFlash(error.message || '{{ __('Impossible de charger les paramètres') }}', 'error');
            } finally {
                this.loading = false;
            }
        },
        async save() {
            if (!this.hasChanges()) {
                this.showFlash('{{ __('Aucun changement à sauvegarder.') }}', 'success');
                return;
            }

            this.saving = true;
            try {
                const previousLocale = JSON.parse(this.originalSettingsJson || '{}').locale || 'fr';
                const payload = await this.fetchJson('/api/settings/me', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    },
                    body: JSON.stringify({ settings: this.settings }),
                });

                this.settings = { ...this.settings, ...((payload.data || {}).settings || {}) };
                this.originalSettingsJson = JSON.stringify(this.settings);
                this.lastSavedAt = (payload.data || {}).saved_at || '';
                this.showFlash(payload.message || 'Paramètres sauvegardés.');

                if (this.settings.locale !== previousLocale) {
                    window.location.href = `${window.location.pathname}?lang=${encodeURIComponent(this.settings.locale)}`;
                    return;
                }
            } catch (error) {
                this.showFlash(error.message || '{{ __('Impossible de sauvegarder') }}', 'error');
            } finally {
                this.saving = false;
            }
        },
    };
}
</script>
@endsection
