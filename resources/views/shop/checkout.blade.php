@extends('layouts.app')
@section('title', 'Paiement - Lumo Plateforme')
@section('content')
<style>
@keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
@keyframes spin { to { transform: rotate(360deg); } }
.fade-in-up { animation: fadeInUp 0.6s ease forwards; }
.spin { animation: spin 1s linear infinite; }
</style>

<div x-data="{
    step: 1,
    loading: false,
    paymentMethod: '',
    address: { nom:'', tel:'', adresse:'', ville:'', pays:'Côte d\'Ivoire' },
    complete() {
        this.loading = true;
        setTimeout(() => { this.loading = false; this.step = 4; }, 2000);
    }
}">
    <div class="mb-6 fade-in-up">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">💳 Paiement</h1>
    </div>

    <!-- Step Indicator -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm mb-6 fade-in-up" style="animation-delay:0.1s">
        <div class="flex items-center justify-between">
            <template x-for="(label, i) in ['Adresse', 'Paiement', 'Confirmation']">
                <div class="flex items-center" :class="i < 2 ? 'flex-1' : ''">
                    <div class="flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold transition"
                            :class="step > i+1 ? 'bg-green-500 text-white' : step === i+1 ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-500'">
                            <span x-show="step <= i+1" x-text="i+1"></span>
                            <span x-show="step > i+1">✓</span>
                        </div>
                        <span class="text-xs mt-1 font-medium" :class="step === i+1 ? 'text-indigo-600' : 'text-gray-400'" x-text="label"></span>
                    </div>
                    <div x-show="i < 2" class="flex-1 h-0.5 mx-2 mb-4" :class="step > i+1 ? 'bg-green-400' : 'bg-gray-200 dark:bg-gray-700'"></div>
                </div>
            </template>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Steps -->
        <div class="lg:col-span-2">

            <!-- Step 1: Address -->
            <div x-show="step === 1" class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm fade-in-up">
                <h2 class="font-semibold text-gray-900 dark:text-white mb-5">📍 Adresse de livraison</h2>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nom complet</label>
                            <input x-model="address.nom" type="text" placeholder="Prénom Nom" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Téléphone</label>
                            <input x-model="address.tel" type="tel" placeholder="+225 07 XX XX XX XX" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Adresse</label>
                        <input x-model="address.adresse" type="text" placeholder="Rue, quartier, numéro..." class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ville</label>
                            <input x-model="address.ville" type="text" placeholder="Abidjan" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pays</label>
                            <select x-model="address.pays" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                                <option>Côte d'Ivoire</option><option>Sénégal</option><option>Mali</option>
                                <option>Burkina Faso</option><option>Ghana</option><option>Togo</option><option>Bénin</option>
                            </select>
                        </div>
                    </div>
                </div>
                <button @click="step = 2" class="w-full mt-6 bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-xl font-semibold transition">Suivant →</button>
            </div>

            <!-- Step 2: Payment -->
            <div x-show="step === 2" class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm fade-in-up">
                <h2 class="font-semibold text-gray-900 dark:text-white mb-5">💳 Mode de paiement</h2>
                <div class="space-y-3">
                    <label @click="paymentMethod = 'mtn'" :class="paymentMethod === 'mtn' ? 'border-yellow-400 bg-yellow-50 dark:bg-yellow-900' : 'border-gray-200 dark:border-gray-700'" class="flex items-center gap-4 p-4 rounded-xl border-2 cursor-pointer transition hover:border-yellow-300">
                        <div class="w-10 h-10 bg-yellow-400 rounded-xl flex items-center justify-center text-xl">📱</div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-900 dark:text-white">Mobile Money MTN</p>
                            <p class="text-xs text-gray-500">Paiement instantané via MTN MoMo</p>
                        </div>
                        <div :class="paymentMethod === 'mtn' ? 'border-yellow-500 bg-yellow-500' : 'border-gray-300'" class="w-5 h-5 rounded-full border-2 flex items-center justify-center">
                            <div x-show="paymentMethod === 'mtn'" class="w-2.5 h-2.5 bg-white rounded-full"></div>
                        </div>
                    </label>
                    <label @click="paymentMethod = 'orange'" :class="paymentMethod === 'orange' ? 'border-orange-400 bg-orange-50 dark:bg-orange-900' : 'border-gray-200 dark:border-gray-700'" class="flex items-center gap-4 p-4 rounded-xl border-2 cursor-pointer transition hover:border-orange-300">
                        <div class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center text-xl">📱</div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-900 dark:text-white">Mobile Money Orange</p>
                            <p class="text-xs text-gray-500">Paiement via Orange Money</p>
                        </div>
                        <div :class="paymentMethod === 'orange' ? 'border-orange-500 bg-orange-500' : 'border-gray-300'" class="w-5 h-5 rounded-full border-2 flex items-center justify-center">
                            <div x-show="paymentMethod === 'orange'" class="w-2.5 h-2.5 bg-white rounded-full"></div>
                        </div>
                    </label>
                    <label @click="paymentMethod = 'wave'" :class="paymentMethod === 'wave' ? 'border-blue-400 bg-blue-50 dark:bg-blue-900' : 'border-gray-200 dark:border-gray-700'" class="flex items-center gap-4 p-4 rounded-xl border-2 cursor-pointer transition hover:border-blue-300">
                        <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center text-xl">🌊</div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-900 dark:text-white">Wave</p>
                            <p class="text-xs text-gray-500">Paiement via Wave Mobile Money</p>
                        </div>
                        <div :class="paymentMethod === 'wave' ? 'border-blue-500 bg-blue-500' : 'border-gray-300'" class="w-5 h-5 rounded-full border-2 flex items-center justify-center">
                            <div x-show="paymentMethod === 'wave'" class="w-2.5 h-2.5 bg-white rounded-full"></div>
                        </div>
                    </label>
                    <label @click="paymentMethod = 'card'" :class="paymentMethod === 'card' ? 'border-indigo-400 bg-indigo-50 dark:bg-indigo-900' : 'border-gray-200 dark:border-gray-700'" class="flex items-center gap-4 p-4 rounded-xl border-2 cursor-pointer transition hover:border-indigo-300">
                        <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-xl">💳</div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-900 dark:text-white">Carte bancaire</p>
                            <p class="text-xs text-gray-500">Visa, Mastercard</p>
                        </div>
                        <div :class="paymentMethod === 'card' ? 'border-indigo-500 bg-indigo-500' : 'border-gray-300'" class="w-5 h-5 rounded-full border-2 flex items-center justify-center">
                            <div x-show="paymentMethod === 'card'" class="w-2.5 h-2.5 bg-white rounded-full"></div>
                        </div>
                    </label>
                    <label @click="paymentMethod = 'cash'" :class="paymentMethod === 'cash' ? 'border-green-400 bg-green-50 dark:bg-green-900' : 'border-gray-200 dark:border-gray-700'" class="flex items-center gap-4 p-4 rounded-xl border-2 cursor-pointer transition hover:border-green-300">
                        <div class="w-10 h-10 bg-green-600 rounded-xl flex items-center justify-center text-xl">💵</div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-900 dark:text-white">Livraison contre remboursement</p>
                            <p class="text-xs text-gray-500">Payer à la livraison</p>
                        </div>
                        <div :class="paymentMethod === 'cash' ? 'border-green-500 bg-green-500' : 'border-gray-300'" class="w-5 h-5 rounded-full border-2 flex items-center justify-center">
                            <div x-show="paymentMethod === 'cash'" class="w-2.5 h-2.5 bg-white rounded-full"></div>
                        </div>
                    </label>
                </div>
                <div class="flex gap-3 mt-6">
                    <button @click="step = 1" class="flex-1 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 py-3 rounded-xl font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition">← Précédent</button>
                    <button @click="step = 3" :disabled="!paymentMethod" :class="paymentMethod ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-gray-300 cursor-not-allowed'" class="flex-1 text-white py-3 rounded-xl font-semibold transition">Suivant →</button>
                </div>
            </div>

            <!-- Step 3: Confirmation -->
            <div x-show="step === 3" class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm fade-in-up">
                <h2 class="font-semibold text-gray-900 dark:text-white mb-5">✅ Confirmation de commande</h2>
                <div class="space-y-4">
                    <div class="bg-gray-50 dark:bg-gray-750 rounded-xl p-4">
                        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Adresse de livraison</h3>
                        <p class="text-sm text-gray-900 dark:text-white" x-text="address.nom || 'Non renseigné'"></p>
                        <p class="text-sm text-gray-500" x-text="address.adresse + ', ' + address.ville + ', ' + address.pays"></p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-750 rounded-xl p-4">
                        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mode de paiement</h3>
                        <p class="text-sm text-gray-900 dark:text-white" x-text="{ mtn:'Mobile Money MTN', orange:'Mobile Money Orange', wave:'Wave', card:'Carte bancaire', cash:'Livraison contre remboursement' }[paymentMethod] || paymentMethod"></p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-750 rounded-xl p-4">
                        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Articles</h3>
                        <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                            <div class="flex justify-between"><span>Calculatrice scientifique CASIO fx-991 x1</span><span>15 000 FCFA</span></div>
                            <div class="flex justify-between"><span>Dictionnaire Larousse 2025 x2</span><span>17 000 FCFA</span></div>
                            <div class="flex justify-between"><span>Sac à dos scolaire Premium x1</span><span>22 000 FCFA</span></div>
                        </div>
                        <div class="border-t border-gray-200 dark:border-gray-700 mt-2 pt-2 flex justify-between font-bold text-gray-900 dark:text-white">
                            <span>Total</span><span>64 800 FCFA</span>
                        </div>
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button @click="step = 2" class="flex-1 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 py-3 rounded-xl font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition">← Précédent</button>
                    <button @click="complete()" class="flex-1 bg-green-600 hover:bg-green-700 text-white py-3 rounded-xl font-semibold transition flex items-center justify-center gap-2">
                        <svg x-show="loading" class="h-5 w-5 spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        <span x-show="!loading">Confirmer la commande</span>
                        <span x-show="loading">Traitement...</span>
                    </button>
                </div>
            </div>

            <!-- Step 4: Success -->
            <div x-show="step === 4" class="bg-white dark:bg-gray-800 rounded-2xl p-10 shadow-sm text-center fade-in-up">
                <div class="text-6xl mb-4">🎉</div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Commande confirmée!</h2>
                <p class="text-gray-500 dark:text-gray-400 mb-6">Votre commande #LM-1088 a été enregistrée. Vous recevrez une confirmation par SMS.</p>
                <div class="flex gap-3 justify-center">
                    <a href="/shop/orders" class="bg-indigo-600 text-white px-6 py-3 rounded-xl font-medium hover:bg-indigo-700 transition">Voir mes commandes</a>
                    <a href="/shop" class="border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 px-6 py-3 rounded-xl font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition">Continuer les achats</a>
                </div>
            </div>
        </div>

        <!-- Order Summary Sidebar -->
        <div x-show="step < 4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm fade-in-up" style="animation-delay:0.2s">
                <h2 class="font-semibold text-gray-900 dark:text-white mb-4">Votre commande</h2>
                <div class="space-y-3 text-sm">
                    <div class="flex gap-3 items-center">
                        <span class="text-xl">🔢</span>
                        <div class="flex-1 min-w-0">
                            <p class="text-gray-900 dark:text-white text-xs truncate">Calculatrice CASIO fx-991</p>
                            <p class="text-gray-500 text-xs">x1</p>
                        </div>
                        <span class="text-gray-900 dark:text-white font-medium text-xs">15 000 FCFA</span>
                    </div>
                    <div class="flex gap-3 items-center">
                        <span class="text-xl">📚</span>
                        <div class="flex-1 min-w-0">
                            <p class="text-gray-900 dark:text-white text-xs truncate">Dictionnaire Larousse 2025</p>
                            <p class="text-gray-500 text-xs">x2</p>
                        </div>
                        <span class="text-gray-900 dark:text-white font-medium text-xs">17 000 FCFA</span>
                    </div>
                    <div class="flex gap-3 items-center">
                        <span class="text-xl">🎒</span>
                        <div class="flex-1 min-w-0">
                            <p class="text-gray-900 dark:text-white text-xs truncate">Sac à dos scolaire Premium</p>
                            <p class="text-gray-500 text-xs">x1</p>
                        </div>
                        <span class="text-gray-900 dark:text-white font-medium text-xs">22 000 FCFA</span>
                    </div>
                </div>
                <div class="border-t border-gray-100 dark:border-gray-700 mt-4 pt-4 space-y-2 text-sm">
                    <div class="flex justify-between text-gray-600 dark:text-gray-400"><span>Sous-total</span><span>54 000 FCFA</span></div>
                    <div class="flex justify-between text-gray-600 dark:text-gray-400"><span>Livraison</span><span>2 500 FCFA</span></div>
                    <div class="flex justify-between text-gray-600 dark:text-gray-400"><span>TVA (18%)</span><span>9 720 FCFA</span></div>
                    <div class="flex justify-between font-bold text-gray-900 dark:text-white border-t border-gray-100 dark:border-gray-700 pt-2"><span>Total</span><span>66 220 FCFA</span></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
