<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name'
    ];

    public function users(){

        return $this->hasMany(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }


}
