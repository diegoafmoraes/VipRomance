<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-bold text-2xl text-rose-600 leading-tight">
                Minhas Fotos 📸
            </h2>

            <div class="text-sm text-gray-500">
                {{ $photos->count() }}/8
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-rose-50 via-pink-50 to-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

            @if (session('status'))
                <div class="rounded-2xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-emerald-800 shadow-sm">
                    ✅ {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-2xl bg-rose-50 border border-rose-200 px-4 py-3 text-rose-800 shadow-sm">
                    <div class="font-semibold mb-1">Ops…</div>
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                    </ul>
                </div>
            @endif

            {{-- Upload --}}
            <div class="bg-white rounded-2xl shadow p-6 border border-rose-100">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="font-extrabold text-gray-900">Adicionar foto</div>
                        <div class="text-xs text-gray-500">JPG/PNG/WEBP • até 2MB • máximo 8 fotos</div>
                    </div>

                    <form method="POST" action="{{ route('myphotos.store') }}" enctype="multipart/form-data">
                        @csrf
                        <label class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-rose-500 to-pink-500 px-4 py-2.5 text-sm font-bold text-white shadow hover:opacity-90 cursor-pointer">
                            ➕ Escolher foto
                            <input type="file" name="photo" class="hidden" accept="image/*"
                                   onchange="this.form.submit()" {{ $photos->count() >= 8 ? 'disabled' : '' }}>
                        </label>
                    </form>
                </div>

                @if($photos->count() >= 8)
                    <div class="mt-3 text-xs text-rose-600 font-semibold">
                        Limite atingido. Apague uma foto pra adicionar outra 😄
                    </div>
                @endif
            </div>

            {{-- Grid: 8 slots --}}
            @php
                $max = 8;
                $main = $photos->firstWhere('is_primary', true);
            @endphp

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @for($i=1; $i <= $max; $i++)
                    @php $p = $photos[$i-1] ?? null; @endphp

                    <div class="bg-white rounded-2xl shadow border border-rose-100 overflow-hidden">
                        <div class="aspect-square bg-gradient-to-br from-rose-100 to-pink-100 relative">
                            @if($p)
                                <img src="{{ asset('storage/'.$p->path) }}" class="w-full h-full object-cover" alt="Foto">

                                {{-- botão X --}}
                                <form method="POST" action="{{ route('myphotos.destroy', $p->id) }}"
                                      onsubmit="return confirm('Remover esta foto?');"
                                      class="absolute top-2 right-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="h-9 w-9 rounded-full bg-black/60 text-white font-bold shadow hover:bg-black/75">
                                        ✕
                                    </button>
                                </form>

                                {{-- badge principal + botão definir --}}
                                @if($p->is_primary)
                                    <div class="absolute bottom-2 left-2">
                                        <span class="inline-flex items-center rounded-full bg-emerald-500/90 px-2 py-1 text-xs font-bold text-white shadow">
                                            Perfil
                                        </span>
                                    </div>
                                @else
                                    <form method="POST" action="{{ route('myphotos.primary', $p->id) }}"
                                          class="absolute bottom-2 left-2">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center rounded-full bg-white/85 px-2 py-1 text-xs font-bold text-gray-800 shadow hover:bg-white">
                                            Tornar perfil
                                        </button>
                                    </form>
                                @endif
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <div class="text-center">
                                        <div class="text-3xl">📷</div>
                                        <div class="text-xs text-gray-600 font-semibold mt-1">Slot {{ $i }}</div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="p-3 text-xs text-gray-500 flex items-center justify-between">
                            <span>{{ $i === 1 ? 'Foto de perfil' : 'Álbum' }}</span>
                            <span>{{ $p ? ($p->is_primary ? 'Principal' : ' ') : 'Vazio' }}</span>
                        </div>
                    </div>
                @endfor
            </div>

            <div class="text-center text-xs text-gray-400">
                Dica: a primeira foto marcada como “Perfil” é a que aparece no feed 😎
            </div>
        </div>
    </div>
</x-app-layout>
