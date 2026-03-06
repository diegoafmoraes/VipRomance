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

            </div>
        </div>
    </x-slot>

</x-app-layout>
