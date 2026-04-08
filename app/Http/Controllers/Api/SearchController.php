<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AthleteProfile;
use App\Models\Team;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Busca unificada: atletas e equipes para o atleta encontrar quem seguir e equipes para se afiliar.
     */
    public function index(Request $request)
    {
        try {
            $q = trim((string) $request->input('q', ''));
            $limit = min(20, max(5, (int) $request->input('limit', 12)));

            $user = $request->user();
            $myProfile = $user->athleteProfile;

            $athletes = collect();
            $teams = collect();

            if ($q !== '') {
                $term = '%' . preg_replace('/\s+/', '%', $q) . '%';

                $athletes = AthleteProfile::query()
                    ->where(function ($query) use ($term) {
                        $query->whereHas('user', fn ($w) => $w->where('name', 'like', $term))
                            ->orWhere('academy', 'like', $term)
                            ->orWhere('belt', 'like', $term);
                    })
                    ->with('user:id,name')
                    ->limit($limit)
                    ->get();

                if ($myProfile) {
                    $athletes = $athletes->reject(fn ($a) => $a->id === $myProfile->id);
                }

                try {
                    $teams = Team::query()
                        ->where(function ($query) use ($term) {
                            $query->where('name', 'like', $term)
                                ->orWhere('description', 'like', $term);
                        })
                        ->with('owner:id,name')
                        ->withCount('members')
                        ->limit($limit)
                        ->get();
                } catch (\Throwable $e) {
                    $teams = collect();
                }
            }

            $myTeamIds = [];
            try {
                if ($myProfile) {
                    $myTeamIds = $myProfile->teams()->pluck('id')->toArray();
                }
            } catch (\Throwable $e) {
                // team_members pode não existir
            }

            return response()->json([
                'athletes' => $athletes->map(fn ($a) => [
                    'id' => $a->id,
                    'name' => $a->user?->name ?? null,
                    'belt' => $a->belt,
                    'academy' => $a->academy,
                    'photo_url' => $a->photo_url,
                ]),
                'teams' => $teams->map(fn ($t) => [
                    'id' => $t->id,
                    'name' => $t->name,
                    'slug' => $t->slug ?? '',
                    'description' => $t->description ?? '',
                    'members_count' => $t->members_count ?? 0,
                    'is_member' => in_array($t->id, $myTeamIds, true),
                ]),
            ]);
        } catch (\Throwable $e) {
            report($e);
            return response()->json(['athletes' => [], 'teams' => []]);
        }
    }
}
