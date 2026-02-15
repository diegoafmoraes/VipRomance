<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-rose-600 leading-tight flex items-center gap-2">
                Minhas Fotos 📸
            </h2>

            <div class="text-sm text-gray-500 flex items-center gap-2">
                <a href="{{ route('myprofile.edit') }}"
                   class="rounded-full bg-white/70 px-3 py-1 text-xs font-semibold border border-rose-100 hover:border-rose-200">
                    ← Voltar pro Meu Perfil
                </a>

                <span class="rounded-full bg-white/70 px-3 py-1 text-xs font-semibold border border-rose-100">
                    {{ $photos->count() }}/7
                </span>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        @if (session('status'))
            <div class="rounded-2xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-2xl bg-rose-50 border border-rose-200 px-4 py-3 text-rose-800">
                <ul class="list-disc ml-5">
                    @foreach($errors->all() as $e)
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

            <form method="POST" action="{{ route('myprofile.photos.store') }}" enctype="multipart/form-data" class="flex flex-col sm:flex-row gap-3 items-start sm:items-end">
                @csrf

                <div class="w-full sm:w-auto">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Arquivo</label>
                    <input type="file" name="photo" accept="image/*"
                           class="block w-full text-sm file:mr-4 file:rounded-full file:border-0 file:bg-rose-600 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-rose-700"/>
                </div>

                <label class="inline-flex items-center gap-2 text-sm text-gray-700 mt-2 sm:mt-0">
                    <input type="checkbox" name="make_primary" value="1" class="rounded border-gray-300 text-rose-600 focus:ring-rose-500">
                    Tornar foto de perfil
                </label>

                <label class="inline-flex items-center gap-2 text-sm text-gray-700 mt-2 sm:mt-0">
                    <input type="checkbox" name="is_private" value="1" class="rounded border-gray-300 text-rose-600 focus:ring-rose-500">
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
                    @foreach($photos as $p)
                        <div class="group rounded-2xl border border-rose-100 overflow-hidden bg-white relative">
                            <img src="{{ $p->url }}" class="w-full h-44 object-cover" alt="Foto">

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

                                <form method="POST" action="{{ route('myprofile.photos.destroy', $p) }}"
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
</x-app-layout>
