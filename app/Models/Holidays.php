<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Holidays extends Model
{
    use HasFactory;
    protected $fillable = [
        'id', 'fecha_solicitud', 'dia_vacaciones', 'estado',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
