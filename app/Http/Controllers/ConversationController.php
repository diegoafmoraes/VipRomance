<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Conversation;
use Illuminate\Http\Request;

class ConversationController extends Controller
{

    public function index()
    {
        $me = auth()->user();

        $conversations = Conversation::query()
            ->where('user_one_id', $me->id)
            ->orWhere('user_two_id', $me->id)
            ->with([
                'userOne.primaryPhoto',
                'userTwo.primaryPhoto',
                // se tiver relação messages():
                'messages' => fn($q) => $q->latest()->limit(1),
            ])
            ->orderByDesc('last_message_at')
            ->get()
            ->map(function ($c) use ($me) {
                // quem é o outro usuário nessa conversa?
                $c->other = ($c->user_one_id === $me->id) ? $c->userTwo : $c->userOne;

                // última msg (se tiver relação messages)
                $c->lastMessage = $c->messages->first() ?? null;

                return $c;
            });

        return view('chat.index', compact('me', 'conversations'));
    }

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

    public function withUser(string $username)
    {
        $me = auth()->user();
        $other = User::where('username', $username)->firstOrFail();

        abort_if($me->id === $other->id, 403);

        // compatibilidade recíproca (se você quiser manter isso aqui também)
        $ok = ($me->seeking === $other->sex) && ($other->seeking === $me->sex);
        abort_unless($ok, 404);

        $conversation = Conversation::query()
            ->where(function ($q) use ($me, $other) {
                $q->where('user_one_id', $me->id)->where('user_two_id', $other->id);
            })
            ->orWhere(function ($q) use ($me, $other) {
                $q->where('user_one_id', $other->id)->where('user_two_id', $me->id);
            })
            ->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'user_one_id' => $me->id,
                'user_two_id' => $other->id,
                'last_message_at' => now(),
            ]);
        }

        $messages = \App\Models\Message::query()
            ->where('conversation_id', $conversation->id)
            ->orderBy('id')
            ->get();

        return view('chat.show', compact('me', 'other', 'conversation', 'messages'));
    }

    public function sendToUser(Request $request, string $username)
    {
        $me = auth()->user();
        $other = User::where('username', $username)->firstOrFail();

        $request->validate([
            'body' => ['required', 'string', 'max:500'],
        ]);

        // acha a conversa (mesma lógica)
        $conversation = Conversation::query()
            ->where(function ($q) use ($me, $other) {
                $q->where('user_one_id', $me->id)->where('user_two_id', $other->id);
            })
            ->orWhere(function ($q) use ($me, $other) {
                $q->where('user_one_id', $other->id)->where('user_two_id', $me->id);
            })
            ->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'user_one_id' => $me->id,
                'user_two_id' => $other->id,
                'last_message_at' => now(),
            ]);
        }

        // segurança: só participa quem é da conversa
        abort_unless(
            in_array($me->id, [$conversation->user_one_id, $conversation->user_two_id]),
            403
        );

        \App\Models\Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $me->id,
            'body' => $request->string('body')->toString(),
        ]);

        $conversation->update(['last_message_at' => now()]);

        // ✅ volta pro chat POR USERNAME (não por chat.show)
        return redirect()->route('chat.withUser', $other->username);
    }

    /* public function show(Conversation $conversation)
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

        return view('chat.with', compact('conversation', 'messages', 'me', 'other'));
    } */
}
