<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\TournamentMatch;
use App\Services\TournamentService;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function __construct(private readonly TournamentService $tournamentService)
    {
    }

    public function generateBracket(Request $request, Category $category)
    {
        abort_unless($category->event->organizer_id === $request->user()->id, 403, 'You can only manage your own categories.');

        $matches = $this->tournamentService->generateBracket($category->id);

        return response()->json($matches, 201);
    }

    public function indexByCategory(Request $request, Category $category)
    {
        $event = $category->event;
        $isOwner = $event->organizer_id === $request->user()->id;
        if (!$isOwner && !in_array($event->status, [\App\Models\Event::STATUS_OPEN, \App\Models\Event::STATUS_STARTED], true)) {
            abort(403, 'Evento nao disponivel.');
        }

        $matches = $category->matches()
            ->with(['athlete1.user', 'athlete2.user', 'winner.user'])
            ->orderBy('round_number')
            ->orderBy('match_number')
            ->get();

        return response()->json($matches);
    }

    public function registerResult(Request $request, TournamentMatch $match)
    {
        abort_unless($match->category->event->organizer_id === $request->user()->id, 403, 'You can only manage your own matches.');

        $data = $request->validate([
            'winner_id' => ['required', 'exists:athlete_profiles,id'],
        ]);

        $updated = $this->tournamentService->registerMatchResult($match->id, (int) $data['winner_id']);

        return response()->json($updated->load(['athlete1', 'athlete2', 'winner']));
    }
}
