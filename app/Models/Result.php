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
        'id_option',
    ];

    /**
     * Obtener la opción asociada al resultado.
     */
    public function option()
    {
        return $this->belongsTo(Option::class);
    }
}
