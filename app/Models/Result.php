<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    // Definir la tabla asociada
    protected $table = 'results';

    // Definir los campos que se pueden asignar en masa
    protected $fillable = [
        'id_question',
        'respuesta',
        'id_question_type',
    ];

    /**
     * Obtener la pregunta asociada al resultado.
     */
    public function question()
    {
        return $this->belongsTo(Question::class, 'id_question');
    }

    /**
     * Obtener el tipo de pregunta asociada al resultado.
     */
    public function questionType()
    {
        return $this->belongsTo(QuestionType::class, 'id_question_type');
    }

}
