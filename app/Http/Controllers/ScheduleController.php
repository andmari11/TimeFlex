<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(){

        if(auth()->user()->role==="admin"){
            $schedules = auth()->user()->company->schedules->reverse()->map(function ($schedule) {
                $schedule->scheduleJSON = json_decode($schedule->scheduleJSON, true);
                return $schedule;
            });
            return view('horario', compact('schedules'));
        }
        else{
            $schedule=null;
            return view('horario', compact('schedule'));

        }


    }
}
