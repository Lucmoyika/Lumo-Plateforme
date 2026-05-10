@extends('layouts.app')

@push('head')
    @if(!empty($school))
        <meta name="description" content="{{ $school->name }} à {{ $school->city ?? 'ville non définie' }}. Détail, classes, élèves, enseignants et délégations." />
        <meta property="og:type" content="website" />
        <meta property="og:title" content="{{ $school->name }} - Lumo Plateforme" />
        <meta property="og:description" content="{{ $school->name }} à {{ $school->city ?? 'ville non définie' }}. Détail, classes, élèves, enseignants et délégations." />
        <meta property="og:url" content="{{ url('/schools/' . $school->id) }}" />
        <link rel="canonical" href="{{ url('/schools/' . $school->id) }}" />
    @endif
@endpush

@section('title', !empty($school) ? $school->name . ' - Lumo Plateforme' : 'Détail École - Lumo Plateforme')
@section('content')
<div x-data='schoolShowPage({{ (int)($schoolId ?? 0) }}, @json($school ?? null))' x-init="init()">
    <div class="mb-4 md:mb-6 flex items-center gap-3">
        <a href="/schools" class="text-sm text-indigo-600 hover:underline">← Retour aux écoles</a>
        <span class="text-xs text-gray-400">/</span>
        <span class="text-sm text-gray-500 dark:text-gray-400">Détail école</span>
    </div>

    <div x-show="flash.message" x-transition class="mb-4 px-4 py-3 rounded-lg text-sm"
        :class="flash.type === 'error' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'"
        x-text="flash.message"></div>

    <div x-show="loading" class="text-sm text-gray-500 dark:text-gray-400">Chargement...</div>

    <template x-if="school">
        <div>
            <div class="rounded-3xl bg-gradient-to-r from-slate-950 via-indigo-950 to-indigo-800 mb-6 text-white shadow-xl overflow-hidden relative border border-white/10 p-6 md:p-8">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.12),transparent_34%),radial-gradient(circle_at_bottom_left,rgba(59,130,246,0.22),transparent_38%)]"></div>
                <div class="relative z-10 flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                    <div class="flex items-start gap-5">
                        <div class="w-16 h-16 bg-white/15 backdrop-blur rounded-2xl flex items-center justify-center text-4xl border border-white/15">🏫</div>
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-indigo-200">Établissement</p>
                            <h1 class="text-2xl md:text-4xl font-bold mt-2" x-text="school.name"></h1>
                            <p class="text-indigo-100 mt-2" x-text="`📍 ${school.city || 'Ville non définie'} · Niveaux: ${formatLevelTypes(school.level_types)}`"></p>
                            <p class="text-indigo-200 mt-1 text-sm" x-text="school.email || 'Email non défini'"></p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 min-w-[240px]">
                        <div class="rounded-2xl bg-white/10 backdrop-blur border border-white/15 p-4">
                            <p class="text-xs uppercase tracking-wide text-indigo-100">Classes</p>
                            <p class="mt-1 text-2xl font-bold" x-text="classes.length"></p>
                        </div>
                        <div class="rounded-2xl bg-white/10 backdrop-blur border border-white/15 p-4">
                            <p class="text-xs uppercase tracking-wide text-indigo-100">Élèves</p>
                            <p class="mt-1 text-2xl font-bold" x-text="students.length"></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-6 grid grid-cols-1 xl:grid-cols-[320px_minmax(0,1fr)] gap-6">
                <div class="rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm p-5 md:p-6">
                    <div class="flex items-center justify-between gap-3 mb-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-indigo-600 dark:text-indigo-300">Current Session</p>
                            <p class="mt-1 text-lg font-bold text-gray-900 dark:text-white" x-text="selectedAcademicYear || schoolYears[0] || '2025-2026'"></p>
                        </div>
                        <span class="rounded-full bg-indigo-50 dark:bg-indigo-900/30 px-3 py-1 text-xs font-semibold text-indigo-700 dark:text-indigo-200">Smart School</span>
                    </div>

                    <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 p-4">
                        <label class="block text-xs font-semibold uppercase tracking-[0.2em] text-gray-500 dark:text-gray-400 mb-2">Recherche rapide</label>
                        <input type="text" placeholder="Student Name, Roll Number, Enroll Number..." class="w-full rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-3 text-sm">
                    </div>

                    <div class="mt-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-500 dark:text-gray-400 mb-3">Quick Links</p>
                        <div class="space-y-2">
                            <button type="button" @click="activeTab = 'students'" class="w-full flex items-center justify-between rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 px-4 py-3 text-left text-sm font-semibold text-gray-800 dark:text-gray-100 hover:border-indigo-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition">
                                <span>Student Information</span>
                                <span class="text-xs text-gray-400">➜</span>
                            </button>
                            <button type="button" @click="activeTab = 'attendance'" class="w-full flex items-center justify-between rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 px-4 py-3 text-left text-sm font-semibold text-gray-800 dark:text-gray-100 hover:border-emerald-300 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition">
                                <span>Attendance</span>
                                <span class="text-xs text-gray-400">➜</span>
                            </button>
                            <button type="button" @click="activeTab = 'grades'" class="w-full flex items-center justify-between rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 px-4 py-3 text-left text-sm font-semibold text-gray-800 dark:text-gray-100 hover:border-violet-300 hover:bg-violet-50 dark:hover:bg-violet-900/20 transition">
                                <span>Examinations</span>
                                <span class="text-xs text-gray-400">➜</span>
                            </button>
                            <button type="button" @click="activeTab = 'courses'" class="w-full flex items-center justify-between rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 px-4 py-3 text-left text-sm font-semibold text-gray-800 dark:text-gray-100 hover:border-cyan-300 hover:bg-cyan-50 dark:hover:bg-cyan-900/20 transition">
                                <span>Academics</span>
                                <span class="text-xs text-gray-400">➜</span>
                            </button>
                            <button type="button" @click="activeTab = 'tasks'" class="w-full flex items-center justify-between rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 px-4 py-3 text-left text-sm font-semibold text-gray-800 dark:text-gray-100 hover:border-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition">
                                <span>Lesson Plan</span>
                                <span class="text-xs text-gray-400">➜</span>
                            </button>
                            <button type="button" @click="activeTab = 'teachers'" class="w-full flex items-center justify-between rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 px-4 py-3 text-left text-sm font-semibold text-gray-800 dark:text-gray-100 hover:border-amber-300 hover:bg-amber-50 dark:hover:bg-amber-900/20 transition">
                                <span>Human Resource</span>
                                <span class="text-xs text-gray-400">➜</span>
                            </button>
                            <button type="button" @click="activeTab = 'members'" class="w-full flex items-center justify-between rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 px-4 py-3 text-left text-sm font-semibold text-gray-800 dark:text-gray-100 hover:border-indigo-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition">
                                <span>System Setting</span>
                                <span class="text-xs text-gray-400">➜</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="rounded-3xl border border-indigo-200 dark:border-indigo-800 bg-indigo-50/70 dark:bg-indigo-900/20 p-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-indigo-600 dark:text-indigo-300">What's New In School</p>
                            <div class="mt-3 space-y-4">
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">Notice for new Book collection</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Informer les classes de la disponibilité des nouveaux supports pédagogiques.</p>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">Fee Submission Reminder</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Suivre les paiements et rappeler les échéances aux familles.</p>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-gray-500 dark:text-gray-400">Admin Login</p>
                            <div class="mt-3 grid grid-cols-2 gap-3 text-sm">
                                <a href="#overview" class="rounded-2xl bg-gray-50 dark:bg-gray-900/40 px-4 py-3 font-semibold text-gray-800 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-800 transition">Overview</a>
                                <a href="#classes" class="rounded-2xl bg-gray-50 dark:bg-gray-900/40 px-4 py-3 font-semibold text-gray-800 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-800 transition">Classes</a>
                                <a href="#students" class="rounded-2xl bg-gray-50 dark:bg-gray-900/40 px-4 py-3 font-semibold text-gray-800 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-800 transition">Students</a>
                                <a href="#members" class="rounded-2xl bg-gray-50 dark:bg-gray-900/40 px-4 py-3 font-semibold text-gray-800 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-800 transition">System</a>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5 shadow-sm">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-gray-500 dark:text-gray-400">Quick Access</p>
                                <h3 class="mt-1 text-lg font-bold text-gray-900 dark:text-white">Menus du portail</h3>
                            </div>
                            <button type="button" @click="activeTab = 'overview'" class="rounded-full bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white">Vue d'ensemble</button>
                        </div>
                        <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-3 text-xs font-semibold text-gray-700 dark:text-gray-200">
                            <button type="button" @click="activeTab = 'overview'" class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 px-3 py-3 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition">Overview</button>
                            <button type="button" @click="activeTab = 'classes'" class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 px-3 py-3 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition">Classes</button>
                            <button type="button" @click="activeTab = 'students'" class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 px-3 py-3 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition">Students</button>
                            <button type="button" @click="activeTab = 'teachers'" class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 px-3 py-3 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition">Teachers</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-6 rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm p-5 md:p-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-indigo-600 dark:text-indigo-300">Sous-modules école</p>
                        <h2 class="mt-1 text-xl md:text-2xl font-bold text-gray-900 dark:text-white">Le portail s’ajuste au cycle réel de l’établissement</h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Les modules actifs sont liés à la structure de l’école et peuvent couvrir un ensemble partiel ou complet des niveaux.</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="level in activeLevelTags" :key="level">
                            <span class="rounded-full border border-indigo-200 dark:border-indigo-800 bg-indigo-50 dark:bg-indigo-900/20 px-3 py-1 text-xs font-semibold text-indigo-700 dark:text-indigo-200" x-text="level"></span>
                        </template>
                    </div>
                </div>

                <div class="mt-5 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                    <template x-for="module in moduleCards" :key="module.key">
                        <div class="rounded-2xl border p-4 shadow-sm transition" :class="module.active ? 'border-indigo-300 bg-indigo-50/70 dark:bg-indigo-900/20' : 'border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800'">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-indigo-600 dark:text-indigo-300" x-text="module.badge"></p>
                                    <h3 class="mt-2 text-base font-bold text-gray-900 dark:text-white" x-text="module.label"></h3>
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

            <div class="mb-6 rounded-3xl border border-indigo-200 dark:border-indigo-800 bg-gradient-to-br from-indigo-50 via-white to-cyan-50 dark:from-indigo-950/40 dark:via-slate-900 dark:to-cyan-950/30 shadow-sm p-5 md:p-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-indigo-600 dark:text-indigo-300">Par où commencer</p>
                        <h2 class="mt-1 text-xl md:text-2xl font-bold text-gray-900 dark:text-white">Actions utiles, selon le rôle</h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Le directeur pilote l’établissement. Le staff gère l’opérationnel. L’enseignant suit ses classes. Le parent et l’élève suivent la vie scolaire.</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="#overview" class="rounded-full bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 transition">Vue d'ensemble</a>
                        <a href="#classes" class="rounded-full bg-white px-4 py-2 text-sm font-semibold text-indigo-700 border border-indigo-200 hover:bg-indigo-50 transition dark:bg-slate-900 dark:text-indigo-200 dark:border-indigo-800">Classes</a>
                        <a href="#students" class="rounded-full bg-white px-4 py-2 text-sm font-semibold text-indigo-700 border border-indigo-200 hover:bg-indigo-50 transition dark:bg-slate-900 dark:text-indigo-200 dark:border-indigo-800">Élèves</a>
                        <a href="#teachers" class="rounded-full bg-white px-4 py-2 text-sm font-semibold text-indigo-700 border border-indigo-200 hover:bg-indigo-50 transition dark:bg-slate-900 dark:text-indigo-200 dark:border-indigo-800">Enseignants</a>
                        <a href="#members" class="rounded-full bg-white px-4 py-2 text-sm font-semibold text-indigo-700 border border-indigo-200 hover:bg-indigo-50 transition dark:bg-slate-900 dark:text-indigo-200 dark:border-indigo-800">Délégations</a>
                    </div>
                </div>

                <div class="mt-5 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4">
                    <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4">
                        <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Directeur</p>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Créer les classes, affecter les enseignants, déléguer les droits, suivre les présences et les notes.</p>
                    </div>
                    <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4">
                        <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Staff</p>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Préparer les dossiers, enregistrer les élèves et coordonner avec la direction.</p>
                    </div>
                    <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4">
                        <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Enseignant</p>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Voir ses classes, gérer les présences, saisir les notes et consulter l’emploi du temps.</p>
                    </div>
                    <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4">
                        <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Élève</p>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Consulter bulletins, assiduité, cours et calendrier scolaire.</p>
                    </div>
                    <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4">
                        <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Parent</p>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Suivre les notes, l’assiduité et recevoir les informations importantes.</p>
                    </div>
                </div>
            </div>

            <div class="mb-6 rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm p-5 md:p-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-indigo-600 dark:text-indigo-300">Portail par rôle</p>
                        <h2 class="mt-1 text-xl md:text-2xl font-bold text-gray-900 dark:text-white">Une expérience claire, selon le profil de chacun</h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Le directeur voit tout. Le staff, l’enseignant, l’élève et le parent ne voient que leurs fonctions utiles.</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <span class="rounded-full px-3 py-1 text-xs font-semibold" :class="isDirector ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-200'">Directeur</span>
                        <span class="rounded-full px-3 py-1 text-xs font-semibold" :class="currentRole === 'school_staff' ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-200'">Staff</span>
                        <span class="rounded-full px-3 py-1 text-xs font-semibold" :class="currentRole === 'teacher' ? 'bg-violet-600 text-white' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-200'">Enseignant</span>
                        <span class="rounded-full px-3 py-1 text-xs font-semibold" :class="currentRole === 'student' ? 'bg-cyan-600 text-white' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-200'">Élève</span>
                        <span class="rounded-full px-3 py-1 text-xs font-semibold" :class="currentRole === 'parent' ? 'bg-amber-600 text-white' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-200'">Parent</span>
                    </div>
                </div>

                <div class="mt-5 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4">
                    <template x-for="card in roleCards" :key="card.key">
                        <div class="rounded-2xl border p-4 transition shadow-sm" :class="currentRole === card.key || (card.key === 'director' && isDirector) ? 'border-indigo-300 bg-indigo-50/70 dark:bg-indigo-900/20' : 'border-gray-200 dark:border-gray-700 bg-gray-50/80 dark:bg-gray-750'">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white" x-text="card.title"></p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1" x-text="card.subtitle"></p>
                                </div>
                                <span x-show="currentRole === card.key || (card.key === 'director' && isDirector)" class="rounded-full bg-indigo-600 px-2.5 py-1 text-[11px] font-semibold text-white">Actif</span>
                            </div>
                            <ul class="mt-3 space-y-1 text-xs text-gray-600 dark:text-gray-300">
                                <template x-for="feature in card.features" :key="`${card.key}-${feature}`">
                                    <li x-text="`• ${feature}`"></li>
                                </template>
                            </ul>
                        </div>
                    </template>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden" id="overview">
                <div class="flex border-b border-gray-100 dark:border-gray-700 overflow-x-auto">
                    <button @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-3 text-sm font-medium transition whitespace-nowrap">Vue d'ensemble</button>
                    <button x-show="can('school-classes.view')" @click="activeTab = 'classes'" :class="activeTab === 'classes' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-3 text-sm font-medium transition whitespace-nowrap">Classes</button>
                    <button x-show="can('students.view')" @click="activeTab = 'students'" :class="activeTab === 'students' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-3 text-sm font-medium transition whitespace-nowrap">Élèves</button>
                    <button x-show="can('teachers.view')" @click="activeTab = 'teachers'" :class="activeTab === 'teachers' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-3 text-sm font-medium transition whitespace-nowrap">Enseignants</button>
                    <button x-show="can('attendance.view')" @click="activeTab = 'attendance'" :class="activeTab === 'attendance' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-3 text-sm font-medium transition whitespace-nowrap">Présences</button>
                    <button x-show="can('grades.view')" @click="activeTab = 'grades'" :class="activeTab === 'grades' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-3 text-sm font-medium transition whitespace-nowrap">Notes</button>
                    <button x-show="can('schools.view')" @click="activeTab = 'schoolYears'" :class="activeTab === 'schoolYears' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-3 text-sm font-medium transition whitespace-nowrap">Année scolaire</button>
                    <button x-show="can('school-classes.view')" @click="activeTab = 'courses'" :class="activeTab === 'courses' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-3 text-sm font-medium transition whitespace-nowrap">Cours & EDT</button>
                    <button x-show="can('tasks.view')" @click="activeTab = 'tasks'" :class="activeTab === 'tasks' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-3 text-sm font-medium transition whitespace-nowrap">Tâches</button>
                    <button x-show="can('schools.delegate')" @click="activeTab = 'members'" :class="activeTab === 'members' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-3 text-sm font-medium transition whitespace-nowrap">Membres & droits</button>
                </div>

                <div class="p-6">
                    <div x-show="activeTab === 'overview'" id="overview-panel">
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mb-6">
                            <div class="bg-blue-50 dark:bg-blue-900 rounded-2xl p-4 text-center">
                                <p class="text-3xl font-bold text-blue-700 dark:text-blue-300" x-text="students.length"></p>
                                <p class="text-sm text-blue-600 dark:text-blue-400 mt-1">Élèves</p>
                            </div>
                            <div class="bg-green-50 dark:bg-green-900 rounded-2xl p-4 text-center">
                                <p class="text-3xl font-bold text-green-700 dark:text-green-300" x-text="teachers.length"></p>
                                <p class="text-sm text-green-600 dark:text-green-400 mt-1">Enseignants</p>
                            </div>
                            <div class="bg-purple-50 dark:bg-purple-900 rounded-2xl p-4 text-center">
                                <p class="text-3xl font-bold text-purple-700 dark:text-purple-300" x-text="classes.length"></p>
                                <p class="text-sm text-purple-600 dark:text-purple-400 mt-1">Classes</p>
                            </div>
                            <div class="bg-orange-50 dark:bg-orange-900 rounded-2xl p-4 text-center">
                                <p class="text-lg font-bold text-orange-700 dark:text-orange-300" x-text="school.status"></p>
                                <p class="text-sm text-orange-600 dark:text-orange-400 mt-1">Statut</p>
                            </div>
                        </div>

                        <div class="bg-indigo-50 dark:bg-indigo-900 rounded-2xl p-5">
                            <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Informations</h3>
                            <p class="text-sm text-gray-700 dark:text-gray-300" x-text="school.description || 'Aucune description disponible.'"></p>
                            <div class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                                <span x-text="`Adresse: ${school.address || 'N/A'}`"></span>
                                <span class="mx-2">•</span>
                                <span x-text="`Téléphone: ${school.phone || 'N/A'}`"></span>
                            </div>
                        </div>
                    </div>

                    <div x-show="activeTab === 'classes'" id="classes">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <template x-for="item in classes" :key="item.id">
                                <div class="bg-white dark:bg-gray-750 border border-gray-200 dark:border-gray-700 rounded-2xl p-5">
                                    <h3 class="font-semibold text-gray-900 dark:text-white" x-text="item.name || item.label || `Classe #${item.id}`"></h3>
                                    <p class="text-sm text-gray-500 mt-1" x-text="`Niveau: ${item.level || 'N/A'}`"></p>
                                </div>
                            </template>
                        </div>
                        <p x-show="classes.length === 0" class="text-sm text-gray-500">Aucune classe disponible.</p>
                    </div>

                    <div x-show="activeTab === 'students'" id="students">
                        <div x-show="can('students.create')" class="mb-5 rounded-2xl border border-indigo-200 dark:border-indigo-800 bg-indigo-50/70 dark:bg-indigo-900/20 p-4">
                            <h3 class="font-semibold text-gray-900 dark:text-white mb-1">Import CSV des élèves</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">Ajoutez un fichier CSV avec au minimum les colonnes <span class="font-medium">name</span> et <span class="font-medium">email</span>.</p>
                            <div class="flex flex-col md:flex-row gap-3 md:items-center">
                                <input x-ref="studentsImportFile" type="file" accept=".csv,.txt" class="block w-full text-sm text-gray-700 dark:text-gray-300 file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-600 file:px-4 file:py-2 file:text-white hover:file:bg-indigo-700">
                                <button @click="importStudentsCsv()" :disabled="importingStudents" class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-medium hover:bg-indigo-700 disabled:opacity-50 whitespace-nowrap" x-text="importingStudents ? 'Import en cours...' : 'Importer les élèves'"></button>
                            </div>
                        </div>

                        <div class="hidden md:block overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="text-left text-xs text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700">
                                        <th class="pb-2 font-medium">Nom</th>
                                        <th class="pb-2 font-medium">Classe</th>
                                        <th class="pb-2 font-medium">Statut</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                                    <template x-for="item in students" :key="item.id">
                                        <tr>
                                            <td class="py-3 text-sm font-medium text-gray-900 dark:text-white" x-text="item.name || `${item.first_name || ''} ${item.last_name || ''}`.trim() || `Élève #${item.id}`"></td>
                                            <td class="py-3 text-sm text-gray-600 dark:text-gray-400" x-text="item.class_name || item.class_?.name || item.class?.name || '-' "></td>
                                            <td class="py-3 text-sm text-gray-600 dark:text-gray-400" x-text="item.status || 'active'"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                        <div class="md:hidden space-y-2">
                            <template x-for="item in students" :key="`mobile-${item.id}`">
                                <div class="rounded-xl border border-gray-200 dark:border-gray-700 p-3 bg-white dark:bg-gray-800">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white" x-text="item.name || `${item.first_name || ''} ${item.last_name || ''}`.trim() || `Élève #${item.id}`"></p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400" x-text="`Classe: ${item.class_name || item.class_?.name || item.class?.name || '-'}`"></p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400" x-text="`Statut: ${item.status || 'active'}`"></p>
                                </div>
                            </template>
                        </div>
                        <p x-show="students.length === 0" class="text-sm text-gray-500">Aucun élève disponible.</p>
                    </div>

                    <div x-show="activeTab === 'teachers'" id="teachers">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <template x-for="item in teachers" :key="item.id">
                                <div class="bg-gray-50 dark:bg-gray-750 rounded-2xl p-4">
                                    <p class="font-medium text-gray-900 dark:text-white text-sm" x-text="item.name || `${item.first_name || ''} ${item.last_name || ''}`.trim() || `Enseignant #${item.id}`"></p>
                                    <p class="text-xs text-gray-500" x-text="item.subject || 'Matière non définie'"></p>
                                </div>
                            </template>
                        </div>
                        <p x-show="teachers.length === 0" class="text-sm text-gray-500">Aucun enseignant disponible.</p>
                    </div>

                    <div x-show="activeTab === 'attendance'">
                        <div class="mb-5 rounded-2xl border border-emerald-200 dark:border-emerald-800 bg-emerald-50/70 dark:bg-emerald-900/20 p-4">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <select x-model="attendanceForm.class_id" @change="loadAttendanceData()" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2.5">
                                    <option value="">Choisir une classe</option>
                                    <template x-for="item in classes" :key="`att-class-${item.id}`">
                                        <option :value="item.id" x-text="item.name || item.label || `Classe #${item.id}`"></option>
                                    </template>
                                </select>
                                <input x-model="attendanceForm.date" @change="loadAttendanceData()" type="date" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2.5">
                                <button @click="loadAttendanceData()" :disabled="attendanceLoading" class="w-full rounded-xl bg-emerald-600 text-white font-medium px-4 py-2.5 hover:bg-emerald-700 disabled:opacity-50" x-text="attendanceLoading ? 'Chargement...' : 'Actualiser'"></button>
                            </div>

                            <div class="mt-4 overflow-x-auto">
                                <table class="w-full min-w-[720px]">
                                    <thead>
                                        <tr class="text-left text-xs text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700">
                                            <th class="pb-2 font-medium">Élève</th>
                                            <th class="pb-2 font-medium">Statut</th>
                                            <th class="pb-2 font-medium">Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                                        <template x-for="record in attendanceForm.records" :key="`attendance-${record.student_id}`">
                                            <tr>
                                                <td class="py-3 text-sm text-gray-900 dark:text-white" x-text="record.student_name"></td>
                                                <td class="py-3">
                                                    <select x-model="record.status" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm">
                                                        <option value="present">Présent</option>
                                                        <option value="absent">Absent</option>
                                                        <option value="late">En retard</option>
                                                        <option value="excused">Excusé</option>
                                                    </select>
                                                </td>
                                                <td class="py-3">
                                                    <input x-model="record.notes" type="text" placeholder="Optionnel" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm">
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>

                            <p x-show="attendanceForm.records.length === 0" class="mt-3 text-sm text-gray-500">Sélectionnez une classe pour afficher les élèves.</p>

                            <div class="mt-4 flex justify-end">
                                <button @click="recordAttendance()" :disabled="savingAttendance" class="px-4 py-2 rounded-xl bg-emerald-600 text-white font-medium hover:bg-emerald-700 disabled:opacity-50" x-text="savingAttendance ? 'Enregistrement...' : 'Enregistrer les présences'"></button>
                            </div>
                        </div>
                    </div>

                    <div x-show="activeTab === 'grades'">
                        <div class="mb-5 rounded-2xl border border-violet-200 dark:border-violet-800 bg-violet-50/70 dark:bg-violet-900/20 p-4">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <select x-model="gradeFilter.class_id" @change="syncGradeClass(); loadGradesData()" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2.5">
                                    <option value="">Choisir une classe</option>
                                    <template x-for="item in classes" :key="`grade-class-${item.id}`">
                                        <option :value="item.id" x-text="item.name || item.label || `Classe #${item.id}`"></option>
                                    </template>
                                </select>
                                <input x-model="gradeFilter.term" @change="syncGradeTerm(); loadGradesData()" type="text" placeholder="Ex: 1er trimestre" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2.5">
                                <button @click="loadGradesData()" :disabled="loadingGrades" class="w-full rounded-xl bg-violet-600 text-white font-medium px-4 py-2.5 hover:bg-violet-700 disabled:opacity-50" x-text="loadingGrades ? 'Chargement...' : 'Actualiser'"></button>
                            </div>

                            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3">
                                <select x-model="gradeForm.student_id" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2.5">
                                    <option value="">Choisir un élève</option>
                                    <template x-for="student in getStudentsForClass(gradeForm.class_id)" :key="`grade-student-${student.id}`">
                                        <option :value="student.id" x-text="student.name || `${student.first_name || ''} ${student.last_name || ''}`.trim() || `Élève #${student.id}`"></option>
                                    </template>
                                </select>
                                <input x-model="gradeForm.subject" type="text" placeholder="Matière" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2.5">
                                <input x-model="gradeForm.academic_year" type="text" placeholder="Année académique" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2.5">
                                <input x-model="gradeForm.term" type="text" placeholder="Période / trimestre" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2.5">
                                <input x-model="gradeForm.score" type="number" step="0.01" min="0" placeholder="Note" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2.5">
                                <input x-model="gradeForm.max_score" type="number" step="0.01" min="1" placeholder="Score max" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2.5">
                                <select x-model="gradeForm.exam_type" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2.5">
                                    <option value="exam">Examen</option>
                                    <option value="quiz">Quiz</option>
                                    <option value="homework">Devoir</option>
                                    <option value="participation">Participation</option>
                                </select>
                                <textarea x-model="gradeForm.notes" rows="2" placeholder="Notes complémentaires" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2.5"></textarea>
                            </div>

                            <div class="mt-4 flex items-center justify-between gap-3">
                                <p class="text-xs text-gray-500" x-show="selectedGradeId">Mode édition activé.</p>
                                <div class="flex gap-2">
                                    <button x-show="selectedGradeId" @click="resetGradeForm()" type="button" class="px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 font-medium">Annuler</button>
                                    <button @click="saveGrade()" :disabled="savingGrade" class="px-4 py-2 rounded-xl bg-violet-600 text-white font-medium hover:bg-violet-700 disabled:opacity-50" x-text="savingGrade ? 'Enregistrement...' : (selectedGradeId ? 'Mettre à jour la note' : 'Enregistrer la note')"></button>
                                </div>
                            </div>

                            <div class="mt-5 overflow-x-auto">
                                <table class="w-full min-w-[720px]">
                                    <thead>
                                        <tr class="text-left text-xs text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700">
                                            <th class="pb-2 font-medium">Élève</th>
                                            <th class="pb-2 font-medium">Matière</th>
                                            <th class="pb-2 font-medium">Période</th>
                                            <th class="pb-2 font-medium">Note</th>
                                            <th class="pb-2 font-medium">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                                        <template x-for="grade in grades" :key="`grade-${grade.id}`">
                                            <tr>
                                                <td class="py-3 text-sm text-gray-900 dark:text-white" x-text="grade.student?.user?.name || grade.student?.name || `Élève #${grade.student_id}`"></td>
                                                <td class="py-3 text-sm text-gray-600 dark:text-gray-400" x-text="grade.subject"></td>
                                                <td class="py-3 text-sm text-gray-600 dark:text-gray-400" x-text="grade.term"></td>
                                                <td class="py-3 text-sm text-gray-600 dark:text-gray-400" x-text="`${grade.score}/${grade.max_score || 20}`"></td>
                                                <td class="py-3 text-sm">
                                                    <button x-show="can('grades.update')" @click="startEditGrade(grade)" class="px-3 py-1.5 rounded-lg border border-violet-200 text-violet-700 hover:bg-violet-50 text-xs">Modifier</button>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>

                            <p x-show="grades.length === 0" class="mt-3 text-sm text-gray-500">Aucune note enregistrée pour ce filtre.</p>
                        </div>
                    </div>

                    <div x-show="activeTab === 'schoolYears'">
                        <div class="rounded-2xl border border-indigo-200 dark:border-indigo-800 bg-indigo-50/60 dark:bg-indigo-900/20 p-4">
                            <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Pilotage de l'année scolaire</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <select x-model="selectedAcademicYear" @change="onAcademicYearChange()" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2.5">
                                    <template x-for="year in schoolYears" :key="`year-${year}`">
                                        <option :value="year" x-text="year"></option>
                                    </template>
                                </select>
                                <button @click="archiveAcademicYear()" :disabled="archivingYear || !selectedAcademicYear || !can('school-years.archive')" class="w-full rounded-xl bg-amber-600 text-white font-medium px-4 py-2.5 hover:bg-amber-700 disabled:opacity-50" x-text="archivingYear ? 'Archivage...' : 'Archiver l\'année'"></button>
                                <button @click="restoreAcademicYear()" :disabled="restoringYear || !selectedAcademicYear || !can('school-years.archive')" class="w-full rounded-xl bg-emerald-600 text-white font-medium px-4 py-2.5 hover:bg-emerald-700 disabled:opacity-50" x-text="restoringYear ? 'Restauration...' : 'Restaurer l\'année'"></button>
                            </div>

                            <div class="mt-4 grid grid-cols-2 lg:grid-cols-4 gap-3" x-show="schoolYearSummary">
                                <div class="rounded-xl bg-white dark:bg-gray-800 p-3 border border-gray-100 dark:border-gray-700">
                                    <p class="text-xs text-gray-500">Classes</p>
                                    <p class="text-xl font-semibold text-gray-900 dark:text-white" x-text="schoolYearSummary?.classes_total ?? 0"></p>
                                </div>
                                <div class="rounded-xl bg-white dark:bg-gray-800 p-3 border border-gray-100 dark:border-gray-700">
                                    <p class="text-xs text-gray-500">Élèves</p>
                                    <p class="text-xl font-semibold text-gray-900 dark:text-white" x-text="schoolYearSummary?.students_total ?? 0"></p>
                                </div>
                                <div class="rounded-xl bg-white dark:bg-gray-800 p-3 border border-gray-100 dark:border-gray-700">
                                    <p class="text-xs text-gray-500">Enseignants</p>
                                    <p class="text-xl font-semibold text-gray-900 dark:text-white" x-text="schoolYearSummary?.teachers_total ?? 0"></p>
                                </div>
                                <div class="rounded-xl bg-white dark:bg-gray-800 p-3 border border-gray-100 dark:border-gray-700">
                                    <p class="text-xs text-gray-500">Classes archivées</p>
                                    <p class="text-xl font-semibold text-gray-900 dark:text-white" x-text="schoolYearSummary?.classes_archived ?? 0"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div x-show="activeTab === 'courses'">
                        <div class="rounded-2xl border border-cyan-200 dark:border-cyan-800 bg-cyan-50/70 dark:bg-cyan-900/20 p-4">
                            <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Gestion des cours et emplois du temps</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <select x-model="scheduleForm.class_id" @change="loadClassSchedule()" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2.5">
                                    <option value="">Choisir une classe</option>
                                    <template x-for="item in classes" :key="`schedule-class-${item.id}`">
                                        <option :value="item.id" x-text="(item.name || item.label || ('Classe #' + item.id)) + ' (' + (item.academic_year || '-') + ')' "></option>
                                    </template>
                                </select>
                                <button @click="addScheduleRow()" :disabled="!scheduleForm.class_id" class="w-full rounded-xl border border-cyan-300 text-cyan-700 font-medium px-4 py-2.5 hover:bg-cyan-100 disabled:opacity-50">Ajouter un créneau</button>
                                <button @click="saveClassSchedule()" :disabled="savingSchedule || !scheduleForm.class_id || !can('school-classes.update')" class="w-full rounded-xl bg-cyan-600 text-white font-medium px-4 py-2.5 hover:bg-cyan-700 disabled:opacity-50" x-text="savingSchedule ? 'Enregistrement...' : 'Enregistrer l\'emploi du temps'"></button>
                            </div>

                            <div class="mt-4 overflow-x-auto" x-show="scheduleForm.class_id">
                                <table class="w-full min-w-[980px]">
                                    <thead>
                                        <tr class="text-left text-xs text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700">
                                            <th class="pb-2 font-medium">Jour</th>
                                            <th class="pb-2 font-medium">Début</th>
                                            <th class="pb-2 font-medium">Fin</th>
                                            <th class="pb-2 font-medium">Matière</th>
                                            <th class="pb-2 font-medium">Enseignant</th>
                                            <th class="pb-2 font-medium">Salle</th>
                                            <th class="pb-2 font-medium">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                                        <template x-for="(entry, idx) in scheduleForm.schedules" :key="`sched-${idx}`">
                                            <tr>
                                                <td class="py-2">
                                                    <select x-model="entry.day" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-2 py-1.5 text-sm">
                                                        <option value="monday">Lundi</option>
                                                        <option value="tuesday">Mardi</option>
                                                        <option value="wednesday">Mercredi</option>
                                                        <option value="thursday">Jeudi</option>
                                                        <option value="friday">Vendredi</option>
                                                        <option value="saturday">Samedi</option>
                                                    </select>
                                                </td>
                                                <td class="py-2"><input x-model="entry.start" type="time" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-2 py-1.5 text-sm"></td>
                                                <td class="py-2"><input x-model="entry.end" type="time" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-2 py-1.5 text-sm"></td>
                                                <td class="py-2"><input x-model="entry.subject" type="text" placeholder="Matière" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-2 py-1.5 text-sm"></td>
                                                <td class="py-2">
                                                    <select x-model="entry.teacher_id" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-2 py-1.5 text-sm">
                                                        <option value="">Non assigné</option>
                                                        <template x-for="teacher in teachers" :key="`sched-teacher-${teacher.id}`">
                                                            <option :value="teacher.user_id || ''" x-text="teacher.name || `${teacher.first_name || ''} ${teacher.last_name || ''}`.trim() || `Enseignant #${teacher.id}`"></option>
                                                        </template>
                                                    </select>
                                                </td>
                                                <td class="py-2"><input x-model="entry.room" type="text" placeholder="Salle" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-2 py-1.5 text-sm"></td>
                                                <td class="py-2"><button @click="removeScheduleRow(idx)" class="px-3 py-1.5 rounded-lg bg-red-600 text-white text-xs hover:bg-red-700">Supprimer</button></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>

                            <p x-show="scheduleForm.class_id && scheduleForm.schedules.length === 0" class="mt-3 text-sm text-gray-500">Aucun créneau pour cette classe.</p>
                        </div>
                    </div>

                    <div x-show="activeTab === 'tasks'">
                        <div x-show="can('tasks.create')" class="mb-5 rounded-2xl border border-blue-200 dark:border-blue-800 bg-blue-50/70 dark:bg-blue-900/20 p-4">
                            <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Nouvelle tâche école</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <input x-model="taskForm.title" type="text" placeholder="Titre de la tâche" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2.5">
                                <select x-model="taskForm.assigned_to" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2.5">
                                    <option value="">Assigner plus tard</option>
                                    <template x-for="member in members.filter(m => m.member_type !== 'director')" :key="`task-member-${member.id}`">
                                        <option :value="member.id" x-text="`${member.name} (${member.member_type})`"></option>
                                    </template>
                                </select>
                                <select x-model="taskForm.priority" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2.5">
                                    <option value="low">Priorité basse</option>
                                    <option value="medium">Priorité moyenne</option>
                                    <option value="high">Priorité haute</option>
                                    <option value="urgent">Urgente</option>
                                </select>
                                <input x-model="taskForm.due_date" type="date" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2.5">
                            </div>
                            <textarea x-model="taskForm.description" rows="3" placeholder="Description de la tâche..." class="mt-3 w-full rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2.5"></textarea>
                            <div class="mt-3 flex justify-end">
                                <button @click="createTask()" :disabled="savingTask" class="px-4 py-2 rounded-xl bg-blue-600 text-white font-medium hover:bg-blue-700 disabled:opacity-50" x-text="savingTask ? 'Création...' : 'Créer la tâche'"></button>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <template x-for="task in tasks" :key="`task-${task.id}`">
                                <div class="rounded-2xl border border-gray-200 dark:border-gray-700 p-4">
                                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-3">
                                        <div>
                                            <p class="font-semibold text-gray-900 dark:text-white" x-text="task.title"></p>
                                            <p class="text-xs text-gray-500 mt-1" x-text="task.description || 'Sans description' "></p>
                                            <div class="mt-2 flex flex-wrap gap-2 text-xs">
                                                <span class="px-2 py-1 rounded-full bg-indigo-100 text-indigo-700" x-text="`Statut: ${task.status}`"></span>
                                                <span class="px-2 py-1 rounded-full bg-amber-100 text-amber-700" x-text="`Priorité: ${task.priority}`"></span>
                                                <span class="px-2 py-1 rounded-full bg-gray-100 text-gray-700" x-text="`Assignée à: ${task.assignee?.name || 'non assignée'}`"></span>
                                                <span x-show="task.due_date" class="px-2 py-1 rounded-full bg-rose-100 text-rose-700" x-text="`Échéance: ${task.due_date}`"></span>
                                            </div>
                                        </div>

                                        <div class="flex flex-wrap gap-2 md:justify-end">
                                            <select x-model="task.status" @change="updateTaskStatus(task)" class="rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-1.5 text-xs">
                                                <option value="todo">À faire</option>
                                                <option value="in_progress">En cours</option>
                                                <option value="done">Terminée</option>
                                                <option value="blocked">Bloquée</option>
                                                <option value="cancelled">Annulée</option>
                                            </select>
                                            <button x-show="can('tasks.delete')" @click="deleteTask(task)" class="px-3 py-1.5 rounded-lg bg-red-600 text-white text-xs hover:bg-red-700">Supprimer</button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <p x-show="tasks.length === 0" class="text-sm text-gray-500">Aucune tâche enregistrée.</p>
                    </div>

                    <div x-show="activeTab === 'members'" id="members">
                        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="rounded-2xl border border-gray-200 dark:border-gray-700 p-4 bg-gray-50/60 dark:bg-gray-800/40">
                                    <h3 class="font-semibold text-gray-900 dark:text-white mb-1">Nouvelle délégation</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-300">Attribuez un rôle temporaire à un membre de l'école.</p>

                                    <div class="mt-4 space-y-3">
                                        <select x-model="delegationForm.user_id" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2.5">
                                            <option value="">Choisir un membre</option>
                                            <template x-for="member in members.filter(m => m.member_type !== 'director')" :key="`option-${member.id}`">
                                                <option :value="member.id" x-text="`${member.name} (${member.member_type})`"></option>
                                            </template>
                                        </select>

                                        <input x-model="delegationForm.role_name" type="text" placeholder="Nom du rôle temporaire (ex: Responsable notes)" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2.5">

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <input x-model="delegationForm.starts_at" type="datetime-local" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2.5">
                                            <input x-model="delegationForm.ends_at" type="datetime-local" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2.5">
                                        </div>

                                        <textarea x-model="delegationForm.notes" rows="3" placeholder="Motif ou consignes..." class="w-full rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2.5"></textarea>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 max-h-56 overflow-y-auto pr-1">
                                            <template x-for="permission in delegablePermissions" :key="permission">
                                                <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 rounded-lg border border-gray-200 dark:border-gray-700 px-3 py-2">
                                                    <input type="checkbox" :value="permission" x-model="delegationForm.permissions" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                    <span x-text="permission"></span>
                                                </label>
                                            </template>
                                        </div>

                                        <button @click="createDelegation()" class="w-full rounded-xl bg-indigo-600 text-white font-medium px-4 py-2.5 hover:bg-indigo-700 transition" :disabled="savingDelegation">
                                            <span x-text="savingDelegation ? 'Enregistrement...' : 'Créer la délégation'"></span>
                                        </button>
                                    </div>
                                </div>

                                <p x-show="members.length === 0" class="text-sm text-gray-500">Aucun membre trouvé pour la délégation.</p>
                            </div>

                            <div class="space-y-4">
                                <div class="rounded-2xl border border-gray-200 dark:border-gray-700 p-4">
                                    <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Membres & droits actifs</h3>
                                    <div class="space-y-3">
                                        <template x-for="member in members" :key="`member-${member.id}`">
                                            <div class="rounded-xl border border-gray-200 dark:border-gray-700 p-3">
                                                <div class="flex items-start justify-between gap-3 mb-2">
                                                    <div>
                                                        <p class="font-semibold text-gray-900 dark:text-white" x-text="member.name"></p>
                                                        <p class="text-xs text-gray-500" x-text="`${member.email} · ${member.member_type === 'director' ? 'Gestionnaire' : 'Membre'}`"></p>
                                                    </div>
                                                    <button @click="prefillDelegation(member)" class="text-xs px-3 py-1.5 rounded-lg border border-indigo-200 text-indigo-700 hover:bg-indigo-50" x-show="member.member_type !== 'director'">Déléguer</button>
                                                </div>
                                                <div class="flex flex-wrap gap-2">
                                                    <template x-for="permission in (member.delegated_permissions || [])" :key="`p-${member.id}-${permission}`">
                                                        <span class="text-[11px] px-2 py-1 rounded-full bg-indigo-100 text-indigo-700" x-text="permission"></span>
                                                    </template>
                                                    <span x-show="!member.delegated_permissions || member.delegated_permissions.length === 0" class="text-xs text-gray-400">Aucun droit délégué actif</span>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <div class="rounded-2xl border border-gray-200 dark:border-gray-700 p-4">
                                    <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Délégations temporaires</h3>
                                    <div class="space-y-3">
                                        <template x-for="delegation in delegations" :key="delegation.id">
                                            <div class="rounded-xl border border-gray-200 dark:border-gray-700 p-3">
                                                <div class="flex items-start justify-between gap-3">
                                                    <div>
                                                        <p class="font-medium text-gray-900 dark:text-white" x-text="delegation.role_name"></p>
                                                        <p class="text-xs text-gray-500" x-text="`${delegation.user?.name || 'N/A'} · ${delegation.is_active ? 'Active' : 'Inactive'}`"></p>
                                                        <p class="text-xs text-gray-500 mt-1" x-text="`${delegation.starts_at || '-'} → ${delegation.ends_at || '-'}`"></p>
                                                    </div>
                                                    <button x-show="delegation.is_active" @click="revokeDelegation(delegation)" class="text-xs px-3 py-1.5 rounded-lg bg-red-600 text-white hover:bg-red-700">Révoquer</button>
                                                </div>
                                                <div class="mt-2 flex flex-wrap gap-2">
                                                    <template x-for="permission in (delegation.permissions || [])" :key="`d-${delegation.id}-${permission}`">
                                                        <span class="text-[11px] px-2 py-1 rounded-full bg-emerald-100 text-emerald-700" x-text="permission"></span>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>
                                        <p x-show="delegations.length === 0" class="text-sm text-gray-500">Aucune délégation enregistrée.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>

