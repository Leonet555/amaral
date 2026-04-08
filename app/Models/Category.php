<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'belt',
        'weight_min',
        'weight_max',
        'age_min',
        'age_max',
        'gender',
        'max_participants',
        'bracket_generated',
    ];

    protected function casts(): array
    {
        return [
            'weight_min' => 'decimal:2',
            'weight_max' => 'decimal:2',
            'bracket_generated' => 'boolean',
        ];
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function matches()
    {
        return $this->hasMany(TournamentMatch::class);
    }
}
