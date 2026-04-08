<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AthletePost extends Model
{
    use HasFactory;

    protected $fillable = [
        'athlete_profile_id',
        'media_url',
        'media_type',
        'caption',
        'event_id',
    ];

    public function athleteProfile()
    {
        return $this->belongsTo(AthleteProfile::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
