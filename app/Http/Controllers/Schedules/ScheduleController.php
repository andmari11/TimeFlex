<?php

namespace App\Http\Controllers\Schedules;

use App\Http\Controllers\BrowserHistoryController;
use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function index(){

        if(auth()->user()->role==="admin"){
            BrowserHistoryController::add(
                'Todos los horarios', url()->current()
            );
            $schedules = auth()->user()->company->schedules->reverse()->map(function ($schedule) {
                return $schedule;
            });
            return view('horario', compact('schedules'));
        }
        else{
            $schedules = auth()->user()->section->schedules->reverse()->map(function ($schedule) {
                return $schedule;
            });
            return view('horario', compact('schedules'));

        }


    }

    public function show($id)
    {
        $schedule = Schedule::find($id);

        // Determinar el mes del primer turno (shift) en el schedule
        $firstShift = collect($schedule->shifts)->first();
        if ($firstShift) {
            $month = Carbon::parse($firstShift['start'])->startOfMonth();
        } else {
            $month = Carbon::now()->startOfMonth(); // Mes actual si no hay turnos
        }
        BrowserHistoryController::add(
            "Horario " . $schedule->section->name, url()->current()
        );
        // Ajustar para que comience el calendario desde el lunes anterior
        $startOfCalendar = $month->copy()->startOfMonth()->startOfWeek(Carbon::MONDAY);
        $endOfCalendar = $month->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);

        // Generar las fechas de todo el rango ajustado
        $days = collect();
        $currentDay = $startOfCalendar;

        while ($currentDay <= $endOfCalendar) {
            $days->push([
                'date' => $currentDay->copy(),
                'day_of_week' => $currentDay->dayOfWeek,
                'is_current_month' => $currentDay->month === $month->month, // Identificar si pertenece al mes
            ]);
            $currentDay->addDay();
        }

        return view('schedules/single-schedule-view', compact('schedule', 'days'));
    }




}
