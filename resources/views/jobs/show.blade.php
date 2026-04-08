@extends('layouts.app')
@section('title', 'Développeur Full-Stack Senior - Lumo Plateforme')
@section('content')
<style>
@keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
@keyframes slideInLeft { from { opacity: 0; transform: translateX(-30px); } to { opacity: 1; transform: translateX(0); } }
@keyframes pulseBtn { 0%,100% { transform: scale(1); } 50% { transform: scale(1.05); } }
.fade-in-up { animation: fadeInUp 0.6s ease forwards; }
.pulse-btn { animation: pulseBtn 2s infinite; }
</style>

<div x-data="{ showApply: false, saved: false, activeTab: 'description' }">
    <!-- Back button -->
    <div class="mb-4 fade-in-up">
        <a href="/jobs" class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-700 text-sm font-medium">
            ← Retour aux offres
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Job Header -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm fade-in-up" style="animation-delay:0.1s">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-start gap-4">
                        <div class="w-16 h-16 bg-indigo-100 dark:bg-indigo-900 rounded-2xl flex items-center justify-center text-3xl flex-shrink-0">🏢</div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Développeur Full-Stack Senior</h1>
                            <p class="text-indigo-600 dark:text-indigo-400 font-medium mt-1">TechAfrique SARL</p>
                            <div class="flex flex-wrap gap-2 mt-3">
                                <span class="inline-flex items-center gap-1 text-sm text-gray-600 dark:text-gray-400"><span>📍</span> Abidjan, Côte d'Ivoire</span>
                                <span class="inline-flex items-center gap-1 text-sm text-gray-600 dark:text-gray-400"><span>⏰</span> CDI</span>
                                <span class="inline-flex items-center gap-1 text-sm text-green-700 dark:text-green-400 font-semibold"><span>💰</span> 800 000 – 1 200 000 FCFA/mois</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2 flex-shrink-0">
                        <button @click="saved = !saved" class="p-2.5 border rounded-xl transition" :class="saved ? 'bg-yellow-50 border-yellow-400 text-yellow-600' : 'border-gray-300 dark:border-gray-600 text-gray-500 hover:border-yellow-400 hover:text-yellow-500'">
                            <svg class="h-5 w-5" fill="none" :fill="saved ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                        </button>
                        <button class="p-2.5 border border-gray-300 dark:border-gray-600 text-gray-500 hover:border-indigo-400 hover:text-indigo-500 rounded-xl transition">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Skills Tags -->
                <div class="flex flex-wrap gap-2 mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <span class="bg-indigo-50 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 px-3 py-1 rounded-full text-xs font-medium">React</span>
                    <span class="bg-green-50 dark:bg-green-900 text-green-700 dark:text-green-300 px-3 py-1 rounded-full text-xs font-medium">Node.js</span>
                    <span class="bg-yellow-50 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-300 px-3 py-1 rounded-full text-xs font-medium">MongoDB</span>
                    <span class="bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300 px-3 py-1 rounded-full text-xs font-medium">Docker</span>
                    <span class="bg-purple-50 dark:bg-purple-900 text-purple-700 dark:text-purple-300 px-3 py-1 rounded-full text-xs font-medium">TypeScript</span>
                    <span class="bg-red-50 dark:bg-red-900 text-red-700 dark:text-red-300 px-3 py-1 rounded-full text-xs font-medium">AWS</span>
                </div>

                <button @click="showApply = true" class="w-full mt-4 bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-xl font-semibold transition pulse-btn shadow-lg">
                    💼 Postuler maintenant
                </button>
            </div>

            <!-- Job Details Tabs -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden fade-in-up" style="animation-delay:0.2s">
                <div class="flex border-b border-gray-100 dark:border-gray-700">
                    <button @click="activeTab = 'description'" :class="activeTab === 'description' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-3 text-sm font-medium transition">Description</button>
                    <button @click="activeTab = 'profil'" :class="activeTab === 'profil' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-3 text-sm font-medium transition">Profil requis</button>
                    <button @click="activeTab = 'avantages'" :class="activeTab === 'avantages' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-3 text-sm font-medium transition">Avantages</button>
                </div>
                <div class="p-6">
                    <div x-show="activeTab === 'description'">
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Mission</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">Rejoignez TechAfrique SARL en tant que développeur Full-Stack Senior. Vous serez responsable du développement et de la maintenance de nos plateformes numériques desservant plus de 500 000 utilisateurs à travers l'Afrique de l'Ouest.</p>
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Responsabilités</h3>
                        <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                            <li class="flex items-start gap-2"><span class="text-green-500 mt-0.5">✓</span> Concevoir et développer des fonctionnalités frontend avec React/TypeScript</li>
                            <li class="flex items-start gap-2"><span class="text-green-500 mt-0.5">✓</span> Développer et maintenir des APIs REST avec Node.js et Express</li>
                            <li class="flex items-start gap-2"><span class="text-green-500 mt-0.5">✓</span> Gérer les bases de données MongoDB et PostgreSQL</li>
                            <li class="flex items-start gap-2"><span class="text-green-500 mt-0.5">✓</span> Déployer et gérer les applications sur AWS avec Docker</li>
                            <li class="flex items-start gap-2"><span class="text-green-500 mt-0.5">✓</span> Collaborer avec l'équipe product pour concevoir de nouvelles fonctionnalités</li>
                            <li class="flex items-start gap-2"><span class="text-green-500 mt-0.5">✓</span> Mentorer les développeurs juniors de l'équipe</li>
                        </ul>
                    </div>
                    <div x-show="activeTab === 'profil'">
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Profil recherché</h3>
                        <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                            <li class="flex items-start gap-2"><span class="text-indigo-500 mt-0.5">•</span> Bac+4/5 en Informatique, Génie Logiciel ou équivalent</li>
                            <li class="flex items-start gap-2"><span class="text-indigo-500 mt-0.5">•</span> 5+ ans d'expérience en développement Full-Stack</li>
                            <li class="flex items-start gap-2"><span class="text-indigo-500 mt-0.5">•</span> Maîtrise de React, Node.js, MongoDB, Docker</li>
                            <li class="flex items-start gap-2"><span class="text-indigo-500 mt-0.5">•</span> Expérience avec les architectures microservices</li>
                            <li class="flex items-start gap-2"><span class="text-indigo-500 mt-0.5">•</span> Bonne communication en français et anglais</li>
                        </ul>
                    </div>
                    <div x-show="activeTab === 'avantages'">
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Avantages offerts</h3>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-green-50 dark:bg-green-900 rounded-xl p-3 text-sm text-green-700 dark:text-green-300">🏥 Assurance maladie famille</div>
                            <div class="bg-blue-50 dark:bg-blue-900 rounded-xl p-3 text-sm text-blue-700 dark:text-blue-300">🏠 Aide au logement</div>
                            <div class="bg-purple-50 dark:bg-purple-900 rounded-xl p-3 text-sm text-purple-700 dark:text-purple-300">📚 Formation continue</div>
                            <div class="bg-orange-50 dark:bg-orange-900 rounded-xl p-3 text-sm text-orange-700 dark:text-orange-300">🏖️ 30 jours de congés</div>
                            <div class="bg-yellow-50 dark:bg-yellow-900 rounded-xl p-3 text-sm text-yellow-700 dark:text-yellow-300">💻 Équipement fourni</div>
                            <div class="bg-red-50 dark:bg-red-900 rounded-xl p-3 text-sm text-red-700 dark:text-red-300">🏆 Bonus annuel</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Similar Jobs -->
            <div class="fade-in-up" style="animation-delay:0.3s">
                <h2 class="font-semibold text-gray-900 dark:text-white mb-3">Offres similaires</h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm hover:shadow-md transition">
                        <p class="font-medium text-gray-900 dark:text-white text-sm">Développeur Backend</p>
                        <p class="text-indigo-600 text-xs mt-1">AfriTech Solutions</p>
                        <p class="text-gray-500 text-xs mt-1">Dakar · 600 000 FCFA</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm hover:shadow-md transition">
                        <p class="font-medium text-gray-900 dark:text-white text-sm">Lead Developer</p>
                        <p class="text-indigo-600 text-xs mt-1">Orange CI</p>
                        <p class="text-gray-500 text-xs mt-1">Abidjan · 1 000 000 FCFA</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm hover:shadow-md transition">
                        <p class="font-medium text-gray-900 dark:text-white text-sm">Ingénieur DevOps</p>
                        <p class="text-indigo-600 text-xs mt-1">Jumia Group</p>
                        <p class="text-gray-500 text-xs mt-1">Lagos · 900 000 FCFA</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Company Sidebar -->
        <div class="space-y-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm fade-in-up" style="animation-delay:0.2s">
                <div class="text-center mb-4">
                    <div class="w-16 h-16 bg-indigo-100 dark:bg-indigo-900 rounded-2xl mx-auto flex items-center justify-center text-3xl mb-3">🏢</div>
                    <h3 class="font-semibold text-gray-900 dark:text-white">TechAfrique SARL</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Technologie · Abidjan</p>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Leader africain des solutions numériques, TechAfrique développe des plateformes innovantes pour l'éducation, le commerce et les services en Afrique de l'Ouest depuis 2015.</p>
                <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                    <div class="flex justify-between"><span>👥 Taille</span><span class="font-medium text-gray-900 dark:text-white">50-200 employés</span></div>
                    <div class="flex justify-between"><span>📅 Fondée</span><span class="font-medium text-gray-900 dark:text-white">2015</span></div>
                    <div class="flex justify-between"><span>💼 Offres</span><span class="font-medium text-gray-900 dark:text-white">12 actives</span></div>
                </div>
                <a href="/companies/1" class="block mt-4 text-center text-indigo-600 hover:text-indigo-700 text-sm font-medium border border-indigo-200 dark:border-indigo-700 rounded-xl py-2 hover:bg-indigo-50 dark:hover:bg-indigo-900 transition">
                    Voir le profil complet →
                </a>
            </div>

            <div class="bg-indigo-50 dark:bg-indigo-900 rounded-2xl p-4 fade-in-up" style="animation-delay:0.3s">
                <h4 class="font-medium text-indigo-800 dark:text-indigo-200 text-sm mb-2">📊 Statistiques</h4>
                <div class="space-y-1 text-sm text-indigo-700 dark:text-indigo-300">
                    <div class="flex justify-between"><span>Candidatures</span><span class="font-semibold">47</span></div>
                    <div class="flex justify-between"><span>Vues</span><span class="font-semibold">1,284</span></div>
                    <div class="flex justify-between"><span>Publiée</span><span class="font-semibold">Il y a 3 jours</span></div>
                    <div class="flex justify-between"><span>Expire</span><span class="font-semibold">Dans 27 jours</span></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Apply Modal -->
    <div x-show="showApply" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">
        <div @click.away="showApply = false" class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-lg">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">💼 Postuler à cette offre</h2>
                <button @click="showApply = false" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">CV (PDF)</label>
                    <input type="file" accept=".pdf,.doc,.docx" class="w-full border border-dashed border-gray-300 dark:border-gray-600 rounded-xl px-3 py-3 text-sm text-gray-500 focus:ring-2 focus:ring-indigo-500 outline-none cursor-pointer">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Lettre de motivation</label>
                    <textarea rows="5" placeholder="Expliquez pourquoi vous êtes le candidat idéal pour ce poste..." class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none resize-none"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Disponibilité</label>
                    <select class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                        <option>Immédiatement</option>
                        <option>Dans 1 mois</option>
                        <option>Dans 2 mois</option>
                        <option>Dans 3 mois</option>
                    </select>
                </div>
            </div>
            <div class="p-6 border-t border-gray-100 dark:border-gray-700 flex gap-3">
                <button @click="showApply = false" class="flex-1 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 py-2.5 rounded-xl font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition">Annuler</button>
                <button @click="showApply = false" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white py-2.5 rounded-xl font-medium transition">Envoyer ma candidature</button>
            </div>
        </div>
    </div>
</div>
@endsection
