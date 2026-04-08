<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamMember extends Model
{
    public const ROLE_MEMBER = 'member';
    public const ROLE_ADMIN = 'admin';

    protected $fillable = ['team_id', 'athlete_id', 'role'];

    protected $attributes = [
        'role' => self::ROLE_MEMBER,
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function athlete(): BelongsTo
    {
        return $this->belongsTo(AthleteProfile::class, 'athlete_id');
    }
}
