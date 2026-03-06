<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-rose-600 leading-tight flex items-center gap-2">
                Meu Perfil 💘
            </h2>

            <div class="text-sm text-gray-500 flex items-center gap-2">
                <span class="inline-flex items-center rounded-full bg-white/70 px-3 py-1 text-xs font-semibold text-gray-700 border border-rose-100">
                    {{ $me->sex }} → {{ $me->seeking }}
                </span>

                @if(!empty($me->is_tester))
                <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                    Tester ⭐
                </span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-rose-50 via-pink-50 to-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

            @if (session('status'))
            <div class="rounded-2xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-emerald-800 shadow-sm">
                ✅ Perfil atualizado com sucesso!
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

            {{-- Tabs --}}
            <div class="bg-white rounded-2xl shadow p-5">
                <div class="flex flex-wrap gap-2 border-b border-gray-100 pb-3">
                    <button type="button" class="tab-btn px-4 py-2 rounded-full text-sm font-bold bg-rose-500 text-white" data-tab="perfil">
                        Meu Perfil
                    </button>
                    <button type="button" class="tab-btn px-4 py-2 rounded-full text-sm font-bold bg-gray-100 text-gray-700 hover:bg-rose-500" data-tab="fotos">
                        Minhas fotos
                    </button>
                    <button type="button" class="tab-btn px-4 py-2 rounded-full text-sm font-bold bg-gray-100 text-gray-700 hover:bg-rose-500" data-tab="prefs">
                        Preferências/Características
                    </button>
                </div>

                <div class="mt-5">
                    {{-- Card: editar perfil --}}

                    <section class="tab-panel" data-panel="perfil">
                        {{-- ✅ aqui você cola o conteúdo já pronto do “Meu Perfil” --}}
                        @include('myprofile.partials.meu-perfil')
                    </section>

                    <section class="tab-panel hidden" data-panel="fotos">
                        {{-- ✅ aqui você cola o conteúdo já pronto de “Minhas fotos” --}}
                        @include('myprofile.partials.minhas-fotos')
                    </section>

                </div>
            </div>

        </div>
    </div>

    <div class="text-center text-xs text-gray-400">
        VipRomance • MVP raiz 😎
    </div>
    <script>
        // Monta a estrutura de TABs
        (function() {
            const btns = document.querySelectorAll('.tab-btn');
            const panels = document.querySelectorAll('.tab-panel');

            function setTab(key) {
                panels.forEach(p => p.classList.toggle('hidden', p.dataset.panel !== key));
                btns.forEach(b => {
                    const active = b.dataset.tab === key;
                    b.classList.toggle('bg-rose-500', active);
                    b.classList.toggle('text-white', active);
                    b.classList.toggle('bg-gray-100', !active);
                    b.classList.toggle('text-gray-700', !active);
                });
            }

            btns.forEach(b => b.addEventListener('click', () => setTab(b.dataset.tab)));
        })();

        // retorna na tab selecionada
        (function() {
            const btns = document.querySelectorAll('.tab-btn');
            const panels = document.querySelectorAll('.tab-panel');
            const initialTab = @json(session('activeTab', 'perfil'));

            function setTab(key) {
                panels.forEach(p => p.classList.toggle('hidden', p.dataset.panel !== key));
                btns.forEach(b => {
                    const active = b.dataset.tab === key;
                    b.classList.toggle('bg-rose-500', active);
                    b.classList.toggle('text-white', active);
                    b.classList.toggle('bg-gray-100', !active);
                    b.classList.toggle('text-gray-700', !active);
                });
            }

            btns.forEach(b => b.addEventListener('click', () => setTab(b.dataset.tab)));

            setTab(initialTab);
        })();
    </script>

</x-app-layout>