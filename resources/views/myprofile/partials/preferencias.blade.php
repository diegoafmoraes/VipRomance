@php
$me = $me ?? auth()->user();
$optionsByCategory = $optionsByCategory ?? collect();
$selected = $selected ?? [];

$titles = [
'open_to' => '🔥 Aberto a',
'preferences' => '💖 Preferências',
'personality' => '✨ Personalidade',
];

$subtitles = [
'open_to' => 'coisas que você toparia explorar',
'preferences' => 'o tipo de conexão que você procura',
'personality' => 'traços que combinam com você',
];
@endphp

<form method="POST" action="{{ route('myprofile.preferences.update') }}" class="space-y-6">
    @csrf
    @method('PUT')

    <div class="bg-white rounded-2xl shadow p-6 border border-rose-100">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="font-semibold text-gray-800">Preferências e Características ✨</h3>
                <div class="text-xs text-gray-500 mt-1">marque quantas opções quiser em cada grupo</div>
            </div>

            <span class="text-xs text-gray-500">perfil mais completo = mais interessante 😎</span>
        </div>

        @foreach($titles as $category => $title)
        <div class="mb-8 last:mb-0">
            <div class="mb-3">
                <div class="font-semibold text-gray-800">{{ $title }}</div>
                <div class="text-xs text-gray-500">{{ $subtitles[$category] ?? '' }}</div>
            </div>

            <div class="flex flex-wrap gap-3">
                @foreach(($optionsByCategory[$category] ?? collect()) as $opt)
                @php
                $value = $opt->category . ':' . $opt->key;
                $isChecked = in_array($value, $selected, true);
                @endphp

                <label class="cursor-pointer">
                    <input
                        type="checkbox"
                        name="preferences[]"
                        value="{{ $value }}"
                        class="peer sr-only"
                        @checked($isChecked)>

                    <span class="inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold transition-all duration-150
                                {{ $isChecked
                                    ? 'bg-gradient-to-r from-rose-500 to-pink-500 text-white shadow-md scale-[1.02] ring-2 ring-rose-200'
                                    : 'bg-gray-100 text-gray-700 hover:bg-rose-50 hover:text-rose-700' }}
                                peer-checked:bg-gradient-to-r
                                peer-checked:from-rose-500
                                peer-checked:to-pink-500
                                peer-checked:text-white
                                peer-checked:shadow-md
                                peer-checked:scale-[1.02]
                                peer-checked:ring-2
                                peer-checked:ring-rose-200">
                        {{ $opt->label }}
                    </span>
                </label>
                @endforeach
            </div>
        </div>
        @endforeach

        {{-- Botões --}}
        <div class="mt-6 flex items-center justify-end gap-3">
            <a href="{{ route('home') }}"
                class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                ← Voltar
            </a>

            <div class="pt-2">
                <button
                    type="submit"
                    class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-rose-500 to-pink-500 px-5 py-2.5 text-sm font-semibold text-white shadow hover:opacity-90 transition">
                    💾 Salvar preferências
                </button>
            </div>
        </div>
    </div>
</form>