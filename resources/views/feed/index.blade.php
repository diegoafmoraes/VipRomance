<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-rose-600 leading-tight flex items-center gap-2">
                Sugestões pra você 💘
                <a href="{{ route('myprofile.edit') }}"
                    class="inline-flex items-center gap-2 rounded-full bg-white/70 px-3 py-1 text-xs font-semibold
          border border-rose-100 hover:border-rose-200">
                    💘 Meu Perfil
                </a>
                <a href="{{ route('chat.index') }}"
                    class="inline-flex items-center gap-2 rounded-full bg-white/70 px-3 py-1 text-xs font-semibold
          border border-rose-100 hover:border-rose-200">
                    💬 Minhas conversas
                </a>


            </h2>

            <div class="text-sm text-gray-500">
                {{ $me->sex }} → {{ $me->seeking }}
                @if($me->is_tester)
                <span class="ml-2 inline-flex items-center rounded-full bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-700">
                    Tester ⭐
                </span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 my-4">
        {{-- Carrossel aleatório --}}
        <div class="bg-white rounded-2xl shadow p-5">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-semibold text-gray-800">Gente aleatória compatível 🎲</h3>
                <span class="text-xs text-gray-500">scroll →</span>
            </div>

            <div class="flex gap-4 overflow-x-auto pb-2">
                @forelse($random as $u)
                <a href="{{ route('profile.public', $u->username) }}"
                    class="min-w-[220px] rounded-2xl bg-white border border-rose-100
       hover:scale-[1.02] hover:shadow-xl transition-all duration-300 hover:border-rose-200 hover:shadow transition p-4">

                    <div class="flex items-center gap-3">
                        @if($u->primaryPhoto)
                        <img src="{{ $u->primaryPhoto->url }}"
                            class="h-12 w-12 rounded-full object-cover border border-rose-200"
                            alt="Foto de {{ $u->username }}">
                        @else
                        <div class="h-12 w-12 rounded-full bg-gradient-to-br from-rose-400 to-pink-500 flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr($u->username,0,1)) }}
                        </div>
                        @endif

                        <div>
                            <div class="font-semibold text-gray-900">{{ $u->username }}</div>
                            <div class="text-xs text-gray-500">{{ $u->city }} - {{ $u->state }}</div>
                        </div>
                    </div>

                    <div class="mt-3 text-sm text-gray-600 line-clamp-2">
                        {{ $u->bio }}
                    </div>

                    <div class="mt-3 flex gap-2 text-xs">
                        <span class="rounded-full bg-gray-100 px-2 py-1">{{ $u->height_cm }}cm</span>
                        <span class="rounded-full bg-gray-100 px-2 py-1">{{ $u->weight_kg }}kg</span>
                        <span class="rounded-full bg-gray-100 px-2 py-1">{{ $u->body_type }}</span>
                    </div>
                </a>
                @empty
                <div class="text-gray-500">Sem sugestões ainda 😅 (por enquanto)</div>
                @endforelse
            </div>
        </div>

        {{-- Últimos cadastrados --}}
        <div class="bg-white rounded-2xl shadow p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-800">Últimos cadastrados ✨</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($latest as $u)
                <a href="{{ route('profile.public', $u->username) }}"
                    class="rounded-2xl bg-white border border-rose-100
                  hover:-translate-y-1 hover:shadow-xl transition-all duration-300
                  hover:border-rose-200 p-5">

                    {{-- Cabeçalho com foto --}}
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-3">

                            {{-- Avatar --}}
                            @if($u->primaryPhoto)
                            <img src="{{ $u->primaryPhoto->url }}"
                                class="h-10 w-10 rounded-full object-cover border border-rose-200"
                                alt="Foto de {{ $u->username }}">
                            @else
                            <div class="h-10 w-10 rounded-full
                                    bg-gradient-to-br from-rose-400 to-pink-500
                                    flex items-center justify-center
                                    text-white font-bold">
                                {{ strtoupper(substr($u->username,0,1)) }}
                            </div>
                            @endif

                            {{-- Nome + Local --}}
                            <div>
                                <div class="font-bold text-gray-900 leading-tight">
                                    {{ $u->username }}
                                </div>

                                <div class="text-xs text-gray-500">
                                    {{ $u->city ?? '—' }}{{ $u->state ? ' - '.$u->state : '' }}
                                </div>
                            </div>
                        </div>

                        {{-- Sexo --}}
                        <span class="text-xs text-gray-500">
                            {{ $u->sex }} → {{ $u->seeking }}
                        </span>
                    </div>

                    {{-- Bio --}}
                    <div class="mt-2 text-sm text-gray-600 line-clamp-2">
                        {{ $u->bio ?: 'Sem bio por enquanto… 👀' }}
                    </div>

                    {{-- Tags --}}
                    <div class="mt-4 flex flex-wrap gap-2 text-xs">
                        @if($u->hair_color)
                        <span class="rounded-full bg-rose-50 text-rose-700 px-2 py-1">
                            {{ $u->hair_color }}
                        </span>
                        @endif

                        @if($u->eye_color)
                        <span class="rounded-full bg-rose-50 text-rose-700 px-2 py-1">
                            {{ $u->eye_color }}
                        </span>
                        @endif

                        @if($u->height_cm)
                        <span class="rounded-full bg-gray-100 px-2 py-1">
                            {{ $u->height_cm }}cm
                        </span>
                        @endif

                        @if($u->weight_kg)
                        <span class="rounded-full bg-gray-100 px-2 py-1">
                            {{ $u->weight_kg }}kg
                        </span>
                        @endif

                        @if($u->body_type)
                        <span class="rounded-full bg-gray-100 px-2 py-1">
                            {{ $u->body_type }}
                        </span>
                        @endif
                    </div>

                    {{-- Botão --}}
                    <div class="mt-4 inline-flex items-center gap-2 text-sm font-bold text-white
                        bg-gradient-to-r from-rose-500 to-pink-500
                        px-3 py-1.5 rounded-full shadow">
                        Ver perfil →
                    </div>

                </a>
                @empty
                <div class="text-gray-500">
                    Ainda não tem ninguém compatível. Bora convidar mais gente 😄
                </div>
                @endforelse
            </div>
        </div>

    </div>
</x-app-layout>