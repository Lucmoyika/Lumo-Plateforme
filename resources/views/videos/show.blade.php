@extends('layouts.app')
@section('title', 'Introduction à la Programmation Web - Lumo Plateforme')
@section('content')
<style>
@keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
@keyframes slideInLeft { from { opacity: 0; transform: translateX(-30px); } to { opacity: 1; transform: translateX(0); } }
.fade-in-up { animation: fadeInUp 0.6s ease forwards; }
.row-enter { animation: slideInLeft 0.4s ease forwards; }
</style>

<div x-data="{ liked: false, saved: false, comment: '' }" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-5">
        <!-- Video Player -->
        <div class="bg-black rounded-2xl overflow-hidden shadow-xl fade-in-up relative aspect-video flex flex-col">
            <div class="flex-1 flex items-center justify-center">
                <button class="w-16 h-16 bg-white bg-opacity-90 rounded-full flex items-center justify-center hover:bg-opacity-100 transition shadow-xl">
                    <svg class="h-7 w-7 text-indigo-600 ml-1" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8 5v14l11-7z"/>
                    </svg>
                </button>
            </div>
            <!-- Controls Bar -->
            <div class="bg-black bg-opacity-80 p-3">
                <div class="h-1.5 bg-gray-600 rounded-full mb-2 cursor-pointer">
                    <div class="h-full bg-indigo-500 rounded-full" style="width:35%"></div>
                </div>
                <div class="flex items-center justify-between text-white text-xs">
                    <div class="flex items-center gap-3">
                        <button class="hover:text-indigo-400 transition">⏮</button>
                        <button class="hover:text-indigo-400 transition text-base">▶</button>
                        <button class="hover:text-indigo-400 transition">⏭</button>
                        <span class="text-gray-400">12:34 / 35:20</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <button class="hover:text-indigo-400 transition">🔊</button>
                        <button class="hover:text-indigo-400 transition">⚙️</button>
                        <button class="hover:text-indigo-400 transition">⛶</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Video Info -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm fade-in-up" style="animation-delay:0.1s">
            <h1 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Introduction à la Programmation Web avec JavaScript</h1>
            <div class="flex flex-wrap items-center gap-3 text-sm text-gray-500 dark:text-gray-400 mb-4">
                <span>👁️ 24 856 vues</span>
                <span>📅 10 Jan 2025</span>
                <span>⏱️ 35:20</span>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-500 rounded-full flex items-center justify-center text-white font-bold">KA</div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white text-sm">Prof. Kouamé Assi</p>
                        <p class="text-xs text-gray-500">Développement Web Complet · 48 vidéos</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button @click="liked = !liked" :class="liked ? 'bg-blue-100 dark:bg-blue-900 text-blue-600' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300'" class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm font-medium hover:bg-blue-100 dark:hover:bg-blue-900 hover:text-blue-600 transition">
                        👍 <span x-text="liked ? '1 246' : '1 245'"></span>
                    </button>
                    <button @click="saved = !saved" :class="saved ? 'bg-yellow-100 dark:bg-yellow-900 text-yellow-600' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300'" class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm font-medium transition">
                        🔖 Sauvegarder
                    </button>
                    <button class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                        📤 Partager
                    </button>
                </div>
            </div>

            <!-- Course Progress -->
            <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-gray-600 dark:text-gray-400">Progression du cours</span>
                    <span class="font-semibold text-indigo-600">35%</span>
                </div>
                <div class="h-2 bg-gray-100 dark:bg-gray-700 rounded-full">
                    <div class="h-full bg-indigo-600 rounded-full" style="width:35%"></div>
                </div>
            </div>
        </div>

        <!-- Comments -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm fade-in-up" style="animation-delay:0.2s">
            <h2 class="font-semibold text-gray-900 dark:text-white mb-4">Commentaires (48)</h2>
            <div class="flex gap-3 mb-6">
                <div class="w-9 h-9 bg-purple-500 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0">Moi</div>
                <div class="flex-1">
                    <textarea x-model="comment" rows="2" placeholder="Ajouter un commentaire..." class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none resize-none"></textarea>
                    <div x-show="comment.length > 0" class="flex justify-end gap-2 mt-2">
                        <button @click="comment = ''" class="text-gray-500 px-3 py-1.5 text-sm">Annuler</button>
                        <button class="bg-indigo-600 text-white px-4 py-1.5 rounded-lg text-sm font-medium hover:bg-indigo-700 transition">Commenter</button>
                    </div>
                </div>
            </div>
            <div class="space-y-5">
                <div class="flex gap-3 row-enter">
                    <div class="w-9 h-9 bg-green-500 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0">KA</div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1"><span class="font-medium text-gray-900 dark:text-white text-sm">Kouassi Ange</span><span class="text-xs text-gray-400">Il y a 2 jours</span></div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Super explication ! Maintenant je comprends les closures en JavaScript. Merci Prof. Assi !</p>
                        <button class="text-xs text-gray-400 hover:text-blue-600 mt-1">👍 42</button>
                    </div>
                </div>
                <div class="flex gap-3 row-enter" style="animation-delay:0.1s">
                    <div class="w-9 h-9 bg-orange-500 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0">FD</div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1"><span class="font-medium text-gray-900 dark:text-white text-sm">Fatou Diallo</span><span class="text-xs text-gray-400">Il y a 3 jours</span></div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Excellente vidéo. J'ai enfin compris le DOM manipulation. Pouvez-vous faire une vidéo sur React ?</p>
                        <button class="text-xs text-gray-400 hover:text-blue-600 mt-1">👍 28</button>
                    </div>
                </div>
                <div class="flex gap-3 row-enter" style="animation-delay:0.2s">
                    <div class="w-9 h-9 bg-teal-500 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0">MT</div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1"><span class="font-medium text-gray-900 dark:text-white text-sm">Moussa Traoré</span><span class="text-xs text-gray-400">Il y a 5 jours</span></div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Le meilleur cours de JS en français ! Clair, concis et avec des exemples africains. Continuez !</p>
                        <button class="text-xs text-gray-400 hover:text-blue-600 mt-1">👍 61</button>
                    </div>
                </div>
                <div class="flex gap-3 row-enter" style="animation-delay:0.3s">
                    <div class="w-9 h-9 bg-pink-500 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0">NA</div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1"><span class="font-medium text-gray-900 dark:text-white text-sm">Ngozi Adeyemi</span><span class="text-xs text-gray-400">Il y a 1 semaine</span></div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">J'ai commencé le cours depuis Lagos et c'est fantastique ! Les sous-titres français aident beaucoup.</p>
                        <button class="text-xs text-gray-400 hover:text-blue-600 mt-1">👍 17</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-4">
        <!-- Chapters -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden fade-in-up">
            <div class="p-4 border-b border-gray-100 dark:border-gray-700">
                <h2 class="font-semibold text-gray-900 dark:text-white">📋 Chapitres du cours</h2>
            </div>
            <div class="divide-y divide-gray-50 dark:divide-gray-700">
                <div class="p-3 bg-indigo-50 dark:bg-indigo-900 flex items-center gap-3">
                    <div class="w-7 h-7 bg-indigo-600 rounded-full flex items-center justify-center text-white text-xs font-bold">1</div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-indigo-700 dark:text-indigo-300 truncate">Introduction à JavaScript</p>
                        <p class="text-xs text-indigo-500">▶ En cours · 35:20</p>
                    </div>
                </div>
                <div class="p-3 flex items-center gap-3 hover:bg-gray-50 dark:hover:bg-gray-750 cursor-pointer transition">
                    <div class="w-7 h-7 bg-green-500 rounded-full flex items-center justify-center text-white text-xs">✓</div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-700 dark:text-gray-300 truncate">Variables et Types de données</p>
                        <p class="text-xs text-gray-400">28:15</p>
                    </div>
                </div>
                <div class="p-3 flex items-center gap-3 hover:bg-gray-50 dark:hover:bg-gray-750 cursor-pointer transition">
                    <div class="w-7 h-7 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center text-gray-500 text-xs">3</div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-700 dark:text-gray-300 truncate">Fonctions et Closures</p>
                        <p class="text-xs text-gray-400">42:08</p>
                    </div>
                </div>
                <div class="p-3 flex items-center gap-3 hover:bg-gray-50 dark:hover:bg-gray-750 cursor-pointer transition">
                    <div class="w-7 h-7 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center text-gray-500 text-xs">4</div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-700 dark:text-gray-300 truncate">Manipulation du DOM</p>
                        <p class="text-xs text-gray-400">38:45</p>
                    </div>
                </div>
                <div class="p-3 flex items-center gap-3 hover:bg-gray-50 dark:hover:bg-gray-750 cursor-pointer transition">
                    <div class="w-7 h-7 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center text-gray-500 text-xs">5</div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-700 dark:text-gray-300 truncate">Événements JavaScript</p>
                        <p class="text-xs text-gray-400">31:22</p>
                    </div>
                </div>
                <div class="p-3 flex items-center gap-3 hover:bg-gray-50 dark:hover:bg-gray-750 cursor-pointer transition">
                    <div class="w-7 h-7 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center text-gray-500 text-xs">6</div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-700 dark:text-gray-300 truncate">API Fetch et Promesses</p>
                        <p class="text-xs text-gray-400">45:10</p>
                    </div>
                </div>
                <div class="p-3 flex items-center gap-3 hover:bg-gray-50 dark:hover:bg-gray-750 cursor-pointer transition">
                    <div class="w-7 h-7 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center text-gray-500 text-xs">7</div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-700 dark:text-gray-300 truncate">Projet: Site e-commerce</p>
                        <p class="text-xs text-gray-400">1:12:30</p>
                    </div>
                </div>
                <div class="p-3 flex items-center gap-3 hover:bg-gray-50 dark:hover:bg-gray-750 cursor-pointer transition">
                    <div class="w-7 h-7 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center text-gray-500 text-xs">8</div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-700 dark:text-gray-300 truncate">Tests et Débogage</p>
                        <p class="text-xs text-gray-400">28:55</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Videos -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden fade-in-up" style="animation-delay:0.2s">
            <div class="p-4 border-b border-gray-100 dark:border-gray-700">
                <h2 class="font-semibold text-gray-900 dark:text-white">▶️ Vidéos suggérées</h2>
            </div>
            <div class="divide-y divide-gray-50 dark:divide-gray-700">
                <a href="/videos/1" class="p-3 flex gap-3 hover:bg-gray-50 dark:hover:bg-gray-750 transition">
                    <div class="w-20 h-14 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center text-2xl flex-shrink-0">⚛️</div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">Introduction à React.js</p>
                        <p class="text-xs text-gray-400 mt-1">Prof. Assi · 52:18</p>
                    </div>
                </a>
                <a href="/videos/1" class="p-3 flex gap-3 hover:bg-gray-50 dark:hover:bg-gray-750 transition">
                    <div class="w-20 h-14 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center text-2xl flex-shrink-0">🗄️</div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">Node.js et Express.js</p>
                        <p class="text-xs text-gray-400 mt-1">Prof. Diallo · 48:45</p>
                    </div>
                </a>
                <a href="/videos/1" class="p-3 flex gap-3 hover:bg-gray-50 dark:hover:bg-gray-750 transition">
                    <div class="w-20 h-14 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center text-2xl flex-shrink-0">🎨</div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">CSS et Tailwind CSS</p>
                        <p class="text-xs text-gray-400 mt-1">Prof. Koné · 41:20</p>
                    </div>
                </a>
                <a href="/videos/1" class="p-3 flex gap-3 hover:bg-gray-50 dark:hover:bg-gray-750 transition">
                    <div class="w-20 h-14 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center text-2xl flex-shrink-0">🐘</div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">Base de données PostgreSQL</p>
                        <p class="text-xs text-gray-400 mt-1">Prof. Mensah · 38:10</p>
                    </div>
                </a>
                <a href="/videos/1" class="p-3 flex gap-3 hover:bg-gray-50 dark:hover:bg-gray-750 transition">
                    <div class="w-20 h-14 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center text-2xl flex-shrink-0">🐙</div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">Git et GitHub</p>
                        <p class="text-xs text-gray-400 mt-1">Prof. Assi · 29:35</p>
                    </div>
                </a>
                <a href="/videos/1" class="p-3 flex gap-3 hover:bg-gray-50 dark:hover:bg-gray-750 transition">
                    <div class="w-20 h-14 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center text-2xl flex-shrink-0">🐳</div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">Docker et Déploiement</p>
                        <p class="text-xs text-gray-400 mt-1">Prof. Traoré · 55:00</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
