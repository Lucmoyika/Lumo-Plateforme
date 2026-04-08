@extends('layouts.app')

@section('title', 'Journaux d\'audit - Lumo Plateforme')

@section('content')
<div x-data="{ search: '' }">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">📋 Journaux d'audit</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Suivi des activités et actions utilisateurs</p>
        </div>
        <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-xl font-medium transition">
            ⬇️ Exporter CSV
        </button>
    </div>

    <!-- Filters -->
    <div class="flex flex-col sm:flex-row gap-4 mb-6 bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-100 dark:border-gray-700 shadow">
        <input x-model="search" type="text" placeholder="Rechercher une action, un utilisateur..."
            class="flex-1 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
        <select class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
            <option>Toutes les actions</option>
            <option>Connexion</option>
            <option>Création</option>
            <option>Modification</option>
            <option>Suppression</option>
        </select>
        <input type="date" class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
    </div>

    <!-- Audit Log Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700 text-left">
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date/Heure</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Utilisateur</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Action</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Module</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">IP</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach([
                        ['date' => '2025-01-15 10:30:25', 'user' => 'admin@lumo.app', 'action' => 'LOGIN', 'module' => 'Auth', 'ip' => '192.168.1.1', 'status' => 'success'],
                        ['date' => '2025-01-15 10:35:12', 'user' => 'admin@lumo.app', 'action' => 'CREATE', 'module' => 'Schools', 'ip' => '192.168.1.1', 'status' => 'success'],
                        ['date' => '2025-01-15 11:02:45', 'user' => 'teacher@lumo.app', 'action' => 'LOGIN', 'module' => 'Auth', 'ip' => '10.0.0.5', 'status' => 'success'],
                        ['date' => '2025-01-15 11:15:33', 'user' => 'teacher@lumo.app', 'action' => 'UPDATE', 'module' => 'Grades', 'ip' => '10.0.0.5', 'status' => 'success'],
                        ['date' => '2025-01-15 11:40:10', 'user' => 'unknown', 'action' => 'LOGIN', 'module' => 'Auth', 'ip' => '45.33.22.10', 'status' => 'failed'],
                        ['date' => '2025-01-15 12:05:08', 'user' => 'student@lumo.app', 'action' => 'VIEW', 'module' => 'Videos', 'ip' => '172.16.0.3', 'status' => 'success'],
                        ['date' => '2025-01-15 12:30:55', 'user' => 'admin@lumo.app', 'action' => 'DELETE', 'module' => 'Products', 'ip' => '192.168.1.1', 'status' => 'success'],
                    ] as $log)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400 whitespace-nowrap">{{ $log['date'] }}</td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $log['user'] }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-bold px-2 py-1 rounded
                                @if($log['action'] === 'LOGIN') bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300
                                @elseif($log['action'] === 'CREATE') bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300
                                @elseif($log['action'] === 'UPDATE') bg-yellow-100 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-300
                                @elseif($log['action'] === 'DELETE') bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300
                                @else bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300
                                @endif">
                                {{ $log['action'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $log['module'] }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400 font-mono">{{ $log['ip'] }}</td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-medium px-2 py-1 rounded {{ $log['status'] === 'success' ? 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300' : 'bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300' }}">
                                {{ $log['status'] === 'success' ? '✓ Succès' : '✗ Échec' }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <p class="text-sm text-gray-500 dark:text-gray-400">Affichage de 7 sur 1 248 entrées</p>
            <div class="flex gap-2">
                <button class="px-3 py-1 rounded border border-gray-300 dark:border-gray-600 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">← Préc.</button>
                <button class="px-3 py-1 rounded bg-indigo-600 text-white text-sm">1</button>
                <button class="px-3 py-1 rounded border border-gray-300 dark:border-gray-600 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">2</button>
                <button class="px-3 py-1 rounded border border-gray-300 dark:border-gray-600 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">Suiv. →</button>
            </div>
        </div>
    </div>
</div>
@endsection
