<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Team extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'logo_url',
        'banner_url',
        'owner_id',
    ];

    protected static function booted(): void
    {
        static::creating(function (Team $team) {
            if (empty($team->slug)) {
                $team->slug = Str::slug($team->name);
            }
        });
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(AthleteProfile::class, 'team_members', 'team_id', 'athlete_id')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function teamMembers()
    {
        return $this->hasMany(TeamMember::class);
    }
}
