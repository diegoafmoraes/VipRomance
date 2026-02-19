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
            ->where('id', '!=', $me->id)
            ->with('primaryPhoto')
            ->reciprocalMatches($me)
            ->latest('id')
            ->limit(12)
            ->get();

        // Aleatórios compatíveis (carrossel)
        $random = User::query()
            ->where('id', '!=', $me->id)
            ->with('primaryPhoto')
            ->reciprocalMatches($me)
            ->inRandomOrder()
            ->limit(12)
            ->get();

        return view('feed.index', compact('me', 'latest', 'random'));
    }
}
