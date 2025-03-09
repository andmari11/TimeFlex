<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    // Definir la tabla asociada
    protected $table = 'options';

    // Definir los campos que se pueden asignar en masa
    protected $fillable = [
        'id_question',
        'value',
    ];

    /**
     * Obtener la pregunta asociada a la opción.
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
