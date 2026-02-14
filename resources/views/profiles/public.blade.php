<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">
                {{ $u->username }}
            </h2>

            <a href="{{ route('home') }}"
               class="text-sm font-semibold text-gray-600 hover:text-rose-600">
                ← Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow p-6">
                <div class="flex items-start gap-4">
                    <div class="h-16 w-16 rounded-2xl bg-gradient-to-br from-rose-400 to-pink-500 flex items-center justify-center text-white text-2xl font-bold">
                        {{ strtoupper(substr($u->username,0,1)) }}
                    </div>

                    <div class="flex-1">
                        <div class="text-gray-700">{{ $u->bio }}</div>

                        <div class="mt-4 grid grid-cols-2 sm:grid-cols-3 gap-2 text-sm">
                            <div class="rounded-lg bg-gray-50 p-3">Cabelo: <b>{{ $u->hair_color }}</b></div>
                            <div class="rounded-lg bg-gray-50 p-3">Olhos: <b>{{ $u->eye_color }}</b></div>
                            <div class="rounded-lg bg-gray-50 p-3">Biotipo: <b>{{ $u->body_type }}</b></div>
                            <div class="rounded-lg bg-gray-50 p-3">Altura: <b>{{ $u->height_cm }} cm</b></div>
                            <div class="rounded-lg bg-gray-50 p-3">Peso: <b>{{ $u->weight_kg }} kg</b></div>
                            <div class="rounded-lg bg-gray-50 p-3">Cidade: <b>{{ $u->city }}/{{ $u->state }}</b></div>
                        </div>

                        <div class="mt-6">
                            <button class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-rose-500 to-pink-500 px-5 py-3 text-white font-semibold shadow hover:opacity-95 transition">
                                💬 Mandar mensagem
                            </button>
                            <span class="ml-3 text-xs text-gray-500">(A gente liga isso na parte de conversas já já)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
