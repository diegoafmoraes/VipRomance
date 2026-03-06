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

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

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

                <section class="tab-panel hidden" data-panel="prefs">
                    {{-- ✅ aqui você cola o conteúdo já pronto de “Preferências” --}}
                    @include('myprofile.partials.preferencias')
                </section>

            </div>
        </div>

    </div>
    </div>

    <script>
        (function() {
            const btns = document.querySelectorAll('.tab-btn');
            const panels = document.querySelectorAll('.tab-panel');
            const initialTab = @json(session('activeTab', 'perfil'));

            function setTab(key) {
                panels.forEach(panel => {
                    panel.classList.toggle('hidden', panel.dataset.panel !== key);
                });

                btns.forEach(button => {
                    const active = button.dataset.tab === key;

                    button.classList.toggle('bg-rose-500', active);
                    button.classList.toggle('text-white', active);
                    button.classList.toggle('bg-gray-100', !active);
                    button.classList.toggle('text-gray-700', !active);
                });
            }

            btns.forEach(button => {
                button.addEventListener('click', () => {
                    setTab(button.dataset.tab);
                });
            });

            setTab(initialTab);
        })();
    </script>

</x-app-layout>