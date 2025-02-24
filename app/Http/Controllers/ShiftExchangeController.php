<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShiftExchangeController extends Controller
{
    public function create()
    {
        $userShifts = auth()->user()->shifts;
        return view('schedules.shift-exchange', compact('userShifts'));

    }
}
