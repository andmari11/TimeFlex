<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class form_section extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user',
        'title',
        'summary',
        'start_date',
        'end_date',
    ];

    /**
     * Relación muchos a muchos con el modelo Section
     */
    public function sections()
    {
        return $this->belongsToMany(Section::class, 'form_section', 'form_id', 'section_id');
    }
}
