<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MyProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('myprofile.edit', [
            'me' => $request->user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        // sexo e buscando travados: não entram aqui
        $data = $request->validate([
            'bio'        => ['nullable', 'string', 'max:500'],
            'city'       => ['nullable', 'string', 'max:80'],
            'state'      => ['nullable', 'string', 'max:2'],

            'hair_color' => ['nullable', 'in:LOIRO,CASTANHO,PRETO,RUIVO,GRISALHO,CARECA'],
            'eye_color'  => ['nullable', 'in:AZUL,CASTANHO,CINZA,MEL,VERDE'],

            'height_cm'  => ['nullable', 'integer', 'min:140', 'max:220'],
            'weight_kg'  => ['nullable', 'integer', 'min:40', 'max:200'],

            'body_type'  => ['nullable', 'in:NORMAL,MAGRO,SARADO,MUSCULOSO,FOFINHO,ELEGANTE,SENSUAL'],
        ]);

        $user->fill($data)->save();

        return redirect()
            ->route('myprofile.edit')
            ->with('status', 'Perfil atualizado ✅');
    }
}
