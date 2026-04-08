@extends('layouts.guest')

@section('title', 'Inscription - Lumo Plateforme')

@section('content')
<h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 text-center">Créer un compte</h2>

<div x-data="{ loading: false, error: '' }">
    <form @submit.prevent="
        loading = true; error = '';
        fetch('/api/auth/register', {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content},
            body: JSON.stringify({name: $refs.name.value, email: $refs.email.value, password: $refs.password.value, password_confirmation: $refs.password_confirmation.value})
        }).then(r => r.json()).then(d => {
            if(d.success) { localStorage.setItem('token', d.data.token); window.location='/dashboard'; }
            else { error = d.message || 'Erreur lors de l\'inscription'; loading = false; }
        }).catch(() => { error = 'Erreur réseau'; loading = false; });
    " class="space-y-4">
        <div x-show="error" class="bg-red-100 text-red-700 px-4 py-2 rounded-lg text-sm" x-text="error"></div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nom complet</label>
            <input x-ref="name" type="text" required placeholder="Votre nom"
                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Adresse email</label>
            <input x-ref="email" type="email" required placeholder="vous@exemple.com"
                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mot de passe</label>
            <input x-ref="password" type="password" required placeholder="••••••••"
                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirmer le mot de passe</label>
            <input x-ref="password_confirmation" type="password" required placeholder="••••••••"
                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
        </div>

        <button type="submit" :disabled="loading"
            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-xl transition disabled:opacity-50">
            <span x-show="!loading">Créer mon compte</span>
            <span x-show="loading">Inscription en cours...</span>
        </button>
    </form>
</div>
@endsection

@section('footer-link')
<p class="text-white text-sm">Déjà un compte ?
    <a href="/login" class="font-semibold underline">Se connecter</a>
</p>
@endsection
