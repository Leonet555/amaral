<?php

namespace App\Models;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements CanResetPasswordContract
{
    use CanResetPassword, HasApiTokens, HasFactory, Notifiable;

    public const ROLE_ORGANIZER = 'organizer';
    public const ROLE_ATHLETE = 'athlete';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function athleteProfile()
    {
        return $this->hasOne(AthleteProfile::class);
    }

    public function organizedEvents()
    {
        return $this->hasMany(Event::class, 'organizer_id');
    }
}