<script>
function schoolShowPage(schoolId, initialSchool = null) {
    return {
        schoolId,
        activeTab: 'overview',
        loading: false,
        school: initialSchool,
        classes: [],
        students: [],
        teachers: [],
        tasks: [],
        attendanceRecords: [],
        grades: [],
        schoolYears: [],
        schoolYearSummary: null,
        selectedAcademicYear: '',
        members: [],
        delegablePermissions: [],
        delegations: [],
        savingMemberId: null,
        savingDelegation: false,
        savingTask: false,
        savingAttendance: false,
        savingGrade: false,
        attendanceLoading: false,
        loadingGrades: false,
        importingStudents: false,
        archivingYear: false,
        restoringYear: false,
        savingSchedule: false,
        flash: { message: '', type: 'success' },
        token: localStorage.getItem('token') || '',
        permissions: [],
        isSuperAdmin: false,
        currentRole: '',
        currentUserId: null,
        isDirector: false,
        activeLevelTags: [],
        moduleCards: [],
        schoolModules: @json(config('school_modules.submodules')),
        roleCards: [
            {
                key: 'director',
                title: 'Directeur',
                subtitle: 'Pilotage complet de l’établissement',
                features: [
                    'Vue globale de l’école et des indicateurs',
                    'Gestion des classes, élèves, enseignants et staff',
                    'Présences, notes, tâches, délégations et année scolaire',
                ],
            },
            {
                key: 'school_staff',
                title: 'Staff administratif',
                subtitle: 'Secrétariat et support opérationnel',
                features: [
                    'Gestion des dossiers et des inscriptions',
                    'Suivi des élèves et coordination interne',
                    'Préparation des informations pour la direction',
                ],
            },
            {
                key: 'teacher',
                title: 'Enseignant',
                subtitle: 'Suivi pédagogique des classes',
                features: [
                    'Mes classes et mon emploi du temps',
                    'Saisie des présences et des notes',
                    'Suivi des évaluations et des bulletins',
                ],
            },
            {
                key: 'student',
                title: 'Élève',
                subtitle: 'Vie scolaire individuelle',
                features: [
                    'Consulter les bulletins et les résultats',
                    'Suivre l’assiduité et les absences',
                    'Voir les cours et le calendrier scolaire',
                ],
            },
            {
                key: 'parent',
                title: 'Parent',
                subtitle: 'Suivi du parcours de l’enfant',
                features: [
                    'Lire les notes et les bulletins',
                    'Suivre les présences et les absences',
                    'Recevoir les informations importantes de l’école',
                ],
            },
        ],
        delegationForm: {
            user_id: '',
            role_name: '',
            starts_at: '',
            ends_at: '',
            notes: '',
            permissions: [],
        },
        taskForm: {
            title: '',
            description: '',
            priority: 'medium',
            status: 'todo',
            due_date: '',
            assigned_to: '',
        },
        selectedGradeId: null,
        attendanceForm: {
            class_id: '',
            date: new Date().toISOString().slice(0, 10),
            records: [],
        },
        gradeFilter: {
            class_id: '',
            term: '1er trimestre',
        },
        gradeForm: {
            class_id: '',
            student_id: '',
            subject: '',
            academic_year: `${new Date().getFullYear()}-${new Date().getFullYear() + 1}`,
            term: '1er trimestre',
            score: '',
            max_score: 20,
            exam_type: 'exam',
            notes: '',
        },
        scheduleForm: {
            class_id: '',
            schedules: [],
        },
        async init() {
            await this.loadUserContext();
            await this.loadSchool();
        },
        async loadUserContext() {
            const cachedUser = localStorage.getItem('user');
            if (cachedUser) {
                try {
                    const user = JSON.parse(cachedUser);
                    const rolePermissions = (user.roles ?? []).flatMap((role) => (role.permissions ?? []).map((permission) => permission.name));
                    const directPermissions = (user.permissions ?? []).map((permission) => permission.name);
                    this.permissions = [...new Set([...rolePermissions, ...directPermissions])];
                    this.isSuperAdmin = user?.role === 'super_admin';
                    this.currentRole = user?.role || (user?.roles ?? [])[0]?.name || '';
                    this.currentUserId = user?.id ?? null;
                } catch (_) {
                    this.permissions = [];
                    this.isSuperAdmin = false;
                }
            }

            if (!this.token) {
                return;
            }

            try {
                const mePayload = await this.fetchJson('/api/auth/me');
                const user = mePayload.data || {};
                localStorage.setItem('user', JSON.stringify(user));
                const rolePermissions = (user.roles ?? []).flatMap((role) => (role.permissions ?? []).map((permission) => permission.name));
                const directPermissions = (user.permissions ?? []).map((permission) => permission.name);
                this.permissions = [...new Set([...rolePermissions, ...directPermissions])];
                this.isSuperAdmin = user?.role === 'super_admin';
                this.currentRole = user?.role || (user?.roles ?? [])[0]?.name || '';
                this.currentUserId = user?.id ?? null;
            } catch (_) {
                // Keep cached permissions when /me is unavailable.
            }
        },
        can(permission) {
            return this.isSuperAdmin || this.permissions.includes(permission) || this.permissions.includes('*');
        },
        showFlash(message, type = 'error') {
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
                primaire: 'Primaire',
                secondaire: 'Secondaire',
                humanites: 'Humanités',
            };

            if (!Array.isArray(levelTypes) || levelTypes.length === 0) {
                return 'Aucun niveau';
            }

            return levelTypes.map(level => labels[level] || level).join(', ');
        },
        async fetchJson(url, options = {}) {
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 10000);
            let response;

            try {
                response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${this.token}`,
                        ...(options.headers || {}),
                    },
                    credentials: 'same-origin',
                    signal: controller.signal,
                    ...options,
                });
            } finally {
                clearTimeout(timeoutId);
            }

            const payload = await response.json();
            if (!response.ok || !payload.success) {
                throw new Error(payload.message || 'Erreur API');
            }

            return payload;
        },
        async loadSchool() {
            this.loading = true;
            try {
                const schoolPayload = await this.fetchJson(`/api/schools/${this.schoolId}`);
                this.school = schoolPayload.data;
                this.isDirector = Number(this.school?.director_id || 0) === Number(this.currentUserId || 0);
                this.computeModuleCards();

                if (this.can('schools.view')) {
                    await this.loadSchoolYears(false);
                }

                const loaders = [];
                const yearQuery = this.selectedAcademicYear ? `academic_year=${encodeURIComponent(this.selectedAcademicYear)}` : '';

                if (this.can('school-classes.view')) {
                    loaders.push(this.fetchJson(`/api/schools/${this.schoolId}/classes${yearQuery ? `?${yearQuery}` : ''}`).then((payload) => {
                        this.classes = payload.data || [];
                        if (!this.attendanceForm.class_id && this.classes.length > 0) {
                            this.attendanceForm.class_id = String(this.classes[0].id);
                        }
                        if (!this.gradeFilter.class_id && this.classes.length > 0) {
                            this.gradeFilter.class_id = String(this.classes[0].id);
                            this.gradeForm.class_id = String(this.classes[0].id);
                        }
                        if (!this.scheduleForm.class_id && this.classes.length > 0) {
                            this.scheduleForm.class_id = String(this.classes[0].id);
                        }
                    }));
                }

                if (this.can('students.view')) {
                    loaders.push(this.fetchJson(`/api/schools/${this.schoolId}/students${yearQuery ? `?${yearQuery}` : ''}`).then((payload) => {
                        this.students = payload.data || [];
                    }));
                }

                if (this.can('teachers.view')) {
                    loaders.push(this.fetchJson(`/api/schools/${this.schoolId}/teachers${yearQuery ? `?${yearQuery}` : ''}`).then((payload) => {
                        this.teachers = payload.data || [];
                    }));
                }

                if (this.can('schools.delegate')) {
                    loaders.push(this.fetchJson(`/api/schools/${this.schoolId}/members`).then((payload) => {
                        this.members = payload.data?.members || [];
                        this.delegablePermissions = payload.data?.delegable_permissions || [];
                        this.delegations = payload.data?.delegations || [];
                    }));
                }

                if (this.can('tasks.view')) {
                    loaders.push(this.fetchJson(`/api/schools/${this.schoolId}/tasks?per_page=100`).then((payload) => {
                        this.tasks = payload.data || [];
                    }));
                }

                await Promise.allSettled(loaders);

                this.computeModuleCards();

                if (this.can('attendance.view') && this.attendanceForm.class_id) {
                    await this.loadAttendanceData();
                }

                if (this.can('grades.view') && this.gradeFilter.class_id) {
                    await this.loadGradesData();
                }

                if (this.can('school-classes.view') && this.scheduleForm.class_id) {
                    await this.loadClassSchedule();
                }
            } catch (error) {
                this.showFlash(error.message || 'Impossible de charger le détail école');
            } finally {
                this.loading = false;
            }
        },
        computeModuleCards() {
            const labels = {
                maternelle: 'Maternelle',
                primaire: 'Primaire',
                secondaire: 'Secondaire',
                humanites: 'Humanités',
            };

            const levelTypes = Array.isArray(this.school?.level_types) ? this.school.level_types : [];
            const normalizedLevels = [...new Set(levelTypes)].sort().join('|');
            this.activeLevelTags = levelTypes.map((level) => labels[level] || level);

            this.moduleCards = Object.values(this.schoolModules || {}).map((module) => {
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
        async loadSchoolYears(showError = true) {
            if (!this.can('schools.view')) {
                return;
            }

            try {
                const query = this.selectedAcademicYear ? `?academic_year=${encodeURIComponent(this.selectedAcademicYear)}` : '';
                const payload = await this.fetchJson(`/api/schools/${this.schoolId}/school-years${query}`);
                this.schoolYears = payload.data?.available_years || [];
                this.selectedAcademicYear = payload.data?.selected_academic_year || this.selectedAcademicYear || this.schoolYears[0] || '';
                this.schoolYearSummary = payload.data?.summary || null;

                if (this.selectedAcademicYear) {
                    this.gradeForm.academic_year = this.selectedAcademicYear;
                }
            } catch (error) {
                if (showError) {
                    this.showFlash(error.message || 'Impossible de charger les années scolaires');
                }
            }
        },
        async onAcademicYearChange() {
            this.gradeForm.academic_year = this.selectedAcademicYear || this.gradeForm.academic_year;
            await this.loadSchoolYears(false);
            await this.loadSchool();
        },
        async archiveAcademicYear() {
            if (!this.selectedAcademicYear) {
                this.showFlash('Choisis une année scolaire.', 'error');
                return;
            }

            if (!confirm(`Archiver l'année ${this.selectedAcademicYear} ?`)) {
                return;
            }

            this.archivingYear = true;
            try {
                await this.fetchJson(`/api/schools/${this.schoolId}/school-years/archive`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    },
                    body: JSON.stringify({ academic_year: this.selectedAcademicYear }),
                });

                this.showFlash('Année scolaire archivée.', 'success');
                await this.loadSchoolYears(false);
                await this.loadSchool();
            } catch (error) {
                this.showFlash(error.message || 'Archivage impossible', 'error');
            } finally {
                this.archivingYear = false;
            }
        },
        async restoreAcademicYear() {
            if (!this.selectedAcademicYear) {
                this.showFlash('Choisis une année scolaire.', 'error');
                return;
            }

            if (!confirm(`Restaurer l'année ${this.selectedAcademicYear} ?`)) {
                return;
            }

            this.restoringYear = true;
            try {
                await this.fetchJson(`/api/schools/${this.schoolId}/school-years/restore`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    },
                    body: JSON.stringify({ academic_year: this.selectedAcademicYear }),
                });

                this.showFlash('Année scolaire restaurée.', 'success');
                await this.loadSchoolYears(false);
                await this.loadSchool();
            } catch (error) {
                this.showFlash(error.message || 'Restauration impossible', 'error');
            } finally {
                this.restoringYear = false;
            }
        },
        addScheduleRow() {
            this.scheduleForm.schedules.push({
                day: 'monday',
                start: '08:00',
                end: '09:00',
                subject: '',
                teacher_id: '',
                room: '',
            });
        },
        removeScheduleRow(index) {
            this.scheduleForm.schedules.splice(index, 1);
        },
        dayNumberToKey(dayNumber) {
            const map = {
                1: 'monday',
                2: 'tuesday',
                3: 'wednesday',
                4: 'thursday',
                5: 'friday',
                6: 'saturday',
                7: 'sunday',
            };

            return map[Number(dayNumber)] || 'monday';
        },
        async loadClassSchedule() {
            if (!this.scheduleForm.class_id) {
                this.scheduleForm.schedules = [];
                return;
            }

            try {
                const payload = await this.fetchJson(`/api/schools/${this.schoolId}/classes/${this.scheduleForm.class_id}/schedule`);
                this.scheduleForm.schedules = (payload.data || []).map((item) => ({
                    day: this.dayNumberToKey(item.day_of_week),
                    start: String(item.start_time || '').slice(0, 5),
                    end: String(item.end_time || '').slice(0, 5),
                    subject: item.subject || '',
                    teacher_id: item.teacher_id || '',
                    room: item.room || '',
                }));
            } catch (error) {
                this.scheduleForm.schedules = [];
                this.showFlash(error.message || 'Impossible de charger l\'emploi du temps', 'error');
            }
        },
        async saveClassSchedule() {
            if (!this.scheduleForm.class_id) {
                this.showFlash('Choisis une classe.', 'error');
                return;
            }

            this.savingSchedule = true;
            try {
                await this.fetchJson(`/api/schools/${this.schoolId}/classes/${this.scheduleForm.class_id}/schedule`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    },
                    body: JSON.stringify({
                        schedules: (this.scheduleForm.schedules || []).map((item) => ({
                            day: item.day,
                            start: item.start,
                            end: item.end,
                            subject: item.subject,
                            teacher_id: item.teacher_id || null,
                            room: item.room || null,
                        })),
                    }),
                });

                this.showFlash('Emploi du temps enregistré.', 'success');
                await this.loadClassSchedule();
            } catch (error) {
                this.showFlash(error.message || 'Erreur lors de l\'enregistrement de l\'emploi du temps', 'error');
            } finally {
                this.savingSchedule = false;
            }
        },
        getStudentsForClass(classId) {
            if (!classId) {
                return [];
            }

            return this.students.filter((student) => String(student.class_id || student.class_?.id || student.class?.id || '') === String(classId));
        },
        syncAttendanceClass() {
            this.attendanceForm.class_id = String(this.attendanceForm.class_id || this.gradeFilter.class_id || '');
        },
        syncGradeClass() {
            this.gradeForm.class_id = String(this.gradeFilter.class_id || this.gradeForm.class_id || '');
            if (!this.gradeForm.student_id) {
                return;
            }

            const studentExists = this.getStudentsForClass(this.gradeForm.class_id).some((student) => String(student.id) === String(this.gradeForm.student_id));
            if (!studentExists) {
                this.gradeForm.student_id = '';
            }
        },
        syncGradeTerm() {
            this.gradeForm.term = this.gradeFilter.term;
        },
        normalizeAttendanceRecords(records) {
            const recordMap = new Map((records || []).map((record) => [String(record.student_id), record]));
            const classStudents = this.getStudentsForClass(this.attendanceForm.class_id);

            this.attendanceForm.records = classStudents.map((student) => {
                const existing = recordMap.get(String(student.id));
                return {
                    student_id: student.id,
                    class_id: this.attendanceForm.class_id,
                    date: this.attendanceForm.date,
                    student_name: student.name || `${student.first_name || ''} ${student.last_name || ''}`.trim() || `Élève #${student.id}`,
                    status: existing?.status || 'present',
                    notes: existing?.notes || '',
                };
            });
        },
        async loadAttendanceData() {
            if (!this.can('attendance.view') || !this.attendanceForm.class_id) {
                this.attendanceForm.records = [];
                return;
            }

            this.attendanceLoading = true;
            try {
                const payload = await this.fetchJson(`/api/schools/${this.schoolId}/attendance?class_id=${encodeURIComponent(this.attendanceForm.class_id)}&date=${encodeURIComponent(this.attendanceForm.date)}`);
                this.attendanceRecords = payload.data || [];
                this.normalizeAttendanceRecords(this.attendanceRecords);
            } catch (error) {
                this.showFlash(error.message || 'Impossible de charger les présences');
            } finally {
                this.attendanceLoading = false;
            }
        },
        async recordAttendance() {
            if (!this.attendanceForm.class_id) {
                this.showFlash('Choisis une classe.', 'error');
                return;
            }

            if (!this.attendanceForm.date) {
                this.showFlash('Choisis une date.', 'error');
                return;
            }

            if ((this.attendanceForm.records || []).length === 0) {
                this.showFlash('Aucun élève à enregistrer pour cette classe.', 'error');
                return;
            }

            this.savingAttendance = true;
            try {
                await this.fetchJson(`/api/schools/${this.schoolId}/attendance`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    },
                    body: JSON.stringify({
                        records: this.attendanceForm.records.map((record) => ({
                            student_id: record.student_id,
                            class_id: record.class_id,
                            date: this.attendanceForm.date,
                            status: record.status,
                            notes: record.notes,
                        })),
                    }),
                });

                this.showFlash('Présences enregistrées.', 'success');
                await this.loadAttendanceData();
            } catch (error) {
                this.showFlash(error.message || 'Erreur lors de l\'enregistrement des présences', 'error');
            } finally {
                this.savingAttendance = false;
            }
        },
        async loadGradesData() {
            if (!this.can('grades.view') || !this.gradeFilter.class_id) {
                this.grades = [];
                return;
            }

            this.loadingGrades = true;
            try {
                const query = new URLSearchParams({ class_id: this.gradeFilter.class_id });
                if (this.gradeFilter.term) {
                    query.set('term', this.gradeFilter.term);
                    this.gradeForm.term = this.gradeFilter.term;
                }

                const payload = await this.fetchJson(`/api/schools/${this.schoolId}/grades?${query.toString()}`);
                this.grades = payload.data || [];
            } catch (error) {
                this.showFlash(error.message || 'Impossible de charger les notes');
            } finally {
                this.loadingGrades = false;
            }
        },
        resetGradeForm() {
            this.selectedGradeId = null;
            this.gradeForm = {
                class_id: this.gradeFilter.class_id || this.gradeForm.class_id || '',
                student_id: '',
                subject: '',
                academic_year: `${new Date().getFullYear()}-${new Date().getFullYear() + 1}`,
                term: this.gradeFilter.term || '1er trimestre',
                score: '',
                max_score: 20,
                exam_type: 'exam',
                notes: '',
            };
        },
        startEditGrade(grade) {
            this.selectedGradeId = grade.id;
            this.gradeFilter.class_id = String(grade.class_id);
            this.gradeForm = {
                class_id: String(grade.class_id),
                student_id: String(grade.student_id),
                subject: grade.subject || '',
                academic_year: grade.academic_year || `${new Date().getFullYear()}-${new Date().getFullYear() + 1}`,
                term: grade.term || this.gradeFilter.term || '1er trimestre',
                score: grade.score ?? '',
                max_score: grade.max_score ?? 20,
                exam_type: grade.exam_type || 'exam',
                notes: grade.notes || '',
            };
            this.activeTab = 'grades';
        },
        async saveGrade() {
            if (!this.gradeForm.class_id) {
                this.showFlash('Choisis une classe.', 'error');
                return;
            }

            if (!this.gradeForm.student_id) {
                this.showFlash('Choisis un élève.', 'error');
                return;
            }

            if (!this.gradeForm.subject.trim()) {
                this.showFlash('La matière est requise.', 'error');
                return;
            }

            if (!this.gradeForm.term.trim()) {
                this.showFlash('La période est requise.', 'error');
                return;
            }

            this.savingGrade = true;
            try {
                const method = this.selectedGradeId ? 'PUT' : 'POST';
                const url = this.selectedGradeId ? `/api/schools/${this.schoolId}/grades/${this.selectedGradeId}` : `/api/schools/${this.schoolId}/grades`;

                await this.fetchJson(url, {
                    method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    },
                    body: JSON.stringify({
                        student_id: this.gradeForm.student_id,
                        class_id: this.gradeForm.class_id,
                        subject: this.gradeForm.subject,
                        academic_year: this.gradeForm.academic_year,
                        term: this.gradeForm.term,
                        score: this.gradeForm.score,
                        max_score: this.gradeForm.max_score,
                        exam_type: this.gradeForm.exam_type,
                        notes: this.gradeForm.notes,
                    }),
                });

                this.showFlash(this.selectedGradeId ? 'Note mise à jour.' : 'Note enregistrée.', 'success');
                this.resetGradeForm();
                await this.loadGradesData();
            } catch (error) {
                this.showFlash(error.message || 'Erreur lors de l\'enregistrement de la note', 'error');
            } finally {
                this.savingGrade = false;
            }
        },
        prefillDelegation(member) {
            this.delegationForm.user_id = member.id;
            this.delegationForm.role_name = member.member_type === 'teacher' ? 'Responsable opérationnel' : 'Délégué temporaire';
        },
        async createDelegation() {
            if (!this.delegationForm.user_id) {
                this.showFlash('Sélectionne un membre.', 'error');
                return;
            }

            if (!this.delegationForm.role_name.trim()) {
                this.showFlash('Le nom du rôle est requis.', 'error');
                return;
            }

            if (!this.delegationForm.starts_at || !this.delegationForm.ends_at) {
                this.showFlash('La période de délégation est requise.', 'error');
                return;
            }

            if ((this.delegationForm.permissions || []).length === 0) {
                this.showFlash('Sélectionne au moins une permission.', 'error');
                return;
            }

            this.savingDelegation = true;
            try {
                await this.fetchJson(`/api/schools/${this.schoolId}/members/${this.delegationForm.user_id}/delegations`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    },
                    body: JSON.stringify({
                        role_name: this.delegationForm.role_name,
                        permissions: [...new Set(this.delegationForm.permissions || [])],
                        starts_at: this.delegationForm.starts_at,
                        ends_at: this.delegationForm.ends_at,
                        notes: this.delegationForm.notes,
                    }),
                });

                this.showFlash('Délégation créée.', 'success');
                this.delegationForm.permissions = [];
                this.delegationForm.notes = '';
                await this.loadSchool();
            } catch (error) {
                this.showFlash(error.message || 'Erreur lors de la délégation');
            } finally {
                this.savingDelegation = false;
            }
        },
        async revokeDelegation(delegation) {
            if (!confirm('Révoquer cette délégation ?')) {
                return;
            }

            try {
                await this.fetchJson(`/api/schools/${this.schoolId}/members/${delegation.user?.id}/delegations/${delegation.id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    },
                });

                this.showFlash('Délégation révoquée.', 'success');
                await this.loadSchool();
            } catch (error) {
                this.showFlash(error.message || 'Erreur lors de la révocation');
            }
        },
        async createTask() {
            if (!this.taskForm.title.trim()) {
                this.showFlash('Le titre de la tâche est requis.', 'error');
                return;
            }

            this.savingTask = true;
            try {
                await this.fetchJson(`/api/schools/${this.schoolId}/tasks`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    },
                    body: JSON.stringify({
                        title: this.taskForm.title,
                        description: this.taskForm.description,
                        priority: this.taskForm.priority,
                        status: this.taskForm.status,
                        due_date: this.taskForm.due_date || null,
                        assigned_to: this.taskForm.assigned_to || null,
                    }),
                });

                this.taskForm = {
                    title: '',
                    description: '',
                    priority: 'medium',
                    status: 'todo',
                    due_date: '',
                    assigned_to: '',
                };
                this.showFlash('Tâche créée.', 'success');
                await this.loadSchool();
            } catch (error) {
                this.showFlash(error.message || 'Erreur lors de la création de la tâche', 'error');
            } finally {
                this.savingTask = false;
            }
        },
        async updateTaskStatus(task) {
            try {
                await this.fetchJson(`/api/schools/${this.schoolId}/tasks/${task.id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    },
                    body: JSON.stringify({ status: task.status }),
                });

                this.showFlash('Statut de tâche mis à jour.', 'success');
                await this.loadSchool();
            } catch (error) {
                this.showFlash(error.message || 'Erreur lors de la mise à jour de la tâche', 'error');
            }
        },
        async deleteTask(task) {
            if (!confirm('Supprimer cette tâche ?')) {
                return;
            }

            try {
                await this.fetchJson(`/api/schools/${this.schoolId}/tasks/${task.id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    },
                });

                this.showFlash('Tâche supprimée.', 'success');
                await this.loadSchool();
            } catch (error) {
                this.showFlash(error.message || 'Erreur lors de la suppression', 'error');
            }
        },
        async importStudentsCsv() {
            const fileInput = this.$refs.studentsImportFile;
            const file = fileInput?.files?.[0];

            if (!file) {
                this.showFlash('Choisis un fichier CSV.', 'error');
                return;
            }

            this.importingStudents = true;
            try {
                const formData = new FormData();
                formData.append('school_id', String(this.schoolId));
                formData.append('file', file);

                const response = await fetch(`/api/schools/${this.schoolId}/students/import`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${this.token}`,
                        'Accept': 'application/json',
                    },
                    credentials: 'same-origin',
                    body: formData,
                });

                const payload = await response.json();
                if (!response.ok || !payload.success) {
                    throw new Error(payload.message || 'Import impossible');
                }

                this.showFlash(payload.message || 'Import terminé.', 'success');
                fileInput.value = '';
                await this.loadSchool();
            } catch (error) {
                this.showFlash(error.message || 'Erreur lors de l\'import', 'error');
            } finally {
                this.importingStudents = false;
            }
        },
    };
}
</script>
@endsection
