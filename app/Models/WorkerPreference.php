<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'holidays'
    ];
    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
