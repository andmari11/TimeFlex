<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'company_id'
    ];

    public function users(){

        return $this->hasMany(User::class);
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function schedules(){
        return $this->hasMany(Schedule::class);
    }

    public function forms()
    {
        return $this->belongsToMany(Form::class, 'form_section', 'section_id', 'form_id');
    }
}
