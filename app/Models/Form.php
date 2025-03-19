<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;

    // Definir la tabla asociada
    protected $table = 'forms';

    // Definir los campos que se pueden asignar en masa
    protected $fillable = [
        'id_user',
        'title',
        'summary',
        'start_date',
        'end_date',
        'id_section',
    ];

    /**
     * Obtener el usuario asociado al formulario.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function section()
    {
        return $this->belongsTo(Section::class, 'id_section');
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'id_form');
    }

    public function sections()
    {
        return $this->belongsToMany(Section::class, 'form_section', 'form_id', 'section_id');
    }

}
