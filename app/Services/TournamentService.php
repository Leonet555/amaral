<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Registration;
use App\Models\TournamentMatch;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TournamentService
{
    public function generateBracket(int $categoryId): Collection
    {
        return DB::transaction(function () use ($categoryId) {
            $category = Category::with('registrations')->lockForUpdate()->findOrFail($categoryId);

            if ($category->bracket_generated) {
                abort(422, 'Bracket already generated for this category.');
            }

            $athleteIds = Registration::query()
                ->where('category_id', $category->id)
                ->where('payment_status', Registration::PAYMENT_PAID)
                ->pluck('athlete_id')
                ->shuffle()
                ->values();

            if ($athleteIds->count() < 2) {
                abort(422, 'At least 2 paid registrations are required to generate bracket.');
            }

            $matches = collect();
            $round = 1;
            $matchNumber = 1;

            for ($i = 0; $i < $athleteIds->count(); $i += 2) {
                $athlete1 = $athleteIds[$i];
                $athlete2 = $athleteIds[$i + 1] ?? null;

                $isBye = $athlete2 === null;
                $matches->push(TournamentMatch::create([
                    'category_id' => $category->id,
                    'athlete_1_id' => $athlete1,
                    'athlete_2_id' => $athlete2,
                    'winner_id' => $isBye ? $athlete1 : null,
                    'round_number' => $round,
                    'match_number' => $matchNumber++,
                    'status' => $isBye ? TournamentMatch::STATUS_FINISHED : TournamentMatch::STATUS_PENDING,
                ]));
            }

            $category->update(['bracket_generated' => true]);

            $this->advanceRoundIfReady($category->id, 1);

            return $matches;
        });
    }

    public function registerMatchResult(int $matchId, int $winnerId): TournamentMatch
    {
        return DB::transaction(function () use ($matchId, $winnerId) {
            $match = TournamentMatch::lockForUpdate()->findOrFail($matchId);

            if ($match->status === TournamentMatch::STATUS_FINISHED) {
                abort(422, 'Match is already finished.');
            }

            $allowedWinners = array_filter([$match->athlete_1_id, $match->athlete_2_id]);
            if (!in_array($winnerId, $allowedWinners, true)) {
                abort(422, 'Winner must be one of the match athletes.');
            }

            $match->update([
                'winner_id' => $winnerId,
                'status' => TournamentMatch::STATUS_FINISHED,
            ]);

            $this->advanceRoundIfReady($match->category_id, $match->round_number);

            return $match->fresh();
        });
    }

    private function advanceRoundIfReady(int $categoryId, int $roundNumber): void
    {
        $roundMatches = TournamentMatch::query()
            ->where('category_id', $categoryId)
            ->where('round_number', $roundNumber)
            ->orderBy('match_number')
            ->get();

        if ($roundMatches->isEmpty() || $roundMatches->contains(fn ($m) => $m->status !== TournamentMatch::STATUS_FINISHED)) {
            return;
        }

        $alreadyExistsNextRound = TournamentMatch::query()
            ->where('category_id', $categoryId)
            ->where('round_number', $roundNumber + 1)
            ->exists();

        if ($alreadyExistsNextRound) {
            return;
        }

        $winners = $roundMatches->pluck('winner_id')->filter()->values();
        if ($winners->count() <= 1) {
            return;
        }

        $nextMatchNumber = 1;
        for ($i = 0; $i < $winners->count(); $i += 2) {
            $athlete1 = $winners[$i];
            $athlete2 = $winners[$i + 1] ?? null;
            $isBye = $athlete2 === null;

            TournamentMatch::create([
                'category_id' => $categoryId,
                'athlete_1_id' => $athlete1,
                'athlete_2_id' => $athlete2,
                'winner_id' => $isBye ? $athlete1 : null,
                'round_number' => $roundNumber + 1,
                'match_number' => $nextMatchNumber++,
                'status' => $isBye ? TournamentMatch::STATUS_FINISHED : TournamentMatch::STATUS_PENDING,
            ]);
        }

        $this->advanceRoundIfReady($categoryId, $roundNumber + 1);
    }
}
