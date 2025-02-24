<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    // Definir la tabla asociada
    protected $table = 'questions';

    // Definir los campos que se pueden asignar en masa
    protected $fillable = [
        'id_form',
        'title',
        'id_question_type',
    ];

    /**
     * Obtener el formulario asociado a la pregunta.
     */
    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    /**
     * Obtener el tipo de pregunta asociado.
     */
    public function options()
    {
        return $this->hasMany(Option::class, 'id_question');
    }
}
