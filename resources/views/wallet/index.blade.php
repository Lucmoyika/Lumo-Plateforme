@extends('layouts.app')

@section('title', 'Wallet - Lumo Plateforme')

@section('content')
<div x-data="{ tab: 'overview', showDeposit: false, amount: '' }">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">💳 Mon Wallet</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Gérez vos finances en toute sécurité</p>
        </div>
    </div>

    <!-- Balance Card -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-8 text-white mb-8">
        <p class="text-indigo-200 text-sm mb-2">Solde disponible</p>
        <p class="text-5xl font-extrabold">125 000 <span class="text-2xl font-normal">FCFA</span></p>
        <div class="mt-6 flex flex-wrap gap-3">
            <button @click="showDeposit = true" class="bg-white text-indigo-600 font-semibold px-5 py-2 rounded-xl text-sm hover:bg-indigo-50 transition">
                ⬆️ Déposer
            </button>
            <button class="bg-indigo-700 hover:bg-indigo-800 text-white font-semibold px-5 py-2 rounded-xl text-sm transition border border-indigo-500">
                ⬇️ Retirer
            </button>
            <button class="bg-indigo-700 hover:bg-indigo-800 text-white font-semibold px-5 py-2 rounded-xl text-sm transition border border-indigo-500">
                ↗️ Transférer
            </button>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5 border border-gray-100 dark:border-gray-700">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total reçu</p>
            <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">+350 000 FCFA</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5 border border-gray-100 dark:border-gray-700">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total dépensé</p>
            <p class="text-2xl font-bold text-red-500 dark:text-red-400 mt-1">-225 000 FCFA</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5 border border-gray-100 dark:border-gray-700">
            <p class="text-sm text-gray-500 dark:text-gray-400">Transactions</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">48</p>
        </div>
    </div>

    <!-- Transactions -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-100 dark:border-gray-700">
        <div class="p-6 border-b border-gray-100 dark:border-gray-700">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">Historique des transactions</h2>
        </div>
        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            @foreach([
                ['label' => 'Dépôt Mobile Money', 'amount' => '+50 000', 'type' => 'credit', 'date' => '15 Jan 2025', 'icon' => '📱'],
                ['label' => 'Achat - Manuel scolaire', 'amount' => '-3 500', 'type' => 'debit', 'date' => '14 Jan 2025', 'icon' => '🛒'],
                ['label' => 'Transfert reçu de Aminata', 'amount' => '+25 000', 'type' => 'credit', 'date' => '12 Jan 2025', 'icon' => '↙️'],
                ['label' => 'Paiement frais scolaires', 'amount' => '-75 000', 'type' => 'debit', 'date' => '10 Jan 2025', 'icon' => '🏫'],
                ['label' => 'Dépôt virement bancaire', 'amount' => '+100 000', 'type' => 'credit', 'date' => '08 Jan 2025', 'icon' => '🏦'],
            ] as $tx)
            <div class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-xl flex items-center justify-center text-xl">{{ $tx['icon'] }}</div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white text-sm">{{ $tx['label'] }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $tx['date'] }}</p>
                    </div>
                </div>
                <span class="font-bold {{ $tx['type'] === 'credit' ? 'text-green-600 dark:text-green-400' : 'text-red-500 dark:text-red-400' }}">
                    {{ $tx['amount'] }} FCFA
                </span>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Deposit Modal -->
    <div x-show="showDeposit" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" @click.self="showDeposit = false">
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 w-full max-w-md mx-4">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Déposer des fonds</h3>
            <input x-model="amount" type="number" placeholder="Montant en FCFA"
                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-3 mb-4 focus:ring-2 focus:ring-indigo-500 outline-none">
            <div class="flex gap-3">
                <button @click="showDeposit = false" class="flex-1 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 py-2 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition">Annuler</button>
                <button class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-xl transition">Confirmer</button>
            </div>
        </div>
    </div>
</div>
@endsection
