<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AthletePost;
use App\Models\Event;
use Illuminate\Http\Request;

class AthletePostController extends Controller
{
    /** Feed: publicações dos atletas que o usuário segue. */
    public function feed(Request $request)
    {
        try {
            $profile = $request->user()->athleteProfile;
            $followingIds = $profile ? $profile->following()->pluck('id')->all() : [];

            if (empty($followingIds)) {
                return response()->json([]);
            }

            $posts = AthletePost::query()
                ->with(['athleteProfile.user:id,name', 'event:id,name,date'])
                ->whereIn('athlete_profile_id', $followingIds)
                ->latest()
                ->limit(50)
                ->get()
                ->map(fn (AthletePost $p) => $this->toPublicWithAuthor($p));

            return response()->json($posts);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([]);
        }
    }

    /** Feed discover: torneios como "posts" para curtir e comentar (quando o feed está vazio). */
    public function feedDiscover(Request $request)
    {
        try {
            $userId = $request->user()->id;

            $events = Event::query()
                ->whereIn('status', [Event::STATUS_OPEN, Event::STATUS_STARTED])
                ->orderBy('date')
                ->limit(20)
                ->get();

            return response()->json($events->map(function (Event $event) use ($userId) {
                $likesCount = $event->likes()->count();
                $commentsCount = $event->comments()->count();
                $userHasLiked = $event->likes()->where('user_id', $userId)->exists();

                return [
                    'type' => 'event',
                    'id' => $event->id,
                    'name' => $event->name,
                    'description' => $event->description,
                    'banner_url' => $event->banner_url,
                    'date' => $event->date?->toDateString(),
                    'location' => $event->location,
                    'sport_type' => $event->sport_type,
                    'likes_count' => $likesCount,
                    'comments_count' => $commentsCount,
                    'user_has_liked' => $userHasLiked,
                ];
            }));
        } catch (\Throwable $e) {
            report($e);
            return response()->json([]);
        }
    }

    /** Lista publicações do atleta logado (para o perfil / grid). */
    public function index(Request $request)
    {
        $profile = $request->user()->athleteProfile;
        if (!$profile) {
            return response()->json([]);
        }

        $posts = $profile->posts()
            ->with('event:id,name,date')
            ->latest()
            ->get()
            ->map(fn (AthletePost $p) => $this->toPublic($p));

        return response()->json($posts);
    }

    /** Cria uma nova publicação (foto ou vídeo). */
    public function store(Request $request)
    {
        $profile = $request->user()->athleteProfile;
        if (!$profile) {
            return response()->json(['message' => 'Complete seu perfil primeiro.'], 422);
        }

        $data = $request->validate([
            'media_url' => ['required', 'string'],
            'media_type' => ['required', 'string', 'in:image,video'],
            'caption' => ['nullable', 'string', 'max:2200'],
            'event_id' => ['nullable', 'integer', 'exists:events,id'],
        ]);

        $data['athlete_profile_id'] = $profile->id;
        $post = AthletePost::create($data);

        return response()->json($this->toPublic($post->load('event:id,name,date')), 201);
    }

    /** Remove uma publicação (só o dono). */
    public function destroy(Request $request, AthletePost $athletePost)
    {
        $profile = $request->user()->athleteProfile;
        if (!$profile || $athletePost->athlete_profile_id !== $profile->id) {
            abort(403, 'Access denied.');
        }

        $athletePost->delete();
        return response()->json(null, 204);
    }

    private function toPublic(AthletePost $p): array
    {
        return [
            'id' => $p->id,
            'media_url' => $p->media_url,
            'media_type' => $p->media_type,
            'caption' => $p->caption,
            'event' => $p->event ? ['id' => $p->event->id, 'name' => $p->event->name, 'date' => $p->event->date?->toDateString()] : null,
            'created_at' => $p->created_at?->toIso8601String(),
        ];
    }

    private function toPublicWithAuthor(AthletePost $p): array
    {
        $author = $p->athleteProfile;
        return [
            'id' => $p->id,
            'media_url' => $p->media_url,
            'media_type' => $p->media_type,
            'caption' => $p->caption,
            'event' => $p->event ? ['id' => $p->event->id, 'name' => $p->event->name, 'date' => $p->event->date?->toDateString()] : null,
            'created_at' => $p->created_at?->toIso8601String(),
            'author' => $author ? [
                'id' => $author->id,
                'name' => $author->user->name ?? null,
                'photo_url' => $author->photo_url,
            ] : null,
        ];
    }
}
