<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventComment;
use App\Models\EventLike;
use Illuminate\Http\Request;

class EventLikeController extends Controller
{
    /** Lista comentários do evento. */
    public function comments(Request $request, Event $event)
    {
        $comments = $event->comments()->get()->map(fn ($c) => [
            'id' => $c->id,
            'body' => $c->body,
            'user_name' => $c->user->name ?? null,
            'created_at' => $c->created_at?->toIso8601String(),
        ]);

        return response()->json($comments);
    }

    /** Adiciona comentário. */
    public function storeComment(Request $request, Event $event)
    {
        $data = $request->validate(['body' => ['required', 'string', 'max:2000']]);

        $comment = EventComment::create([
            'user_id' => $request->user()->id,
            'event_id' => $event->id,
            'body' => $data['body'],
        ]);

        $comment->load('user:id,name');

        return response()->json([
            'id' => $comment->id,
            'body' => $comment->body,
            'user_name' => $comment->user->name,
            'created_at' => $comment->created_at?->toIso8601String(),
        ], 201);
    }

    /** Contagem de curtidas e se o usuário curtiu. */
    public function likeStatus(Request $request, Event $event)
    {
        $userId = $request->user()->id;
        $likesCount = $event->likes()->count();
        $userHasLiked = $event->likes()->where('user_id', $userId)->exists();

        return response()->json([
            'likes_count' => $likesCount,
            'user_has_liked' => $userHasLiked,
        ]);
    }

    /** Curtir evento. */
    public function like(Request $request, Event $event)
    {
        EventLike::firstOrCreate([
            'user_id' => $request->user()->id,
            'event_id' => $event->id,
        ]);

        return response()->json([
            'likes_count' => $event->likes()->count(),
            'user_has_liked' => true,
        ]);
    }

    /** Descurtir evento. */
    public function unlike(Request $request, Event $event)
    {
        EventLike::query()
            ->where('user_id', $request->user()->id)
            ->where('event_id', $event->id)
            ->delete();

        return response()->json([
            'likes_count' => $event->likes()->count(),
            'user_has_liked' => false,
        ]);
    }
}
