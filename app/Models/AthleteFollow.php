<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AthleteFollow extends Model
{
    protected $fillable = ['follower_athlete_id', 'following_athlete_id'];

    public function follower(): BelongsTo
    {
        return $this->belongsTo(AthleteProfile::class, 'follower_athlete_id');
    }

    public function following(): BelongsTo
    {
        return $this->belongsTo(AthleteProfile::class, 'following_athlete_id');
    }
}
