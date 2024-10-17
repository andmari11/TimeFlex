<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];

    public function employees(){
        return $this->hasMany(User::class);
    }

    public function sections(){

        return $this->hasMany(Section::class);
    }


}
