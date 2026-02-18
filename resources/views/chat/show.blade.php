<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-xs text-gray-500">Conversando com</div>
                <div class="font-bold text-xl text-rose-600">{{ $other->username }}</div>
            </div>

            <a href="{{ route('home') }}"
                class="rounded-full bg-white/70 px-3 py-1 text-xs font-semibold border border-rose-100 hover:border-rose-200">
                ← Voltar
            </a>
        </div>
    </x-slot>

    <?php /*
    {{-- debug rápido --}}
    <div class="text-xs text-gray-400">
        msgs: {{ $messages->count() ?? 'sem $messages' }}
    </div>
    */ ?>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow border border-rose-100 overflow-hidden">
            {{-- lista --}}
            <div class="h-[60vh] overflow-y-auto p-4 space-y-3 bg-gradient-to-b from-rose-50/40 to-white">
                @forelse($messages as $m)
                @php $isMe = $m->sender_id === $me->id; @endphp

                <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[75%] rounded-2xl px-4 py-2 text-sm shadow
                            {{ $isMe ? 'bg-rose-500 text-white' : 'bg-white border border-rose-100 text-gray-800' }}">
                        {{ $m->body }}
                    </div>
                </div>
                @empty
                <div class="text-center text-gray-500 py-10">
                    Ainda não tem mensagens… manda a primeira e faz história 😄
                </div>
                @endforelse
            </div>

            {{-- composer --}}
            <form method="POST" action="{{ route('chat.send', $conversation) }}" class="p-3 border-t border-rose-100 flex gap-2">
                @csrf
                <input name="body"
                    class="flex-1 rounded-full border border-rose-200 focus:border-rose-400 focus:ring-rose-400 text-sm px-4 py-2"
                    placeholder="Digite sua mensagem…" />

                <button class="rounded-full bg-gradient-to-r from-rose-500 to-pink-500 text-white text-sm font-semibold px-5 py-2 shadow hover:opacity-90">
                    Enviar →
                </button>
            </form>
        </div>
    </div>
</x-app-layout>