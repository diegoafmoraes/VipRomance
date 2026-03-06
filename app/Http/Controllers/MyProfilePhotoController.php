<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MyProfilePhotoController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $photos = $user->photos()->latest()->get();

        return view('myprofile.photos', compact('user', 'photos'));
    }

    public function store(Request $request)
    {
        $user = $request->user();

        // Limite 7
        if ($user->photos()->count() >= 8) {
            return back()
                ->withErrors('Limite de 8 fotos atingido.')
                ->with('activeTab', 'fotos');
        }

        $request->validate([
            'photo' => ['required', 'image', 'max:2048'], // 2MB
        ]);

        $folder = "users/{$user->username}/photos";

        $path = $request->file('photo')->store($folder, 'public');

        $isFirst = $user->photos()->count() === 0;

        $user->photos()->create([
            'path' => $path,
            'is_primary' => $isFirst,
            'is_private' => 0,
        ]);

        return back()
            ->with('status', 'Foto enviada 📸')
            ->with('activeTab', 'fotos');
    }

    public function destroy(Photo $photo)
    {
        abort_if($photo->user_id !== auth()->id(), 403);

        Storage::disk('public')->delete($photo->path);

        $photo->delete();

        return back()
            ->with('status', 'Foto removida ❌')
            ->with('activeTab', 'fotos');
    }

    public function primary(Photo $photo)
    {
        abort_if($photo->user_id !== auth()->id(), 403);

        /** @var User $user */
        $user = auth()->user();

        $user->photos()->update(['is_primary' => 0]);

        $photo->update(['is_primary' => 1]);

        return back()
            ->with('status', 'Foto principal atualizada ⭐')
            ->with('activeTab', 'fotos');
    }
}
