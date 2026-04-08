@extends('layouts.guest')

@section('title', 'Connexion - Lumo Plateforme')

@section('content')
<h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 text-center">Connexion</h2>

<div x-data="{ loading: false, error: '' }">
    <form @submit.prevent="
        loading = true; error = '';
        fetch('/api/auth/login', {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content},
            body: JSON.stringify({email: $refs.email.value, password: $refs.password.value})
        }).then(r => r.json()).then(d => {
            if(d.success) { localStorage.setItem('token', d.data.token); window.location='/dashboard'; }
            else { error = d.message || 'Identifiants invalides'; loading = false; }
        }).catch(() => { error = 'Erreur réseau'; loading = false; });
    " class="space-y-4">
        <div x-show="error" class="bg-red-100 text-red-700 px-4 py-2 rounded-lg text-sm" x-text="error"></div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Adresse email</label>
            <input x-ref="email" type="email" required placeholder="vous@exemple.com"
                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mot de passe</label>
            <input x-ref="password" type="password" required placeholder="••••••••"
                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none">
        </div>

        <div class="flex items-center justify-between text-sm">
            <a href="/forgot-password" class="text-indigo-600 hover:underline">Mot de passe oublié ?</a>
        </div>

        <button type="submit" :disabled="loading"
            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-xl transition disabled:opacity-50">
            <span x-show="!loading">Se connecter</span>
            <span x-show="loading">Connexion en cours...</span>
        </button>
    </form>
</div>
@endsection

@section('footer-link')
<p class="text-white text-sm">Pas encore de compte ?
    <a href="/register" class="font-semibold underline">Créer un compte</a>
</p>
@endsection
