<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Holidays extends Model
{
    use HasFactory;
    protected $fillable = [
        'id', 'fecha_solicitud', 'dia_vacaciones', 'estado', 'pregunta_id'
    ];
    public function user()
    {
        return $this->belongsToMany(User::class);
    }
    public function question()
    {
        return $this->belongsTo(Question::class, 'pregunta_id');
    }
}
