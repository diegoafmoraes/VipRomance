<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PreferenceOption;
use App\Models\UserPreference;
use Illuminate\Support\Facades\Auth;

class MyProfileController extends Controller
{
    /**
     * Carrega a view de TABs e permite edit
     *
     * @param Request $request
     * @return void
     */
    public function edit(Request $request)
    {
        $me = auth()->user()->load('photos');
        $photos = $me->photos ?? collect();

        $optionsByCategory = PreferenceOption::query()
            ->where('is_active', 1)
            ->orderBy('category')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('category');

        $selected = $me->preferences()
            ->get()
            ->map(fn($item) => $item->category . ':' . $item->key)
            ->toArray();

        return view('myprofile.edit', compact(
            'me',
            'photos',
            'optionsByCategory',
            'selected'
        ));
    }

    public function update(Request $request)
    {
        $user = $request->user();

        // sexo e buscando travados: não entram aqui
        $data = $request->validate([
            'bio'           => ['nullable', 'string', 'max:500'],
            'city'          => ['nullable', 'string', 'max:80'],
            'state'         => ['nullable', 'string', 'max:2'],

            'hair_color'    => ['nullable', 'in:LOIRO,CASTANHO,PRETO,RUIVO,GRISALHO,CARECA'],
            'eye_color'     => ['nullable', 'in:AZUL,CASTANHO,CINZA,MEL,VERDE'],

            'height_cm'     => ['nullable', 'integer', 'min:140', 'max:220'],
            'weight_kg'     => ['nullable', 'integer', 'min:40', 'max:200'],

            'body_type'       => ['nullable', 'in:NORMAL,MAGRO,SARADO,MUSCULOSO,FOFINHO,ELEGANTE,SENSUAL'],
            'marital_status'  => ['nullable', 'in:MANTER SIGILO,SOLTEIRO/A,AMIZADE COLORIDA,COM COMPANHEIRO/A,UNIÃO ESTÁVEL,CASADO/A,DIVORCIADO/A,VIÚVO/A'],
        ]);

        $user->fill($data)->save();

        return redirect()
            ->route('myprofile.edit')
            ->with('status', 'Perfil atualizado ✅')
            ->with('activeTab', 'perfil');
    }

    public function updatePreferences(Request $request)
    {
        $me = $request->user();

        $data = $request->validate([
            'preferences'   => ['nullable', 'array'],
            'preferences.*' => ['string'],
        ]);

        $items = $data['preferences'] ?? [];

        UserPreference::where('user_id', $me->id)->delete();

        $rows = [];

        foreach ($items as $value) {
            [$category, $key] = array_pad(explode(':', $value, 2), 2, null);

            if (!$category || !$key) {
                continue;
            }

            $rows[] = [
                'user_id'    => $me->id,
                'category'   => $category,
                'key'        => $key,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (!empty($rows)) {
            UserPreference::insert($rows);
        }

        return redirect()
            ->route('myprofile.edit')
            ->with('status', 'Preferências atualizadas com sucesso! ✨')
            ->with('activeTab', 'prefs');
    }
}
