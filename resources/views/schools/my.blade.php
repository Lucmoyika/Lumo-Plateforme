@extends('layouts.app')

@section('title', 'Mon établissement - Lumo Plateforme')

@section('content')
<div class="max-w-6xl mx-auto pb-24 md:pb-0" x-data="mySchoolPage()" x-init="init()">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-indigo-600 dark:text-indigo-300">Smart School</p>
            <h1 class="mt-1 text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Portail de pilotage de l'établissement</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm md:text-base max-w-2xl">Une entrée claire pour gérer l’école comme un vrai centre de commandement: classes, élèves, enseignants, présences et délégations.</p>
        </div>
        <a x-show="school" :href="school ? `/schools/${school.id}` : '/schools'" class="min-h-11 inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 rounded-2xl text-sm font-semibold shadow-sm">Ouvrir le détail</a>
    </div>

    <div x-show="flash.message" x-transition class="mb-4 px-4 py-3 rounded-lg text-sm"
        :class="flash.type === 'error' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'"
        x-text="flash.message"></div>

    <div x-show="loading" class="text-sm text-gray-500 dark:text-gray-400">Chargement...</div>

    <template x-if="school">
        <div>
            <div class="mb-5 rounded-3xl bg-gradient-to-r from-slate-950 via-indigo-950 to-cyan-900 p-6 md:p-8 text-white shadow-xl overflow-hidden relative border border-white/10">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.12),transparent_34%),radial-gradient(circle_at_bottom_left,rgba(59,130,246,0.22),transparent_38%)]"></div>
                <div class="relative z-10 flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-cyan-200">Dashboard Smart School</p>
                        <h2 class="mt-2 text-2xl md:text-4xl font-bold" x-text="school.name"></h2>
                        <p class="mt-2 max-w-2xl text-sm md:text-base text-cyan-100" x-text="`${school.city || 'Ville N/A'} · ${school.level_types?.length ? school.level_types.join(' · ') : (school.type || 'Niveau non défini')} · ${school.status || '-'}`"></p>
                    </div>
                    <a :href="`/schools/${school.id}`" class="min-h-11 inline-flex items-center justify-center rounded-2xl bg-white px-5 py-3 text-sm font-semibold text-indigo-700 shadow-lg hover:bg-indigo-50 transition">Ouvrir le portail complet</a>
                </div>

                <div class="relative z-10 mt-6 grid grid-cols-2 xl:grid-cols-4 gap-3 md:gap-4">
                    <div class="rounded-2xl bg-white/10 backdrop-blur p-4 border border-white/15">
                        <p class="text-xs uppercase tracking-wide text-indigo-100">Classes</p>
                        <p class="text-2xl font-bold mt-1" x-text="stats.classes"></p>
                    </div>
                    <div class="rounded-2xl bg-white/10 backdrop-blur p-4 border border-white/15">
                        <p class="text-xs uppercase tracking-wide text-indigo-100">Élèves</p>
                        <p class="text-2xl font-bold mt-1" x-text="stats.students"></p>
                    </div>
                    <div class="rounded-2xl bg-white/10 backdrop-blur p-4 border border-white/15">
                        <p class="text-xs uppercase tracking-wide text-indigo-100">Enseignants</p>
                        <p class="text-2xl font-bold mt-1" x-text="stats.teachers"></p>
                    </div>
                    <div class="rounded-2xl bg-white/10 backdrop-blur p-4 border border-white/15">
                        <p class="text-xs uppercase tracking-wide text-indigo-100">Membres gérés</p>
                        <p class="text-2xl font-bold mt-1" x-text="stats.members"></p>
                    </div>
                </div>
            </div>

            <div class="mb-6 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                <a :href="`/schools/${school.id}`" class="group rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5 shadow-sm hover:shadow-lg transition min-h-[170px]">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-indigo-600 dark:text-indigo-300">Vue globale</p>
                    <p class="mt-2 text-lg font-bold text-gray-900 dark:text-white group-hover:text-indigo-600 transition">Piloter l'école</p>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Accéder aux classes, élèves, enseignants, présences et notes.</p>
                </a>
                <a :href="`/schools/${school.id}#classes`" class="group rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5 shadow-sm hover:shadow-lg transition min-h-[170px]">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-emerald-600 dark:text-emerald-300">Scolarité</p>
                    <p class="mt-2 text-lg font-bold text-gray-900 dark:text-white group-hover:text-emerald-600 transition">Classes & niveaux</p>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Organiser les groupes et les sections du cycle scolaire.</p>
                </a>
                <a :href="`/schools/${school.id}#students`" class="group rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5 shadow-sm hover:shadow-lg transition min-h-[170px]">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-cyan-600 dark:text-cyan-300">Inscriptions</p>
                    <p class="mt-2 text-lg font-bold text-gray-900 dark:text-white group-hover:text-cyan-600 transition">Élèves</p>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Ajouter, importer et suivre les dossiers scolaires.</p>
                </a>
                <a :href="`/schools/${school.id}#members`" class="group rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5 shadow-sm hover:shadow-lg transition min-h-[170px]">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-violet-600 dark:text-violet-300">Direction</p>
                    <p class="mt-2 text-lg font-bold text-gray-900 dark:text-white group-hover:text-violet-600 transition">Délégations</p>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Attribuer des droits temporaires à l’équipe.</p>
                </a>
            </div>

            <div class="mb-6 rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm p-5 md:p-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-indigo-600 dark:text-indigo-300">Parcours actif</p>
                        <h3 class="mt-1 text-xl font-bold text-gray-900 dark:text-white">Le portail s’adapte au cycle de l’école</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Seuls les sous-modules qui correspondent à l’établissement sont mis en avant.</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="level in activeLevelTags" :key="level">
                            <span class="rounded-full border border-indigo-200 dark:border-indigo-800 bg-indigo-50 dark:bg-indigo-900/20 px-3 py-1 text-xs font-semibold text-indigo-700 dark:text-indigo-200" x-text="level"></span>
                        </template>
                    </div>
                </div>

                <div class="mt-5 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                    <template x-for="module in availableModules" :key="module.key">
                        <div class="rounded-2xl border p-4 shadow-sm" :class="module.active ? 'border-indigo-300 bg-indigo-50/70 dark:bg-indigo-900/20' : 'border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800'">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-indigo-600 dark:text-indigo-300" x-text="module.badge"></p>
                                    <h4 class="mt-2 text-base font-bold text-gray-900 dark:text-white" x-text="module.label"></h4>
                                </div>
                                <span class="rounded-full px-2.5 py-1 text-[11px] font-semibold" :class="module.active ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-600 dark:bg-gray-700 dark:text-gray-300'" x-text="module.active ? 'Actif' : 'Disponible'"></span>
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

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                <a :href="`/schools/${school.id}`" class="group rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5 shadow-sm hover:shadow-md transition min-h-[150px]">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-indigo-600 dark:text-indigo-300">Portail complet</p>
                    <p class="mt-2 text-lg font-bold text-gray-900 dark:text-white group-hover:text-indigo-600 transition">Gérer l'établissement</p>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Classes, élèves, enseignants, présences, notes et tâches.</p>
                </a>
                <a x-show="can('schools.delegate')" :href="`/schools/${school.id}`" class="group rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5 shadow-sm hover:shadow-md transition min-h-[150px]">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-emerald-600 dark:text-emerald-300">Délégation</p>
                    <p class="mt-2 text-lg font-bold text-gray-900 dark:text-white group-hover:text-emerald-600 transition">Membres & droits</p>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Attribuer des responsabilités temporaires à l'équipe.</p>
                </a>
                <a href="/profile" class="group rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5 shadow-sm hover:shadow-md transition min-h-[150px]">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-violet-600 dark:text-violet-300">Compte</p>
                    <p class="mt-2 text-lg font-bold text-gray-900 dark:text-white group-hover:text-violet-600 transition">Mon profil</p>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Informations personnelles et préférences.</p>
                </a>
                <a href="/settings" class="group rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5 shadow-sm hover:shadow-md transition min-h-[150px]">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-600 dark:text-slate-300">Préférences</p>
                    <p class="mt-2 text-lg font-bold text-gray-900 dark:text-white group-hover:text-slate-600 transition">Mes paramètres</p>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Langue, sécurité et paramètres du compte.</p>
                </a>
            </div>

            <div class="fixed left-0 right-0 bottom-0 z-40 px-4 pb-4 md:hidden">
                <div class="rounded-3xl border border-gray-200 bg-white shadow-2xl overflow-hidden">
                    <div class="grid grid-cols-4">
                        <a :href="`/schools/${school.id}`" class="min-h-16 flex flex-col items-center justify-center gap-1 text-xs font-semibold text-gray-700">
                            <span class="text-lg">🏫</span>
                            <span>Portail</span>
                        </a>
                        <a :href="`/schools/${school.id}`" class="min-h-16 flex flex-col items-center justify-center gap-1 text-xs font-semibold text-gray-700">
                            <span class="text-lg">⚙️</span>
                            <span>École</span>
                        </a>
                        <a href="/profile" class="min-h-16 flex flex-col items-center justify-center gap-1 text-xs font-semibold text-gray-700">
                            <span class="text-lg">👤</span>
                            <span>Profil</span>
                        </a>
                        <a href="/settings" class="min-h-16 flex flex-col items-center justify-center gap-1 text-xs font-semibold text-gray-700">
                            <span class="text-lg">⚙︎</span>
                            <span>Réglages</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <div x-show="!loading && !school" class="bg-yellow-50 text-yellow-800 rounded-lg p-4 text-sm">
        Aucun établissement rattaché à votre compte.
    </div>
