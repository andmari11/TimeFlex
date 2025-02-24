<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftExchange extends Model
{
    use HasFactory;
    protected $fillable = [
        'demander_id',
        'receiver_id',
        'shift_receiver_id',
        'shift_demander_id',
    ];

    public function demander()
    {
        return $this->belongsTo(User::class, 'demander_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function shiftReceiver()
    {
        return $this->belongsTo(Shift::class, 'shift_receiver_id');
    }

    public function shiftDemander()
    {
        return $this->belongsTo(Shift::class, 'shift_demander_id');
    }

}
