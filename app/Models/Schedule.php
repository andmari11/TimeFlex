<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'section_id',
        'usersJSON',
        'status'
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function shifts()
    {
        return $this->hasMany(Shift::class);
    }

}
