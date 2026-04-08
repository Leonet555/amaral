<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    public const STATUS_DRAFT = 'DRAFT';
    public const STATUS_OPEN = 'OPEN';
    public const STATUS_CLOSED = 'CLOSED';
    public const STATUS_STARTED = 'STARTED';
    public const STATUS_FINISHED = 'FINISHED';

    protected $fillable = [
        'organizer_id',
        'name',
        'description',
        'athlete_info',
        'date',
        'starts_at',
        'location',
        'banner_url',
        'sport_type',
        'registration_deadline',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'starts_at' => 'datetime',
            'registration_deadline' => 'datetime',
        ];
    }

    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function likes()
    {
        return $this->hasMany(EventLike::class);
    }

    public function comments()
    {
        return $this->hasMany(EventComment::class)->with('user:id,name')->orderBy('created_at');
    }
}
