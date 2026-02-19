<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-rose-600 leading-tight">
                Minhas conversas 💬
            </h2>

            <a href="{{ route('home') }}"
                class="rounded-full bg-white/70 px-3 py-1 text-xs font-semibold border border-rose-100 hover:border-rose-200">
                ← Voltar
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-white rounded-2xl shadow border border-rose-100 overflow-hidden">

            @forelse($conversations as $c)
            @php $u = $c->other; @endphp

            <a href="{{ route('chat.show', $c) }}"
                class="flex items-center gap-3 p-4 border-b border-rose-50 hover:bg-rose-50/40 transition">

                {{-- avatar --}}
                @if($u?->primaryPhoto)
                <img src="{{ $u->primaryPhoto->url }}"
                    class="h-12 w-12 rounded-full object-cover border border-rose-200"
                    alt="Foto de {{ $u->username }}">
                @else
                <div class="h-12 w-12 rounded-full bg-gradient-to-br from-rose-400 to-pink-500
                                    flex items-center justify-center text-white font-bold">
                    {{ strtoupper(substr($u->username ?? 'U', 0, 1)) }}
                </div>
                @endif

                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-3">
                        <div class="font-semibold text-gray-900 truncate">
                            {{ $u->username }}
                        </div>

                        <div class="text-xs text-gray-400">
                            {{ optional($c->last_message_at)->format('H:i') }}
                        </div>
                    </div>

                    <div class="text-sm text-gray-500 truncate">
                        {{ $c->lastMessage?->body ?? 'Sem mensagens ainda… manda um “oi” 😄' }}
                    </div>
                </div>

                <div class="text-rose-400 text-sm font-bold">→</div>
            </a>
            @empty
            <div class="p-8 text-center text-gray-500">
                Você ainda não tem conversas abertas… bora puxar papo 😄
            </div>
            @endforelse
        </div>
    </div>
</x-app-layout>