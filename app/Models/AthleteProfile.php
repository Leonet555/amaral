<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AthleteProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'birth_date',
        'weight',
        'belt',
        'academy',
        'gender',
        'photo_url',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'weight' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class, 'athlete_id');
    }

    /** Quem este atleta segue. */
    public function following()
    {
        return $this->belongsToMany(AthleteProfile::class, 'athlete_follows', 'follower_athlete_id', 'following_athlete_id')
            ->withTimestamps();
    }

    /** Quem segue este atleta. */
    public function followers()
    {
        return $this->belongsToMany(AthleteProfile::class, 'athlete_follows', 'following_athlete_id', 'follower_athlete_id')
            ->withTimestamps();
    }

    /** Equipes das quais este atleta é membro. */
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_members', 'athlete_id', 'team_id')
            ->withPivot('role')
            ->withTimestamps();
    }

    /** Publicações (fotos/vídeos) do atleta. */
    public function posts()
    {
        return $this->hasMany(AthletePost::class, 'athlete_profile_id');
    }

    /** Partidas que este atleta venceu (para calcular títulos). */
    public function wins()
    {
        return $this->hasMany(TournamentMatch::class, 'winner_id');
    }

    /**
     * Categorias em que o atleta foi campeão (venceu a final da categoria).
     */
    public function championshipsWon(): \Illuminate\Support\Collection
    {
        $sub = \App\Models\TournamentMatch::query()
            ->selectRaw('category_id, MAX(round_number) as max_round')
            ->groupBy('category_id');

        $finalMatchIds = \App\Models\TournamentMatch::query()
            ->joinSub($sub, 'max_rounds', function ($join) {
                $join->on('matches.category_id', '=', 'max_rounds.category_id')
                    ->on('matches.round_number', '=', 'max_rounds.max_round');
            })
            ->where('matches.winner_id', $this->id)
            ->pluck('matches.id');

        return \App\Models\TournamentMatch::query()
            ->with(['category.event'])
            ->whereIn('id', $finalMatchIds)
            ->get()
            ->map(fn ($m) => [
                'event' => $m->category->event,
                'category' => $m->category,
                'won_at' => $m->updated_at?->toIso8601String(),
            ])
            ->values();
    }
}
