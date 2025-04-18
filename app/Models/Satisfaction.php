<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Satisfaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'score',
        'schedule_id'
    ];
}
