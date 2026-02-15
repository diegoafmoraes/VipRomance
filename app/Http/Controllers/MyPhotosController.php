<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class MyPhotosController extends Controller
{
    private int $maxPhotos = 8; // 1 perfil + 7 álbum

    private function userFolder($user): string
    {
        $username = preg_replace('~[^a-z0-9_-]+~i', '-', (string) $user->username);
        $username = trim($username, '-') ?: 'user';
        return "uploads/users/{$user->id}-{$username}";
    }

    public function index(Request $request)
    {
        $me = $request->user();
        $photos = $me->photos()->orderByDesc('is_primary')->orderBy('id')->get();

        return view('myphotos.index', compact('me', 'photos'));
    }

    public function store(Request $request)
    {
        $me = $request->user();

        $count = $me->photos()->count();
        if ($count >= $this->maxPhotos) {
            throw ValidationException::withMessages([
                'photo' => ["Você já atingiu o limite de {$this->maxPhotos} fotos."],
            ]);
        }

        $data = $request->validate([
            'photo' => ['required', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048'], // 2MB
        ]);

        $file = $data['photo'];

        $folder = $this->userFolder($me);
        $path = $file->store($folder, 'public'); // storage/app/public/...

        $isFirst = ($count === 0);

        $photo = Photo::create([
            'user_id'    => $me->id,
            'path'       => $path,
            'is_primary' => $isFirst, // primeira vira perfil automaticamente
        ]);

        return redirect()
            ->route('myphotos.index')
            ->with('status', $isFirst ? 'Foto de perfil definida ✅' : 'Foto adicionada ✅');
    }

    public function makePrimary(Request $request, Photo $photo)
    {
        $me = $request->user();

        abort_unless($photo->user_id === $me->id, 403);

        // zera todas e marca essa
        $me->photos()->update(['is_primary' => false]);
        $photo->update(['is_primary' => true]);

        return redirect()
            ->route('myphotos.index')
            ->with('status', 'Foto de perfil atualizada ✅');
    }

    public function destroy(Request $request, Photo $photo)
    {
        $me = $request->user();
        abort_unless($photo->user_id === $me->id, 403);

        // apaga arquivo
        if ($photo->path && Storage::disk('public')->exists($photo->path)) {
            Storage::disk('public')->delete($photo->path);
        }

        $wasPrimary = (bool) $photo->is_primary;
        $photo->delete();

        // se deletou a principal, escolhe outra como principal (se existir)
        if ($wasPrimary) {
            $next = $me->photos()->orderBy('id')->first();
            if ($next) {
                $me->photos()->update(['is_primary' => false]);
                $next->update(['is_primary' => true]);
            }
        }

        return redirect()
            ->route('myphotos.index')
            ->with('status', 'Foto removida ✅');
    }
}
