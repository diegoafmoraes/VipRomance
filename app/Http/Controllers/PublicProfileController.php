<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class PublicProfileController extends Controller
{
    public function show(string $username)
    {
        $u = User::where('username', $username)->where('is_active', 1)->firstOrFail();
        return view('profiles.public', compact('u'));
    }
}