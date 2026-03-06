@php
// Fallbacks seguros pra quando a partial roda dentro de tabs
$me = $me ?? auth()->user();

/** @var \Illuminate\Support\Collection $photos */
$photos = $photos ?? ($me->photos ?? collect());
$photos = $photos instanceof \Illuminate\Support\Collection ? $photos : collect($photos);

// Pra mostrar no lightbox (opcional)
$photoUrls = $photos->map(fn($p) => $p->url)->values();
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
    @if (session('status'))
    <div class="rounded-2xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-emerald-800">
        {{ session('status') }}
    </div>
    @endif

    @if ($errors->any())
    <div class="rounded-2xl bg-rose-50 border border-rose-200 px-4 py-3 text-rose-800 shadow-sm">
        <div class="font-semibold mb-1">Ops… tem uns detalhes pra ajustar:</div>
        <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $e)
            <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Upload --}}
    <div class="bg-white rounded-2xl shadow p-5 border border-rose-100">
        <div class="flex items-center justify-between mb-3">
            <div>
                <div class="font-semibold text-gray-900">Adicionar foto</div>
                <div class="text-xs text-gray-500">Primeira foto vira perfil automaticamente. Limite 7.</div>
            </div>

            <div class="text-xs text-gray-500">
                <span class="font-semibold">{{ $photos->count() }}</span> / 7
            </div>
        </div>

        <form method="POST"
            action="{{ route('myprofile.photos.store') }}"
            enctype="multipart/form-data"
            class="flex flex-col sm:flex-row gap-3 items-start sm:items-end">
            @csrf

            <div class="w-full sm:w-auto">
                <label class="block text-sm font-medium text-gray-700 mb-1">Arquivo</label>
                <input type="file" name="photo" accept="image/*"
                    class="block w-full text-sm file:mr-4 file:rounded-full file:border-0 file:bg-rose-600 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-rose-700" />
            </div>

            <label class="inline-flex items-center gap-2 text-sm text-gray-700 mt-2 sm:mt-0">
                <input type="checkbox" name="make_primary" value="1"
                    class="rounded border-gray-300 text-rose-600 focus:ring-rose-500">
                Tornar foto de perfil
            </label>

            <label class="inline-flex items-center gap-2 text-sm text-gray-700 mt-2 sm:mt-0">
                <input type="checkbox" name="is_private" value="1"
                    class="rounded border-gray-300 text-rose-600 focus:ring-rose-500">
                Privada (só pra você)
            </label>

            <button type="submit"
                @if($photos->count() >= 7) disabled @endif
                class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-rose-500 to-pink-500 px-5 py-2 text-sm font-semibold text-white shadow hover:opacity-90 transition disabled:opacity-40 disabled:cursor-not-allowed">
                ➕ Enviar
            </button>
        </form>
    </div>

    {{-- Grid --}}
    <div class="bg-white rounded-2xl shadow p-5 border border-rose-100">
        <div class="font-semibold text-gray-900 mb-4">Seu álbum</div>

        @if($photos->isEmpty())
        <div class="text-gray-500">Ainda sem fotos… bora colocar essa carinha aí 😄</div>
        @else
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($photos as $i => $p)
            <div class="group rounded-2xl border border-rose-100 overflow-hidden bg-white relative">
                {{-- imagem (clicável) --}}
                <button type="button"
                    class="block w-full"
                    data-lb-open="{{ $i }}"
                    aria-label="Abrir foto {{ $i + 1 }}">
                    <img src="{{ $p->url }}"
                        class="w-full h-44 object-cover group-hover:opacity-95 transition"
                        alt="Foto">
                </button>

                {{-- badges --}}
                <div class="absolute top-2 left-2 flex gap-2">
                    @if($p->is_primary)
                    <span class="text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800 px-2 py-1">
                        Perfil ⭐
                    </span>
                    @endif
                    @if($p->is_private)
                    <span class="text-xs font-semibold rounded-full bg-gray-100 text-gray-700 px-2 py-1">
                        Privada 🔒
                    </span>
                    @endif
                </div>

                {{-- ações --}}
                <div class="p-3 flex items-center justify-between gap-2">
                    <form method="POST" action="{{ route('myprofile.photos.primary', $p) }}">
                        @csrf
                        @method('PUT')
                        <button type="submit"
                            @if($p->is_primary) disabled @endif
                            class="text-xs font-semibold rounded-full px-3 py-1 border border-rose-200 text-rose-700 hover:bg-rose-50 transition disabled:opacity-40 disabled:cursor-not-allowed">
                            Tornar perfil
                        </button>
                    </form>

                    <form method="POST"
                        action="{{ route('myprofile.photos.destroy', $p) }}"
                        onsubmit="return confirm('Remover esta foto?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="text-xs font-semibold rounded-full px-3 py-1 border border-gray-200 text-gray-700 hover:bg-gray-50 transition">
                            🗑️ Excluir
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

{{-- Lightbox (opcional) - só ativa se houver fotos --}}
@if($photos->count() > 0)
<div id="lightbox"
    class="fixed inset-0 z-[9999] hidden"
    aria-hidden="true">
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
            <img id="lbImg"
                src=""
                alt="Foto em destaque"
                class="max-w-[92vw] max-h-[85vh] rounded-2xl shadow-2xl object-contain bg-black/20" />
            <figcaption id="lbCaption"
                class="mt-3 text-center text-white/80 text-sm"></figcaption>
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

        const PHOTOS = @json($photoUrls);

        if (!Array.isArray(PHOTOS) || PHOTOS.length === 0) return;

        let current = 0;

        function openAt(i) {
            current = (i + PHOTOS.length) % PHOTOS.length;
            img.src = PHOTOS[current];
            cap.textContent = `${current + 1}/${PHOTOS.length}`;

            lb.classList.remove('hidden');
            lb.setAttribute('aria-hidden', 'false');
            document.body.classList.add('overflow-hidden');

            const showArrows = PHOTOS.length > 1;
            btnPrev.classList.toggle('hidden', !showArrows);
            btnNext.classList.toggle('hidden', !showArrows);
        }

        function close() {
            lb.classList.add('hidden');
            lb.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('overflow-hidden');
            img.src = '';
        }

        function prev() {
            openAt(current - 1);
        }

        function next() {
            openAt(current + 1);
        }

        document.querySelectorAll('[data-lb-open]').forEach(el => {
            el.addEventListener('click', () => {
                const i = parseInt(el.getAttribute('data-lb-open') || '0', 10);
                openAt(isNaN(i) ? 0 : i);
            });
        });

        lb.addEventListener('click', (e) => {
            if (e.target.matches('[data-lb-close]')) close();
        });

        btnPrev.addEventListener('click', (e) => {
            e.stopPropagation();
            prev();
        });
        btnNext.addEventListener('click', (e) => {
            e.stopPropagation();
            next();
        });

        window.addEventListener('keydown', (e) => {
            if (lb.classList.contains('hidden')) return;
            if (e.key === 'Escape') close();
            if (e.key === 'ArrowLeft') prev();
            if (e.key === 'ArrowRight') next();
        });
    })();
</script>
@endif