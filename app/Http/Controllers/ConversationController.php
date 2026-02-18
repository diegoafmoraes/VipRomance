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
            ->where(function ($q) use ($me, $other) {
                $q->where('user_one_id', $me->id)->where('user_two_id', $other->id);
            })
            ->orWhere(function ($q) use ($me, $other) {
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
        // return redirect()->route('home')->with('status', 'Conversa iniciada! (já já a tela de mensagens entra)');
        return redirect()->route('chat.show', $c);
    }

    public function show(Conversation $conversation)
    {
        $me = auth()->user();

        abort_unless(
            $conversation->user_one_id === $me->id || $conversation->user_two_id === $me->id,
            403
        );

        $messages = $conversation->messages()
            ->with(['sender.primaryPhoto'])
            ->orderBy('id')
            ->get();

        // pega o "outro lado" da conversa
        $otherId = ($conversation->user_one_id === $me->id)
            ? $conversation->user_two_id
            : $conversation->user_one_id;

        $other = \App\Models\User::findOrFail($otherId);

        $messages = $conversation->messages()
            ->orderBy('created_at')
            ->get();

        return view('chat.show', compact('conversation', 'messages', 'me', 'other'));
    }

    public function send(Request $request, Conversation $conversation)
    {
        $me = auth()->user();

        // segurança: só participa quem é da conversa
        abort_unless(
            in_array($me->id, [$conversation->user_one_id, $conversation->user_two_id]),
            403
        );

        // ✅ VALIDAÇÃO AQUI
        $data = $request->validate([
            'body' => ['required', 'string', 'max:500'],
        ]);

        // cria mensagem
        $conversation->messages()->create([
            'sender_id' => $me->id,
            'body'      => $data['body'],
        ]);

        // atualiza última msg
        $conversation->update([
            'last_message_at' => now(),
        ]);

        return redirect()->route('chat.show', $conversation);
    }
}