</div>

<script>
function mySchoolPage() {
    return {
        loading: false,
        school: null,
        stats: {
            classes: 0,
            students: 0,
            teachers: 0,
            members: 0,
        },
        permissions: [],
        flash: { message: '', type: 'success' },
        currentRole: '',
        currentUserId: null,
        activeLevelTags: [],
        availableModules: [],
        schoolModules: @json(config('school_modules.submodules')),
        get token() {
            return localStorage.getItem('token') || '';
        },
        can(permission) {
            const user = JSON.parse(localStorage.getItem('user') || '{}');
            const isSuperAdmin = user?.role === 'super_admin';
            return isSuperAdmin || this.permissions.includes(permission);
        },
        showFlash(message, type = 'error') {
            this.flash = { message, type };
            setTimeout(() => { this.flash.message = ''; }, 3000);
        },
        async fetchJson(url) {
            const response = await fetch(url, {
                headers: {
                    Accept: 'application/json',
                    Authorization: `Bearer ${this.token}`,
                },
                credentials: 'same-origin',
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
                const mePayload = await this.fetchJson('/api/auth/me');
                const user = mePayload.data || {};
                localStorage.setItem('user', JSON.stringify(user));
                this.currentUserId = user?.id ?? null;
                this.currentRole = user?.role || (user?.roles || [])[0]?.name || '';

                const rolePermissions = (user.roles || []).flatMap((role) => (role.permissions || []).map((permission) => permission.name));
                const directPermissions = (user.permissions || []).map((permission) => permission.name);
                this.permissions = [...new Set([...rolePermissions, ...directPermissions])];

                this.school = user.school || null;

                if (!this.school) {
                    const schoolsPayload = await this.fetchJson('/api/schools?per_page=10');
                    const schools = schoolsPayload.data || [];
                    this.school = schools[0] || null;
                }

                if (!this.school) {
                    return;
                }

                this.school.director_id = this.school.director_id ?? null;
                this.computeModules();

                const [classesPayload, studentsPayload, teachersPayload] = await Promise.all([
                    this.can('school-classes.view') ? this.fetchJson(`/api/schools/${this.school.id}/classes`) : Promise.resolve({ data: [] }),
                    this.can('students.view') ? this.fetchJson(`/api/schools/${this.school.id}/students`) : Promise.resolve({ data: [], meta: { total: 0 } }),
                    this.can('teachers.view') ? this.fetchJson(`/api/schools/${this.school.id}/teachers`) : Promise.resolve({ data: [], meta: { total: 0 } }),
                ]);

                this.stats.classes = (classesPayload.data || []).length;
                this.stats.students = studentsPayload.meta?.total || (studentsPayload.data || []).length;
                this.stats.teachers = teachersPayload.meta?.total || (teachersPayload.data || []).length;

                if (this.can('schools.delegate')) {
                    const membersPayload = await this.fetchJson(`/api/schools/${this.school.id}/members`);
                    this.stats.members = (membersPayload.data?.members || []).length;
                }

                this.computeModules();
            } catch (error) {
                this.showFlash(error.message || 'Impossible de charger votre établissement');
            } finally {
                this.loading = false;
            }
        },
        computeModules() {
            const levelTypes = Array.isArray(this.school?.level_types) ? this.school.level_types : [];
            const normalizedLevels = [...new Set(levelTypes)].sort().join('|');
            const labels = {
                maternelle: 'Maternelle',
                primaire: 'Primaire',
                secondaire: 'Secondaire',
                humanites: 'Humanités',
            };

            this.activeLevelTags = levelTypes.map((level) => labels[level] || level);

            this.availableModules = Object.values(this.schoolModules || {}).map((module) => {
                const moduleLevels = [...new Set(module.level_types || [])].sort().join('|');
                return {
                    ...module,
                    active: moduleLevels === normalizedLevels,
                    levels: (module.level_types || []).map((level) => labels[level] || level),
                    description: module.key === 'mp'
                        ? 'Parcours simplifié pour les cycles maternelle et primaire.'
                        : module.key === 'ps'
                            ? 'Gestion mixte pour les écoles qui combinent primaire et secondaire.'
                            : module.key === 'sh'
                                ? 'Suivi renforcé pour le secondaire et les humanités.'
                                : 'Portail complet pour l’ensemble des niveaux.',
                };
            });
        },
    };
}
</script>
@endsection
