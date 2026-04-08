<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    public const PAYMENT_PENDING = 'PENDING';
    public const PAYMENT_PAID = 'PAID';

    protected $fillable = [
        'athlete_id',
        'category_id',
        'payment_status',
    ];

    public function athlete()
    {
        return $this->belongsTo(AthleteProfile::class, 'athlete_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
