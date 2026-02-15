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

            {{-- Card: dados travados --}}
            <div class="bg-white rounded-2xl shadow p-6 border border-rose-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-gray-800">Dados de cadastro (travados) 🔒</h3>
                    <span class="text-xs text-gray-500">por enquanto não editável</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sexo</label>
                        <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-gray-700">
                            {{ $me->sex }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Buscando</label>
                        <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-gray-700">
                            {{ $me->seeking }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card: editar perfil --}}
            <form method="POST" action="{{ route('myprofile.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="bg-white rounded-2xl shadow p-6 border border-rose-100">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold text-gray-800">Sobre você ✨</h3>
                        <span class="text-xs text-gray-500">sem burocracia, só o essencial</span>
                    </div>

                    {{-- Bio --}}
                    <div>
                        <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">
                            Bio (até 500 caracteres)
                        </label>
                        <textarea
                            id="bio"
                            name="bio"
                            maxlength="500"
                            rows="4"
                            class="w-full rounded-xl border border-gray-200 focus:border-rose-300 focus:ring-rose-200 shadow-sm"
                            placeholder="Fala um pouquinho de você…"
                        >{{ old('bio', $me->bio) }}</textarea>

                        <div class="mt-1 flex items-center justify-between text-xs text-gray-500">
                            <span>Dica: 2–3 linhas já ficam top 😄</span>
                            <span><span id="bioCount">0</span>/500</span>
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                        {{-- Altura --}}
                        <div>
                            <label for="height_cm" class="block text-sm font-medium text-gray-700 mb-1">Altura</label>
                            <select id="height_cm" name="height_cm"
                                    class="w-full rounded-xl border border-gray-200 focus:border-rose-300 focus:ring-rose-200 shadow-sm">
                                <option value="">— selecione —</option>
                                @for($h = 140; $h <= 220; $h++)
                                    <option value="{{ $h }}" @selected(old('height_cm', $me->height_cm) == $h)>
                                        {{ $h }} cm
                                    </option>
                                @endfor
                            </select>
                        </div>

                        {{-- Peso --}}
                        <div>
                            <label for="weight_kg" class="block text-sm font-medium text-gray-700 mb-1">Peso</label>
                            <select id="weight_kg" name="weight_kg"
                                    class="w-full rounded-xl border border-gray-200 focus:border-rose-300 focus:ring-rose-200 shadow-sm">
                                <option value="">— selecione —</option>
                                @for($w = 40; $w <= 200; $w++)
                                    <option value="{{ $w }}" @selected(old('weight_kg', $me->weight_kg) == $w)>
                                        {{ $w }} kg
                                    </option>
                                @endfor
                            </select>
                        </div>

                        {{-- Cabelo --}}
                        <div>
                            <label for="hair_color" class="block text-sm font-medium text-gray-700 mb-1">Cor de cabelo</label>
                            @php
                                $hair = ['LOIRO','CASTANHO','PRETO','RUIVO','GRISALHO','CARECA'];
                                $hairOld = old('hair_color', $me->hair_color);
                            @endphp
                            <select id="hair_color" name="hair_color"
                                    class="w-full rounded-xl border border-gray-200 focus:border-rose-300 focus:ring-rose-200 shadow-sm">
                                <option value="">— selecione —</option>
                                @foreach($hair as $v)
                                    <option value="{{ $v }}" @selected($hairOld === $v)>{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Olhos --}}
                        <div>
                            <label for="eye_color" class="block text-sm font-medium text-gray-700 mb-1">Cor dos olhos</label>
                            @php
                                $eyes = ['AZUL','CASTANHO','CINZA','MEL','VERDE'];
                                $eyesOld = old('eye_color', $me->eye_color);
                            @endphp
                            <select id="eye_color" name="eye_color"
                                    class="w-full rounded-xl border border-gray-200 focus:border-rose-300 focus:ring-rose-200 shadow-sm">
                                <option value="">— selecione —</option>
                                @foreach($eyes as $v)
                                    <option value="{{ $v }}" @selected($eyesOld === $v)>{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Body type --}}
                        <div>
                            <label for="body_type" class="block text-sm font-medium text-gray-700 mb-1">Biotipo</label>
                            @php
                                $body = ['MAGRO','NORMAL','FOFINHO','SARADO','MUSCULOSO','ELEGANTE','SENSUAL'];
                                $bodyOld = old('body_type', $me->body_type);
                            @endphp
                            <select id="body_type" name="body_type"
                                    class="w-full rounded-xl border border-gray-200 focus:border-rose-300 focus:ring-rose-200 shadow-sm">
                                <option value="">— selecione —</option>
                                @foreach($body as $v)
                                    <option value="{{ $v }}" @selected($bodyOld === $v)>{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Cidade/Estado (se você quiser manter editável) --}}
                        <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-1">Cidade</label>
                                <input
                                    id="city" name="city" type="text"
                                    value="{{ old('city', $me->city) }}"
                                    class="w-full rounded-xl border border-gray-200 focus:border-rose-300 focus:ring-rose-200 shadow-sm"
                                    placeholder="Ex: Caxias do Sul"
                                />
                            </div>
                            <div>
                                <label for="state" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                                <input
                                    id="state" name="state" type="text"
                                    maxlength="2"
                                    value="{{ old('state', $me->state) }}"
                                    class="w-full rounded-xl border border-gray-200 focus:border-rose-300 focus:ring-rose-200 shadow-sm uppercase"
                                    placeholder="Ex: RS"
                                />
                            </div>
                        </div>

                    </div>

                    {{-- Botões --}}
                    <div class="mt-6 flex items-center justify-end gap-3">
                        <a href="{{ route('home') }}"
                           class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                            ← Voltar
                        </a>

                        <button type="submit"
                                class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-rose-500 to-pink-500 px-5 py-2.5 text-sm font-bold text-white shadow hover:opacity-90 transition">
                            💾 Salvar perfil
                        </button>
                    </div>
                </div>
            </form>

            <div class="text-center text-xs text-gray-400">
                VipRomance • MVP raiz 😎
            </div>
        </div>
    </div>

    {{-- contador da bio --}}
    <script>
        (function () {
            const bio = document.getElementById('bio');
            const out = document.getElementById('bioCount');

            function updateCount() {
                out.textContent = (bio?.value || '').length;
            }

            if (bio && out) {
                bio.addEventListener('input', updateCount);
                updateCount();
            }
        })();
    </script>
</x-app-layout>
