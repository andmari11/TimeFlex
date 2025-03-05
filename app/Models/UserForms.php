<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserForms extends Model
{
    use HasFactory;

    // Definir la tabla asociada
    protected $table = 'user_forms';

    // Definir los campos que se pueden asignar en masa
    protected $fillable = [
        'id_user',
    ];

    /**
     * Obtener el usuario asociado al formulario.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
