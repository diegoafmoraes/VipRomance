<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PreferenceOption;
use Illuminate\Http\Request;

class PublicProfileController extends Controller
{
    public function show(string $username)
    {
        $me = auth()->user();

        $u = User::query()
            ->where('username', $username)
            ->with([
                'photos' => fn($q) => $q->orderByDesc('is_primary')->orderBy('id'),
            ])
            ->firstOrFail();

        // Se for o próprio perfil, libera sempre
        if ($me->id !== $u->id) {
            // viewer.seeking == profile.sex  AND  profile.seeking == viewer.sex
            $ok = ($me->seeking === $u->sex) && ($u->seeking === $me->sex);

            abort_unless($ok, 404);
        }

        // Preferências públicas do perfil visitado
        $rawPrefs = $u->preferences()->get();

        $optionMap = PreferenceOption::query()
            ->where('is_active', 1)
            ->get()
            ->keyBy(fn ($item) => $item->category . ':' . $item->key);

        $publicPrefs = $rawPrefs
            ->groupBy('category')
            ->map(function ($items) use ($optionMap) {
                return $items
                    ->map(function ($pref) use ($optionMap) {
                        $lookup = $pref->category . ':' . $pref->key;
                        return $optionMap[$lookup]->label ?? $pref->key;
                    })
                    ->values();
            });

        return view('profiles.public', compact('me', 'u', 'publicPrefs'));
    }
}