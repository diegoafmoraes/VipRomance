<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Conversation;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function start(string $username)
    {
        $me = auth()->user();
        $other = User::where('username', $username)->firstOrFail();

        abort_if($me->id === $other->id, 403);

        // compatibilidade recíproca
        $ok = ($me->seeking === $other->sex) && ($other->seeking === $me->sex);
        abort_unless($ok, 404);

        // procura conversa existente (ajuste nomes conforme seu schema)
        $c = Conversation::query()
            ->where(function($q) use ($me, $other) {
                $q->where('user_one_id', $me->id)->where('user_two_id', $other->id);
            })
            ->orWhere(function($q) use ($me, $other) {
                $q->where('user_one_id', $other->id)->where('user_two_id', $me->id);
            })
            ->first();

        if (!$c) {
            $c = Conversation::create([
                'user_one_id' => $me->id,
                'user_two_id' => $other->id,
                'last_message_at' => now(),
            ]);
        }

        // por enquanto, só redireciona pro "mensagens" depois
        return redirect()->route('home')->with('status', 'Conversa iniciada! (já já a tela de mensagens entra)');
    }
}
