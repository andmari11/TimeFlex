<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftType extends Model
{
    use HasFactory;
    protected $fillable = [
        'schedule_id',
        'notes',
        'start',
        'end',
        'period',
        'users_needed',
        'weekends_excepted',
    ];
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'shift_user');
    }
    public function hasUser($id_user)
    {
        return $this->users()->where('user_id', $id_user)->exists();
    }
}
