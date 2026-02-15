<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PublicProfileController extends Controller
{
    public function show(string $username)
    {
        $me = auth()->user();

        $u = User::query()
            ->where('username', $username)
            ->with(['photos' => fn($q) => $q->orderByDesc('is_primary')->orderBy('id')])
            ->firstOrFail();

        // Se for o próprio perfil, libera sempre
        if ($me->id !== $u->id) {
            // Regra que você definiu:
            // viewer.seeking == profile.sex  AND  profile.seeking == viewer.sex
            $ok = ($me->seeking === $u->sex) && ($u->seeking === $me->sex);

            abort_unless($ok, 404);
        }

        return view('profiles.public', compact('me', 'u'));
    }
}
