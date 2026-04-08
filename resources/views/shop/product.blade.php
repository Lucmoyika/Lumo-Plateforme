@extends('layouts.app')
@section('title', 'Calculatrice scientifique CASIO fx-991 - Lumo Plateforme')
@section('content')
<style>
@keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
@keyframes toastIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
@keyframes toastOut { from { opacity: 1; } to { opacity: 0; } }
.fade-in-up { animation: fadeInUp 0.6s ease forwards; }
.toast-show { animation: toastIn 0.3s ease forwards; }
</style>

<div x-data="{
    activeTab: 'description',
    qty: 1,
    selectedThumb: 0,
    showToast: false,
    addToCart() {
        this.showToast = true;
        setTimeout(() => this.showToast = false, 2500);
    }
}">
    <!-- Breadcrumb -->
    <nav class="text-sm text-gray-500 dark:text-gray-400 mb-5 fade-in-up">
        <a href="/shop" class="hover:text-indigo-600 transition">Boutique</a>
        <span class="mx-2">›</span>
        <span>Calculatrice scientifique CASIO fx-991</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Images -->
        <div class="fade-in-up">
            <div class="bg-gray-100 dark:bg-gray-800 rounded-2xl h-72 flex items-center justify-center text-8xl mb-3 shadow-sm">🔢</div>
            <div class="grid grid-cols-4 gap-2">
                <template x-for="(t, i) in ['🔢','📐','📏','🧮']">
                    <button @click="selectedThumb = i" :class="selectedThumb === i ? 'ring-2 ring-indigo-500' : ''" class="bg-gray-100 dark:bg-gray-800 rounded-xl h-16 flex items-center justify-center text-2xl hover:ring-2 hover:ring-indigo-300 transition" x-text="t"></button>
                </template>
            </div>
        </div>

        <!-- Product Info -->
        <div class="fade-in-up" style="animation-delay:0.1s">
            <div class="flex items-center gap-2 mb-2">
                <span class="bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 text-xs px-2.5 py-1 rounded-full font-medium">En stock</span>
                <span class="bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 text-xs px-2.5 py-1 rounded-full font-medium">Populaire</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Calculatrice scientifique CASIO fx-991EX</h1>

            <!-- Stars -->
            <div class="flex items-center gap-2 mb-4">
                <div class="flex text-yellow-400">
                    <span>★</span><span>★</span><span>★</span><span>★</span><span class="text-gray-300 dark:text-gray-600">★</span>
                </div>
                <span class="text-sm text-gray-500 dark:text-gray-400">4.0/5 (124 avis)</span>
            </div>

            <p class="text-3xl font-bold text-indigo-600 mb-1">15 000 <span class="text-xl">FCFA</span></p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-5 line-through">18 000 FCFA</p>

            <!-- Qty selector -->
            <div class="flex items-center gap-4 mb-5">
                <div class="flex items-center border border-gray-300 dark:border-gray-600 rounded-xl overflow-hidden">
                    <button @click="qty = Math.max(1, qty - 1)" class="px-4 py-2.5 hover:bg-gray-100 dark:hover:bg-gray-700 transition text-xl font-medium text-gray-600 dark:text-gray-300">−</button>
                    <span class="px-4 py-2.5 text-sm font-medium text-gray-900 dark:text-white" x-text="qty"></span>
                    <button @click="qty++" class="px-4 py-2.5 hover:bg-gray-100 dark:hover:bg-gray-700 transition text-xl font-medium text-gray-600 dark:text-gray-300">+</button>
                </div>
                <span class="text-sm text-gray-500 dark:text-gray-400">48 unités disponibles</span>
            </div>

            <div class="flex gap-3">
                <button @click="addToCart()" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-xl font-semibold transition shadow-lg">
                    🛒 Ajouter au panier
                </button>
                <a href="/shop/checkout" class="flex-1 bg-green-600 hover:bg-green-700 text-white py-3 rounded-xl font-semibold transition text-center">
                    ⚡ Acheter maintenant
                </a>
            </div>

            <div class="mt-4 space-y-2 text-sm text-gray-500 dark:text-gray-400">
                <p>🚚 Livraison en 2-3 jours ouvrables</p>
                <p>↩️ Retours acceptés sous 15 jours</p>
                <p>✅ Produit authentique garanti</p>
            </div>
        </div>
    </div>

    <!-- Product Tabs -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden fade-in-up" style="animation-delay:0.2s">
        <div class="flex border-b border-gray-100 dark:border-gray-700">
            <button @click="activeTab = 'description'" :class="activeTab === 'description' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500'" class="px-6 py-3 text-sm font-medium transition">Description</button>
            <button @click="activeTab = 'specs'" :class="activeTab === 'specs' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500'" class="px-6 py-3 text-sm font-medium transition">Spécifications</button>
            <button @click="activeTab = 'avis'" :class="activeTab === 'avis' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500'" class="px-6 py-3 text-sm font-medium transition">Avis (124)</button>
        </div>
        <div class="p-6">
            <div x-show="activeTab === 'description'">
                <p class="text-gray-600 dark:text-gray-400 text-sm mb-3">La calculatrice scientifique CASIO fx-991EX est la calculatrice de référence pour les lycéens et étudiants en Afrique. Avec plus de 552 fonctions intégrées, elle couvre tous les besoins en mathématiques, physique et chimie.</p>
                <p class="text-gray-600 dark:text-gray-400 text-sm">Indispensable pour les examens du BEPC, Baccalauréat et les concours d'entrée en grandes écoles. Compatible avec les programmes scolaires ivoiriens et CEDEAO.</p>
            </div>
            <div x-show="activeTab === 'specs'">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            <tr><td class="py-2 text-gray-500 w-1/2">Modèle</td><td class="py-2 font-medium text-gray-900 dark:text-white">CASIO fx-991EX ClassWiz</td></tr>
                            <tr><td class="py-2 text-gray-500">Nombre de fonctions</td><td class="py-2 font-medium text-gray-900 dark:text-white">552 fonctions</td></tr>
                            <tr><td class="py-2 text-gray-500">Affichage</td><td class="py-2 font-medium text-gray-900 dark:text-white">LCD haute résolution 192×63</td></tr>
                            <tr><td class="py-2 text-gray-500">Alimentation</td><td class="py-2 font-medium text-gray-900 dark:text-white">Solaire + pile LR44</td></tr>
                            <tr><td class="py-2 text-gray-500">Dimensions</td><td class="py-2 font-medium text-gray-900 dark:text-white">77 × 165.5 × 11.1 mm</td></tr>
                            <tr><td class="py-2 text-gray-500">Poids</td><td class="py-2 font-medium text-gray-900 dark:text-white">95 g</td></tr>
                            <tr><td class="py-2 text-gray-500">Garantie</td><td class="py-2 font-medium text-gray-900 dark:text-white">1 an constructeur</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div x-show="activeTab === 'avis'">
                <div class="space-y-5">
                    <div class="border-b border-gray-100 dark:border-gray-700 pb-5">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-9 h-9 bg-indigo-500 rounded-full flex items-center justify-center text-white font-bold text-sm">KA</div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white text-sm">Kouassi Ange</p>
                                <div class="flex text-yellow-400 text-xs">★★★★★</div>
                            </div>
                            <span class="ml-auto text-xs text-gray-400">10 Jan 2025</span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Excellente calculatrice ! Parfaite pour le bac. Livraison rapide et bien emballée. Je recommande vivement.</p>
                    </div>
                    <div class="border-b border-gray-100 dark:border-gray-700 pb-5">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-9 h-9 bg-green-500 rounded-full flex items-center justify-center text-white font-bold text-sm">FD</div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white text-sm">Fatou Diallo</p>
                                <div class="flex text-yellow-400 text-xs">★★★★<span class="text-gray-300 dark:text-gray-600">★</span></div>
                            </div>
                            <span class="ml-auto text-xs text-gray-400">5 Jan 2025</span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Très bonne qualité. Mon fils utilise pour ses cours de terminale. La fonction tableur est incroyable.</p>
                    </div>
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-9 h-9 bg-orange-500 rounded-full flex items-center justify-center text-white font-bold text-sm">MT</div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white text-sm">Moussa Traoré</p>
                                <div class="flex text-yellow-400 text-xs">★★★★★</div>
                            </div>
                            <span class="ml-auto text-xs text-gray-400">28 Déc 2024</span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Produit authentique, identique à ceux vendus en Europe. Prix correct pour Abidjan. Merci Lumo !</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <div class="mt-8 fade-in-up" style="animation-delay:0.3s">
        <h2 class="font-semibold text-gray-900 dark:text-white mb-4">Produits similaires</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm hover:shadow-md transition cursor-pointer">
                <div class="text-4xl text-center mb-3">📐</div>
                <p class="text-sm font-medium text-gray-900 dark:text-white text-center">Équerre scolaire set</p>
                <p class="text-indigo-600 font-semibold text-center mt-1">3 500 FCFA</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm hover:shadow-md transition cursor-pointer">
                <div class="text-4xl text-center mb-3">📚</div>
                <p class="text-sm font-medium text-gray-900 dark:text-white text-center">Manuel Mathématiques Tle</p>
                <p class="text-indigo-600 font-semibold text-center mt-1">9 000 FCFA</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm hover:shadow-md transition cursor-pointer">
                <div class="text-4xl text-center mb-3">🖊️</div>
                <p class="text-sm font-medium text-gray-900 dark:text-white text-center">Set stylos bille 20pc</p>
                <p class="text-indigo-600 font-semibold text-center mt-1">2 500 FCFA</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm hover:shadow-md transition cursor-pointer">
                <div class="text-4xl text-center mb-3">🎒</div>
                <p class="text-sm font-medium text-gray-900 dark:text-white text-center">Sac à dos lycéen</p>
                <p class="text-indigo-600 font-semibold text-center mt-1">18 000 FCFA</p>
            </div>
        </div>
    </div>

    <!-- Toast -->
    <div x-show="showToast" x-transition role="alert" aria-live="polite" class="fixed bottom-6 right-6 bg-green-600 text-white px-5 py-3 rounded-2xl shadow-2xl z-50 flex items-center gap-2">
        ✅ Produit ajouté au panier !
    </div>
</div>
@endsection
