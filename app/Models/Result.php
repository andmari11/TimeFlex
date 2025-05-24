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
        'id_user',
        'id_form',
        'id_schedule'
    ];

    /**
     * Obtener la pregunta asociada al resultado.
     */
    public function question()
    {
        return $this->belongsTo(Question::class, 'id_question');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function form()
    {
        return $this->belongsTo(Form::class, 'id_form');
    }

    /**
     * Obtener el tipo de pregunta asociada al resultado.
     */
    public function questionType()
    {
        return $this->belongsTo(QuestionType::class, 'id_question_type');
    }

    public function file()
    {
        return $this->belongsTo(File::class, 'respuesta', 'id');
    }

    public function shiftTypes()
    {
        return $this->belongsTo(ShiftType::class, 'respuesta', 'id');
    }

    public function schedule(){

        return $this->belongsTo(Schedule::class, 'id_schedule', 'id');
    }
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($result) {
            // Verifica si el registro tiene un archivo asociado
            if ($result->id_question_type == 9 && $result->file) {
                $result->file->delete();
            }
        });
    }

}
