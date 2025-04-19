<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Weight extends Model
{
    use HasFactory;

    protected $table = 'weights';

    protected $fillable = [
        'id_question',
        'value',
    ];

    /**
     * Relación con la pregunta.
     */
    public function question()
    {
        return $this->belongsTo(Question::class, 'id_question');
    }
}
