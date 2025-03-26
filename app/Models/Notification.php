<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'message', 'url', 'read', 'tipo', 'email', 'nombre', 'apellidos', 'duda'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function shiftExchange()
    {
        return $this->belongsTo(ShiftExchange::class);
    }
}
