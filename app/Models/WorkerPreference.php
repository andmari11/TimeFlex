<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'holidays',
        'form_id',
        'holidays_weight',
        'preferred_shift_types',
        'preferred_shift_types_weight',
        'past_satisfaction',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
