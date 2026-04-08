@extends('layouts.app')
@section('title', 'Mon panier - Lumo Plateforme')
@section('content')
<style>
@keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
.fade-in-up { animation: fadeInUp 0.6s ease forwards; }
</style>

<div x-data="{
    items: [
        { id:1, name:'Calculatrice scientifique CASIO fx-991', price:15000, qty:1, emoji:'🔢' },
        { id:2, name:'Dictionnaire Larousse 2025', price:8500, qty:2, emoji:'📚' },
        { id:3, name:'Sac à dos scolaire Premium', price:22000, qty:1, emoji:'🎒' }
    ],
    promo: '',
    promoApplied: false,
    promoDiscount: 0,
    get subtotal() { return this.items.reduce((s, i) => s + i.price * i.qty, 0); },
    get delivery() { return this.subtotal > 0 ? 2500 : 0; },
    get tva() { return Math.round(this.subtotal * 0.18); },
    get total() { return this.subtotal + this.delivery + this.tva - this.promoDiscount; },
    removeItem(id) { this.items = this.items.filter(i => i.id !== id); },
    applyPromo() {
        if (this.promo.toUpperCase() === 'LUMO10') {
            this.promoDiscount = Math.round(this.subtotal * 0.1);
            this.promoApplied = true;
        }
    },
    formatPrice(p) { return p.toLocaleString('fr-FR') + ' FCFA'; }
}">

    <div class="mb-6 fade-in-up">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">🛒 Mon panier</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1" x-text="items.length + ' article(s) dans votre panier'"></p>
    </div>

    <!-- Empty State -->
    <div x-show="items.length === 0" class="text-center py-16 bg-white dark:bg-gray-800 rounded-2xl shadow-sm fade-in-up">
        <p class="text-6xl mb-4">🛒</p>
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Votre panier est vide</h2>
        <p class="text-gray-500 dark:text-gray-400 mb-6">Découvrez nos produits et ajoutez-les à votre panier</p>
        <a href="/shop" class="bg-indigo-600 text-white px-6 py-3 rounded-xl font-medium hover:bg-indigo-700 transition">Découvrir la boutique</a>
    </div>

    <div x-show="items.length > 0" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Cart Items -->
        <div class="lg:col-span-2 space-y-4">
            <template x-for="item in items" :key="item.id">
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm flex gap-4 fade-in-up">
                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-xl flex items-center justify-center text-3xl flex-shrink-0" x-text="item.emoji"></div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-medium text-gray-900 dark:text-white text-sm" x-text="item.name"></h3>
                        <p class="text-indigo-600 font-semibold mt-1" x-text="formatPrice(item.price)"></p>
                        <div class="flex items-center gap-3 mt-3">
                            <!-- Qty -->
                            <div class="flex items-center border border-gray-200 dark:border-gray-600 rounded-xl overflow-hidden">
                                <button @click="item.qty = Math.max(1, item.qty - 1)" class="px-3 py-1.5 hover:bg-gray-100 dark:hover:bg-gray-700 transition text-lg font-medium text-gray-600 dark:text-gray-300">−</button>
                                <span class="px-3 py-1.5 text-sm font-medium text-gray-900 dark:text-white" x-text="item.qty"></span>
                                <button @click="item.qty++" class="px-3 py-1.5 hover:bg-gray-100 dark:hover:bg-gray-700 transition text-lg font-medium text-gray-600 dark:text-gray-300">+</button>
                            </div>
                            <button @click="removeItem(item.id)" class="text-red-500 hover:text-red-700 text-sm">🗑️ Supprimer</button>
                        </div>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="font-bold text-gray-900 dark:text-white" x-text="formatPrice(item.price * item.qty)"></p>
                    </div>
                </div>
            </template>

            <div class="flex gap-3">
                <a href="/shop" class="flex-1 text-center border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 py-3 rounded-xl font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition text-sm">← Continuer les achats</a>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="space-y-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm fade-in-up" style="animation-delay:0.2s">
                <h2 class="font-semibold text-gray-900 dark:text-white mb-4">Récapitulatif</h2>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between text-gray-600 dark:text-gray-400">
                        <span>Sous-total</span>
                        <span x-text="formatPrice(subtotal)"></span>
                    </div>
                    <div class="flex justify-between text-gray-600 dark:text-gray-400">
                        <span>Frais de livraison</span>
                        <span x-text="formatPrice(delivery)"></span>
                    </div>
                    <div class="flex justify-between text-gray-600 dark:text-gray-400">
                        <span>TVA (18%)</span>
                        <span x-text="formatPrice(tva)"></span>
                    </div>
                    <div x-show="promoApplied" class="flex justify-between text-green-600 font-medium">
                        <span>Réduction promo</span>
                        <span x-text="'-' + formatPrice(promoDiscount)"></span>
                    </div>
                    <div class="border-t border-gray-100 dark:border-gray-700 pt-3 flex justify-between font-bold text-gray-900 dark:text-white text-base">
                        <span>Total</span>
                        <span x-text="formatPrice(total)"></span>
                    </div>
                </div>

                <!-- Promo Code -->
                <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Code promo</p>
                    <div class="flex gap-2">
                        <input x-model="promo" type="text" placeholder="Ex: LUMO10" class="flex-1 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                        <button @click="applyPromo()" class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-3 py-2 rounded-xl text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition">Appliquer</button>
                    </div>
                    <p x-show="promoApplied" class="text-xs text-green-600 mt-1">✓ Code promo appliqué (-10%)</p>
                </div>

                <a href="/shop/checkout" class="block w-full mt-5 bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-xl font-semibold text-center transition shadow-lg">
                    Passer la commande →
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
