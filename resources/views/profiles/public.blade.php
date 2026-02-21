<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}"
                    class="inline-flex items-center gap-2 rounded-full bg-white/70 px-3 py-2 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-gray-200 hover:bg-white">
                    ← Voltar
                </a>

                <div>
                    <h2 class="text-xl sm:text-2xl font-extrabold text-rose-600 leading-tight">
                        {{ $u->username }}
                    </h2>
                    <div class="text-xs sm:text-sm text-gray-500">
                        {{ $u->city }} - {{ $u->state }}
                        <span class="mx-2">•</span>
                        <span class="font-semibold">{{ $u->sex }} → {{ $u->seeking }}</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2">
                @if($u->is_tester)
                <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700">
                    Tester ⭐
                </span>
                @endif

                <span class="inline-flex items-center rounded-full bg-rose-100 px-3 py-1 text-xs font-bold text-rose-700">
                    VipRomance 💘
                </span>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-rose-50 via-pink-50 to-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

            {{-- Topo: Fotos + CTA --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Galeria --}}
                <div class="lg:col-span-2 bg-white rounded-2xl shadow p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-extrabold text-gray-900">Fotos 📸</h3>
                        <span class="text-xs text-gray-500">
                            {{ $u->photos?->count() ?? 0 }}/7
                        </span>
                    </div>

                    @php
                        $photos = $u->photos ?? collect();
                        // gera array de URLs (pra usar tanto no HTML quanto no JS)
                        $photoUrls = $photos->map(fn($p) => asset('storage/'.$p->path))->values();
                        $mainUrl = $photoUrls->first();
                    @endphp

                    {{-- Foto principal (clicável) --}}
                    <div class="rounded-2xl overflow-hidden ring-1 ring-rose-100 bg-gradient-to-br from-rose-100 to-pink-100">
                        @if($mainUrl)
                            <button type="button"
                                class="w-full block focus:outline-none"
                                data-lb
                                data-index="0"
                                data-src="{{ $mainUrl }}"
                                aria-label="Abrir foto principal de {{ $u->username }}">
                                <img src="{{ $mainUrl }}"
                                    alt="Foto de {{ $u->username }}"
                                    class="w-full h-[280px] sm:h-[360px] object-cover hover:opacity-95 transition">
                            </button>
                        @else
                            <div class="w-full h-[280px] sm:h-[360px] flex items-center justify-center">
                                <div class="text-center">
                                    <div class="mx-auto h-16 w-16 rounded-full bg-gradient-to-br from-rose-400 to-pink-500 flex items-center justify-center text-white font-extrabold text-2xl">
                                        {{ strtoupper(substr($u->username, 0, 1)) }}
                                    </div>
                                    <div class="mt-3 text-sm text-gray-700 font-semibold">
                                        Ainda sem fotos 😅
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        (mas já já a gente liga o upload premium)
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Thumbs (clicáveis) --}}
                    @if($photoUrls->count() > 1)
                        <div class="mt-4 grid grid-cols-5 gap-2">
                            @foreach($photoUrls->skip(1)->take(4) as $i => $url)
                                <button type="button"
                                    class="aspect-square rounded-xl overflow-hidden ring-1 ring-rose-100 bg-rose-50 hover:ring-rose-200 focus:outline-none"
                                    data-lb
                                    data-index="{{ $i }}"
                                    data-src="{{ $url }}"
                                    aria-label="Abrir foto {{ $i + 1 }} de {{ $u->username }}">
                                    <img src="{{ $url }}"
                                        alt="Foto {{ $i + 1 }}"
                                        class="w-full h-full object-cover hover:opacity-95 transition">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Card lateral --}}
                <div class="bg-white rounded-2xl shadow p-5">
                    <h3 class="font-extrabold text-gray-900 mb-3">Sobre ✨</h3>

                    <div class="text-sm text-gray-700 leading-relaxed">
                        {{ $u->bio ?: 'Sem bio ainda… mas eu aposto que essa pessoa é interessante 😄' }}
                    </div>

                    <div class="mt-4 flex flex-wrap gap-2 text-xs">
                        @if($u->hair_color)
                        <span class="rounded-full bg-rose-50 text-rose-700 px-2 py-1 font-semibold">{{ $u->hair_color }}</span>
                        @endif
                        @if($u->eye_color)
                        <span class="rounded-full bg-rose-50 text-rose-700 px-2 py-1 font-semibold">{{ $u->eye_color }}</span>
                        @endif
                        @if($u->height_cm)
                        <span class="rounded-full bg-gray-100 px-2 py-1 font-semibold">{{ $u->height_cm }}cm</span>
                        @endif
                        @if($u->weight_kg)
                        <span class="rounded-full bg-gray-100 px-2 py-1 font-semibold">{{ $u->weight_kg }}kg</span>
                        @endif
                        @if($u->body_type)
                        <span class="rounded-full bg-gray-100 px-2 py-1 font-semibold">{{ $u->body_type }}</span>
                        @endif
                    </div>

                    {{-- CTA --}}
                    <div class="mt-6">
                        @if($me->id === $u->id)
                        <a href="{{ route('profile.edit') }}"
                            class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-gray-900 px-4 py-3 text-sm font-extrabold text-white shadow hover:opacity-90">
                            ✍️ Editar meu perfil
                        </a>
                        @else
                        <a href="{{ route('chat.withUser', $u->username) }}"
                            class="mt-4 inline-flex items-center gap-2 text-sm font-bold text-white
                                   bg-gradient-to-r from-rose-500 to-pink-500
                                   px-3 py-1.5 rounded-full shadow hover:opacity-95">
                            💬 Conversar
                        </a>

                        <div class="mt-3 text-xs text-gray-500">
                            Dica: conversa curta, simpática e sem textão… (por enquanto 😄)
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Seção “Características” --}}
            <div class="bg-white rounded-2xl shadow p-5">
                <div class="flex items-center justify-between">
                    <h3 class="font-extrabold text-gray-900">Características 📌</h3>
                    <span class="text-xs text-gray-500">sem frescura, só o essencial</span>
                </div>

                <div class="mt-4 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 text-sm">
                    <div class="rounded-xl bg-rose-50 p-3">
                        <div class="text-xs text-gray-500">Cabelos</div>
                        <div class="font-extrabold text-rose-700">{{ $u->hair_color ?: '—' }}</div>
                    </div>
                    <div class="rounded-xl bg-rose-50 p-3">
                        <div class="text-xs text-gray-500">Olhos</div>
                        <div class="font-extrabold text-rose-700">{{ $u->eye_color ?: '—' }}</div>
                    </div>
                    <div class="rounded-xl bg-gray-100 p-3">
                        <div class="text-xs text-gray-500">Altura</div>
                        <div class="font-extrabold text-gray-800">{{ $u->height_cm ? $u->height_cm.'cm' : '—' }}</div>
                    </div>
                    <div class="rounded-xl bg-gray-100 p-3">
                        <div class="text-xs text-gray-500">Peso</div>
                        <div class="font-extrabold text-gray-800">{{ $u->weight_kg ? $u->weight_kg.'kg' : '—' }}</div>
                    </div>
                    <div class="rounded-xl bg-gray-100 p-3">
                        <div class="text-xs text-gray-500">Biotipo</div>
                        <div class="font-extrabold text-gray-800">{{ $u->body_type ?: '—' }}</div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- LIGHTBOX --}}
    <div id="lightbox" class="fixed inset-0 z-[9999] hidden" aria-hidden="true">
        <div data-lb-close class="absolute inset-0 bg-black/70"></div>

        <div class="relative h-full w-full flex items-center justify-center p-4 sm:p-6">
            <button type="button" data-lb-close
                class="absolute top-4 right-4 rounded-full bg-white/10 hover:bg-white/20 text-white px-3 py-2 text-sm">
                ✕
            </button>

            <button type="button" id="lbPrev"
                class="absolute left-3 sm:left-6 top-1/2 -translate-y-1/2
                       rounded-full bg-white/10 hover:bg-white/20 text-white
                       w-10 h-10 flex items-center justify-center text-xl select-none"
                aria-label="Foto anterior">
                ‹
            </button>

            <figure class="relative max-w-[92vw] max-h-[85vh]">
                <img id="lbImg" src="" alt="Foto em destaque"
                    class="max-w-[92vw] max-h-[85vh] rounded-2xl shadow-2xl object-contain bg-black/20" />
                <figcaption id="lbCaption" class="mt-3 text-center text-white/80 text-sm"></figcaption>
            </figure>

            <button type="button" id="lbNext"
                class="absolute right-3 sm:right-6 top-1/2 -translate-y-1/2
                       rounded-full bg-white/10 hover:bg-white/20 text-white
                       w-10 h-10 flex items-center justify-center text-xl select-none"
                aria-label="Próxima foto">
                ›
            </button>
        </div>
    </div>

    <script>
        (function() {
            const lb = document.getElementById('lightbox');
            if (!lb) return;

            const img = document.getElementById('lbImg');
            const cap = document.getElementById('lbCaption');
            const btnPrev = document.getElementById('lbPrev');
            const btnNext = document.getElementById('lbNext');

            const items = Array.from(document.querySelectorAll('[data-lb]'))
                .map(el => ({
                    el,
                    src: el.getAttribute('data-src'),
                    index: parseInt(el.getAttribute('data-index') || '0', 10)
                }))
                .filter(x => !!x.src)
                .sort((a, b) => a.index - b.index);

            if (!items.length) return;

            let current = 0;

            function openAt(i) {
                current = (i + items.length) % items.length;

                img.src = items[current].src;
                cap.textContent = `${current + 1}/${items.length}`;

                lb.classList.remove('hidden');
                lb.setAttribute('aria-hidden', 'false');
                document.body.classList.add('overflow-hidden');

                const showArrows = items.length > 1;
                btnPrev.classList.toggle('hidden', !showArrows);
                btnNext.classList.toggle('hidden', !showArrows);
            }

            function close() {
                lb.classList.add('hidden');
                lb.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('overflow-hidden');
                img.src = '';
            }

            function prev() { openAt(current - 1); }
            function next() { openAt(current + 1); }

            items.forEach((it, idx) => {
                it.el.addEventListener('click', () => openAt(idx));
            });

            lb.addEventListener('click', (e) => {
                if (e.target.matches('[data-lb-close]')) close();
            });

            btnPrev.addEventListener('click', (e) => { e.stopPropagation(); prev(); });
            btnNext.addEventListener('click', (e) => { e.stopPropagation(); next(); });

            window.addEventListener('keydown', (e) => {
                if (lb.classList.contains('hidden')) return;
                if (e.key === 'Escape') close();
                if (e.key === 'ArrowLeft') prev();
                if (e.key === 'ArrowRight') next();
            });
        })();
    </script>
</x-app-layout>