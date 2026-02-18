<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class FeedController extends Controller
{
    public function index(Request $request)
    {
        $me = $request->user();

        // Últimos cadastrados compatíveis (grid principal)
        $latest = User::query()
            /* ->reciprocalMatches($me)
            ->latest('id')
            ->take(12)
            ->get(); */
            ->where('id', '!=', $me->id)
            ->with('primaryPhoto')
            ->latest()
            ->limit(12)
            ->get();

        // Aleatórios compatíveis (carrossel)
        $random = User::query()
            /* ->reciprocalMatches($me)
            ->inRandomOrder()
            ->take(12)
            ->get(); */
            ->where('id', '!=', $me->id)
            ->with('primaryPhoto')
            ->inRandomOrder()
            ->limit(12)
            ->get();

        return view('feed.index', compact('me', 'latest', 'random'));
    }
}
