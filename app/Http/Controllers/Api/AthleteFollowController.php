<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AthleteFollow;
use App\Models\AthleteProfile;
use Illuminate\Http\Request;

class AthleteFollowController extends Controller
{
    /** Lista atletas que o usuário logado segue. */
    public function following(Request $request)
    {
        $profile = $request->user()->athleteProfile;
        if (!$profile) {
            return response()->json([]);
        }

        $list = $profile->following()
            ->with('user:id,name')
            ->get()
            ->map(fn (AthleteProfile $a) => $this->athleteToPublic($a));

        return response()->json($list);
    }

    /** Lista seguidores do usuário logado. */
    public function followers(Request $request)
    {
        $profile = $request->user()->athleteProfile;
        if (!$profile) {
            return response()->json([]);
        }

        $list = $profile->followers()
            ->with('user:id,name')
            ->get()
            ->map(fn (AthleteProfile $a) => $this->athleteToPublic($a));

        return response()->json($list);
    }

    /** Descobrir atletas (sugestões para seguir). */
    public function discover(Request $request)
    {
        try {
            $profile = $request->user()->athleteProfile;
            $followingIds = $profile
                ? $profile->following()->pluck('id')->push($profile->id)->all()
                : [];

            $athletes = AthleteProfile::query()
                ->with('user:id,name')
                ->when(!empty($followingIds), fn ($q) => $q->whereNotIn('id', $followingIds))
                ->limit(20)
                ->get()
                ->map(fn (AthleteProfile $a) => $this->athleteToPublic($a));

            return response()->json($athletes);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([]);
        }
    }

    /** Seguir um atleta. */
    public function follow(Request $request, AthleteProfile $athlete)
    {
        $myProfile = $request->user()->athleteProfile;
        if (!$myProfile) {
            return response()->json(['message' => 'Complete seu perfil de atleta primeiro.'], 422);
        }
        if ($myProfile->id === $athlete->id) {
            return response()->json(['message' => 'Você não pode seguir a si mesmo.'], 422);
        }

        AthleteFollow::firstOrCreate([
            'follower_athlete_id' => $myProfile->id,
            'following_athlete_id' => $athlete->id,
        ]);

        return response()->json(['message' => 'Agora você segue este atleta.', 'following' => true]);
    }

    /** Deixar de seguir. */
    public function unfollow(Request $request, AthleteProfile $athlete)
    {
        $myProfile = $request->user()->athleteProfile;
        if (!$myProfile) {
            return response()->json([], 204);
        }

        AthleteFollow::query()
            ->where('follower_athlete_id', $myProfile->id)
            ->where('following_athlete_id', $athlete->id)
            ->delete();

        return response()->json(['message' => 'Você deixou de seguir.', 'following' => false]);
    }

    /** Ver perfil público de um atleta (para seguir/deixar de seguir). */
    public function show(Request $request, AthleteProfile $athlete)
    {
        $myProfile = $request->user()->athleteProfile;
        $following = $myProfile
            ? $myProfile->following()->where('athlete_profiles.id', $athlete->id)->exists()
            : false;

        return response()->json([
            ...$this->athleteToPublic($athlete->load('user:id,name')),
            'following' => $following,
            'followers_count' => $athlete->followers()->count(),
            'following_count' => $athlete->following()->count(),
        ]);
    }

    private function athleteToPublic(AthleteProfile $a): array
    {
        return [
            'id' => $a->id,
            'name' => $a->user?->name ?? null,
            'belt' => $a->belt,
            'academy' => $a->academy,
            'photo_url' => $a->photo_url,
        ];
    }
}
