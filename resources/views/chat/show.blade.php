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

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow border border-rose-100 overflow-hidden">
            {{-- lista --}}
            <div id="chatScroll"
                class="h-[60vh] overflow-y-auto p-4 space-y-3 bg-gradient-to-b from-rose-50/40 to-white">
                @forelse($messages as $m)
                @php
                $isMe = $m->sender_id === $me->id;
                $sender = $m->sender; // veio do with()
                $photo = $sender?->primaryPhoto?->url ?? null;
                $initial = strtoupper(substr($sender?->username ?? '?', 0, 1));
                @endphp

                <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
                    <div class="flex items-end gap-2 {{ $isMe ? 'flex-row-reverse' : '' }} max-w-[85%]">

                        {{-- avatar --}}
                        <div class="shrink-0">
                            @if($photo)
                            <img src="{{ $photo }}" alt="Foto"
                                class="h-9 w-9 rounded-full object-cover border border-rose-200 shadow-sm">
                            @else
                            <div class="h-9 w-9 rounded-full bg-gradient-to-br from-rose-400 to-pink-500
                    flex items-center justify-center text-white font-bold text-xs shadow-sm">
                                {{ $initial }}
                            </div>
                            @endif
                        </div>

                        {{-- balão --}}
                        <div class="max-w-[75%] rounded-2xl px-4 py-2 text-sm shadow
      {{ $isMe
          ? 'bg-white border border-rose-100 text-rose-500'
          : 'bg-rose-500 text-white' }}">
                            <div class="whitespace-pre-wrap">{{ $m->body }}</div>

                            <div class="mt-1 text-[11px] opacity-80 {{ $isMe ? 'text-rose-500' : 'text-white' }}">
                                {{ optional($m->created_at)->format('H:i') }}
                            </div>
                        </div>

                    </div>
                </div>
                @empty
                <div class="text-center text-gray-500 py-10">
                    Ainda não tem mensagens… manda a primeira e faz história 😄
                </div>
                @endforelse
            </div>

            {{-- composer --}}
            <form id="chatForm"
                method="POST"
                action="{{ route('chat.send', $conversation) }}"
                class="p-3 border-t border-rose-100">
                @csrf

                <div class="flex gap-2 items-end">
                    <div class="flex-1">
                        <textarea
                            id="chatBody"
                            name="body"
                            rows="1"
                            maxlength="500"
                            class="w-full resize-none rounded-2xl border border-rose-200 focus:border-rose-400 focus:ring-rose-400 text-sm px-4 py-3"
                            placeholder="Digite sua mensagem… (Enter envia • Shift+Enter quebra linha)"></textarea>

                        <div class="mt-1 flex items-center justify-between text-xs text-gray-400 px-1">
                            <span>Dica: mensagens curtas ficam mais charmosas 😌</span>
                            <span id="chatCount">0/500</span>
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="rounded-full bg-gradient-to-r from-rose-500 to-pink-500 text-white text-sm font-semibold px-5 py-3 shadow hover:opacity-90">
                        Enviar →
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- scripts simples inline (sem JS “maroto”, só UX) --}}
    <script>
        (function() {
            const ta = document.getElementById('chatBody');
            const cnt = document.getElementById('chatCount');
            const form = document.getElementById('chatForm');
            const scroll = document.getElementById('chatScroll');

            // auto-scroll pro fim
            if (scroll) scroll.scrollTop = scroll.scrollHeight;

            function updateCount() {
                const v = (ta.value || '');
                cnt.textContent = `${v.length}/500`;
            }

            function autoResize() {
                ta.style.height = 'auto';
                ta.style.height = Math.min(160, ta.scrollHeight) + 'px'; // até ~6 linhas
            }

            ta.addEventListener('input', function() {
                updateCount();
                autoResize();
            });

            // Enter envia | Shift+Enter quebra linha
            ta.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    // evita enviar vazio
                    if ((ta.value || '').trim().length === 0) return;
                    form.submit();
                }
            });

            // init
            updateCount();
            autoResize();
        })();
    </script>
</x-app-layout>