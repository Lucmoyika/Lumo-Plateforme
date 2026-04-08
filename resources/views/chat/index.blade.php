@extends('layouts.app')

@section('title', 'Chat - Lumo Plateforme')

@section('content')
<div x-data="chat()" class="h-[600px] flex bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
    <!-- Conversations sidebar -->
    <div class="w-72 border-r border-gray-200 dark:border-gray-700 flex flex-col">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="font-bold text-gray-900 dark:text-white text-lg">💬 Conversations</h2>
            <input type="text" placeholder="Rechercher..." class="mt-2 w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div class="flex-1 overflow-y-auto">
            @foreach([
                ['name' => 'Kofi Anan', 'last' => 'Bonjour, comment ça va?', 'time' => '10:30', 'unread' => 2],
                ['name' => 'Aminata Diallo', 'last' => 'Merci pour le document', 'time' => '09:15', 'unread' => 0],
                ['name' => 'Jean-Baptiste Kouassi', 'last' => 'La réunion est à 14h', 'time' => 'hier', 'unread' => 1],
                ['name' => 'Fatou Touré', 'last' => 'D\'accord, je vais vérifier', 'time' => 'lun', 'unread' => 0],
                ['name' => 'Equipe Lumo', 'last' => 'Bienvenue sur Lumo!', 'time' => '12/01', 'unread' => 0],
            ] as $i => $conv)
            <div @click="activeConv = {{ $i }}" :class="activeConv === {{ $i }} ? 'bg-indigo-50 dark:bg-indigo-900/30' : ''"
                class="flex items-center gap-3 px-4 py-3 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition border-b border-gray-100 dark:border-gray-700">
                <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                    {{ substr($conv['name'], 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <span class="font-semibold text-gray-900 dark:text-white text-sm truncate">{{ $conv['name'] }}</span>
                        <span class="text-xs text-gray-400 ml-2 flex-shrink-0">{{ $conv['time'] }}</span>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate mt-0.5">{{ $conv['last'] }}</p>
                </div>
                @if($conv['unread'] > 0)
                <span class="bg-indigo-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center flex-shrink-0">{{ $conv['unread'] }}</span>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    <!-- Chat area -->
    <div class="flex-1 flex flex-col">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white text-sm font-bold">K</div>
            <span class="font-semibold text-gray-900 dark:text-white">Kofi Anan</span>
            <span class="w-2 h-2 bg-green-500 rounded-full"></span>
        </div>

        <div class="flex-1 overflow-y-auto p-4 space-y-4">
            <div class="flex items-end gap-2">
                <div class="w-7 h-7 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center text-xs font-bold">K</div>
                <div class="bg-gray-100 dark:bg-gray-700 rounded-xl rounded-bl-none px-4 py-2 max-w-xs">
                    <p class="text-sm text-gray-800 dark:text-gray-200">Bonjour! Comment ça va?</p>
                    <p class="text-xs text-gray-400 mt-1">10:28</p>
                </div>
            </div>
            <div class="flex items-end gap-2 justify-end">
                <div class="bg-indigo-600 rounded-xl rounded-br-none px-4 py-2 max-w-xs">
                    <p class="text-sm text-white">Très bien merci! Et toi?</p>
                    <p class="text-xs text-indigo-200 mt-1">10:29</p>
                </div>
            </div>
            <div class="flex items-end gap-2">
                <div class="w-7 h-7 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center text-xs font-bold">K</div>
                <div class="bg-gray-100 dark:bg-gray-700 rounded-xl rounded-bl-none px-4 py-2 max-w-xs">
                    <p class="text-sm text-gray-800 dark:text-gray-200">Ça va bien! Tu as vu le nouveau module?</p>
                    <p class="text-xs text-gray-400 mt-1">10:30</p>
                </div>
            </div>
        </div>

        <div class="p-4 border-t border-gray-200 dark:border-gray-700 flex gap-3">
            <input x-ref="msg" type="text" placeholder="Écrire un message..."
                class="flex-1 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none"
                @keydown.enter="sendMsg()">
            <button @click="sendMsg()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl transition text-sm">Envoyer</button>
        </div>
    </div>
</div>

<script>
function chat() {
    return {
        activeConv: 0,
        sendMsg() {
            this.$refs.msg.value = '';
        }
    }
}
</script>
@endsection
