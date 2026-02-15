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
                            {{ $u->photos?->count() ?? 0 }}/5
                        </span>
                    </div>

                    @php
                        $photos = $u->photos ?? collect();
                        $main = $photos->first();
                    @endphp

                    {{-- Foto principal --}}
                    <div class="rounded-2xl overflow-hidden ring-1 ring-rose-100 bg-gradient-to-br from-rose-100 to-pink-100">
                        @if($main)
                            <img src="{{ asset('storage/'.$main->path) }}"
                                 alt="Foto de {{ $u->username }}"
                                 class="w-full h-[280px] sm:h-[360px] object-cover">
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

                    {{-- Thumbs --}}
                    @if($photos->count() > 1)
                        <div class="mt-4 grid grid-cols-5 gap-2">
                            @foreach($photos->take(5) as $p)
                                <div class="aspect-square rounded-xl overflow-hidden ring-1 ring-rose-100 bg-rose-50">
                                    <img src="{{ asset('storage/'.$p->path) }}"
                                         alt="Foto"
                                         class="w-full h-full object-cover">
                                </div>
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
                            <form method="POST" action="{{ route('chat.start', $u->username) }}">
                                @csrf
                                <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-2 rounded-xl
                                           bg-gradient-to-r from-rose-500 to-pink-500 px-4 py-3
                                           text-sm font-extrabold text-white shadow hover:opacity-90">
                                    💬 Mandar mensagem
                                </button>
                            </form>

                            <div class="mt-3 text-xs text-gray-500">
                                Dica: conversa curta, simpática e sem textão… (por enquanto 😄)
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Seção “Características” (já deixa pronto pra expandir) --}}
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
</x-app-layout>
