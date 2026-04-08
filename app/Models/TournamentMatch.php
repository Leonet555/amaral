<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TournamentMatch extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'PENDING';
    public const STATUS_FINISHED = 'FINISHED';

    protected $table = 'matches';

    protected $fillable = [
        'category_id',
        'athlete_1_id',
        'athlete_2_id',
        'winner_id',
        'round_number',
        'match_number',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function athlete1()
    {
        return $this->belongsTo(AthleteProfile::class, 'athlete_1_id');
    }

    public function athlete2()
    {
        return $this->belongsTo(AthleteProfile::class, 'athlete_2_id');
    }

    public function winner()
    {
        return $this->belongsTo(AthleteProfile::class, 'winner_id');
    }
}
