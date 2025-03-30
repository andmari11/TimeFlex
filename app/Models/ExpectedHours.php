<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class ExpectedHours extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'month', 'year',
        'morning_hours', 'afternoon_hours', 'night_hours',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
