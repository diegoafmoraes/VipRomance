<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public function index(Request $request)
    {
        $me = $request->user();

        $photos = $me->photos()
            ->orderByDesc('is_primary')
            ->orderBy('id')
            ->get();

        return view('myprofile.photos', compact('me', 'photos'));
    }

    public function store(Request $request)
    {
        $me = $request->user();

        // limite total = 7
        $count = $me->photos()->count();
        if ($count >= 7) {
            return back()->withErrors(['photo' => 'Você já atingiu o limite de 7 fotos.']);
        }

        $data = $request->validate([
            'photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'], // 2MB
            'is_private' => ['nullable', 'boolean'],
        ]);

        $username = $me->username ?: ('user-' . $me->id);
        $dir = "users/{$username}";

        // salva no disk public (storage/app/public/...)
        $path = $request->file('photo')->store($dir, 'public'); // ex: users/diego/abc.jpg

        DB::transaction(function () use ($me, $path, $request) {
            $isFirst = $me->photos()->count() === 0;

            // se for primeira foto, vira principal automaticamente
            $makePrimary = $isFirst || $request->boolean('make_primary');

            if ($makePrimary) {
                $me->photos()->update(['is_primary' => 0]);
            }

            $me->photos()->create([
                'path'       => $path,
                'is_primary' => $makePrimary ? 1 : 0,
                'is_private' => $request->boolean('is_private') ? 1 : 0,
            ]);
        });

        return back()->with('status', 'Foto enviada ✅');
    }

    public function setPrimary(Request $request, Photo $photo)
    {
        $me = $request->user();

        abort_unless($photo->user_id === $me->id, 403);

        DB::transaction(function () use ($me, $photo) {
            $me->photos()->update(['is_primary' => 0]);
            $photo->update(['is_primary' => 1]);
        });

        return back()->with('status', 'Foto de perfil atualizada ✅');
    }

    public function destroy(Request $request, Photo $photo)
    {
        $me = $request->user();
        abort_unless($photo->user_id === $me->id, 403);

        DB::transaction(function () use ($me, $photo) {
            $wasPrimary = (bool) $photo->is_primary;

            // apaga arquivo
            Storage::disk('public')->delete($photo->path);

            // apaga registro
            $photo->delete();

            // se apagou a principal, escolhe outra como principal (se existir)
            if ($wasPrimary) {
                $next = $me->photos()->orderBy('id')->first();
                if ($next) {
                    $next->update(['is_primary' => 1]);
                }
            }
        });

        return back()->with('status', 'Foto removida 🗑️');
    }
}
