<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionType extends Model
{
    use HasFactory;

    // Definir la tabla asociada
    protected $table = 'question_type';

    // Definir los campos que se pueden asignar en masa
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Obtener las preguntas asociadas a este tipo de pregunta.
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
