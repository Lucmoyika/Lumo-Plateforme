@extends('layouts.app')

@section('title', 'Tableau de bord - Lumo Plateforme')

@section('content')
<div x-data="dashboard()" x-init="loadData()">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-2">Tableau de bord</h1>
    <p class="text-gray-500 dark:text-gray-400 mb-6 md:mb-8">Vue personnalisée selon vos droits et votre établissement</p>

    <div x-show="loading" class="text-sm text-gray-500 dark:text-gray-400 mb-6">Chargement du tableau de bord...</div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6 mb-8" x-show="!loading">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 md:p-6 border-l-4 border-indigo-500">
            <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400" x-text="isSchoolScoped ? 'Mon établissement' : 'Établissements visibles'"></p>
            <p class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white mt-1" x-text="stats.schools"></p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 md:p-6 border-l-4 border-purple-500" x-show="can('school-classes.view')">
            <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400">Classes</p>
            <p class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white mt-1" x-text="stats.classes"></p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 md:p-6 border-l-4 border-blue-500" x-show="can('students.view')">
            <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400">Élèves</p>
            <p class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white mt-1" x-text="stats.students"></p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 md:p-6 border-l-4 border-green-500" x-show="can('teachers.view')">
            <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400">Enseignants</p>
            <p class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white mt-1" x-text="stats.teachers"></p>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 md:p-6 border border-gray-100 dark:border-gray-700 mb-8" x-show="schoolContext">
        <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-1">Contexte actif</p>
        <p class="text-lg font-semibold text-gray-900 dark:text-white" x-text="schoolContext?.name"></p>
        <p class="text-sm text-gray-500 dark:text-gray-400" x-text="`${schoolContext?.city || 'Ville N/A'} · ${schoolContext?.type || '-'}`"></p>
    </div>

    <h2 class="text-lg md:text-xl font-bold text-gray-900 dark:text-white mb-4">Accès rapide</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4">
        <template x-for="item in quickAccess" :key="item.href">
            <a :href="item.href" class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 text-center hover:shadow-lg transition border border-gray-100 dark:border-gray-700">
                <div class="text-2xl md:text-3xl mb-2" x-text="item.emoji"></div>
                <p class="text-xs md:text-sm font-medium text-gray-700 dark:text-gray-300" x-text="item.label"></p>
            </a>
        </template>
    </div>

    <template x-if="schoolContext && can('schools.view')">
        <div class="fixed bottom-4 left-4 right-4 md:hidden z-10">
            <a :href="`/schools/${schoolContext.id}`" class="block text-center bg-indigo-600 text-white py-3 rounded-xl shadow-lg font-medium">Ouvrir mon établissement</a>
        </div>
    </template>
</div>

<script>
function dashboard() {
    return {
        loading: false,
        token: localStorage.getItem('token') || '',
        currentUser: null,
        permissions: [],
        quickAccess: [],
        schoolContext: null,
        isSchoolScoped: false,
        stats: {
            schools: 0,
            classes: 0,
            students: 0,
            teachers: 0,
        },
        can(permission) {
            const isSuperAdmin = this.currentUser?.role === 'super_admin';
            return isSuperAdmin || this.permissions.includes(permission);
        },
        parsePermissions(user) {
            const rolePermissions = (user?.roles || []).flatMap((role) => (role.permissions || []).map((permission) => permission.name));
            const directPermissions = (user?.permissions || []).map((permission) => permission.name);
            this.permissions = [...new Set([...rolePermissions, ...directPermissions])];
        },
        async fetchJson(url) {
            const response = await fetch(url, {
                headers: {
                    Accept: 'application/json',
                    Authorization: `Bearer ${this.token}`,
                },
            });

            const payload = await response.json();
            if (!response.ok || !payload.success) {
                throw new Error(payload.message || 'Erreur API');
            }

            return payload;
        },
        buildQuickAccess() {
            const modules = [
                { href: '/schools', emoji: '🏫', label: 'Écoles', permission: 'schools.view' },
                { href: '/universities', emoji: '🎓', label: 'Universités', permission: 'universities.view' },
                { href: '/jobs', emoji: '💼', label: 'Emplois', permission: 'jobs.view' },
                { href: '/shop', emoji: '🛒', label: 'Boutique', permission: 'products.view' },
                { href: '/chat', emoji: '💬', label: 'Messages', permission: 'conversations.view' },
                { href: '/wallet', emoji: '💳', label: 'Wallet', permission: 'wallet.view' },
                { href: '/videos', emoji: '🎥', label: 'Vidéos', permission: 'videos.view' },
                { href: '/profile', emoji: '👤', label: 'Profil', permission: null },
            ];

            this.quickAccess = modules.filter((item) => !item.permission || this.can(item.permission));
        },
        async loadData() {
            this.loading = true;
            try {
                const mePayload = await this.fetchJson('/api/auth/me');
                this.currentUser = mePayload.data || null;
                this.parsePermissions(this.currentUser);
                this.schoolContext = this.currentUser?.school || null;

                if (this.can('schools.view')) {
                    const schoolsPayload = await this.fetchJson('/api/schools?per_page=20');
                    const schools = schoolsPayload.data || [];
                    this.stats.schools = schools.length;

                    if (schools.length === 1) {
                        this.isSchoolScoped = true;
                        this.schoolContext = schools[0];
                    }

                    const targetSchool = this.schoolContext || schools[0] || null;
                    if (targetSchool) {
                        const loaders = [];

                        if (this.can('school-classes.view')) {
                            loaders.push(this.fetchJson(`/api/schools/${targetSchool.id}/classes`).then((payload) => {
                                this.stats.classes = (payload.data || []).length;
                            }));
                        }

                        if (this.can('students.view')) {
                            loaders.push(this.fetchJson(`/api/schools/${targetSchool.id}/students`).then((payload) => {
                                this.stats.students = payload.meta?.total || (payload.data || []).length;
                            }));
                        }

                        if (this.can('teachers.view')) {
                            loaders.push(this.fetchJson(`/api/schools/${targetSchool.id}/teachers`).then((payload) => {
                                this.stats.teachers = payload.meta?.total || (payload.data || []).length;
                            }));
                        }

                        await Promise.allSettled(loaders);
                    }
                }

                this.buildQuickAccess();
            } catch (_) {
                this.quickAccess = [{ href: '/profile', emoji: '👤', label: 'Profil' }];
            } finally {
                this.loading = false;
            }
        },
    }
}
</script>
@endsection
