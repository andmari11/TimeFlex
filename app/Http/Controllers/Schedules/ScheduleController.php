<?php

namespace App\Http\Controllers\Schedules;

use App\Http\Controllers\BrowserHistoryController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\FastApiController;
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
            BrowserHistoryController::add(
                'Horarios personales', url()->current()
            );
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
        $user = auth()->user();
        return view('schedules/single-schedule-view', compact('schedule', 'days', 'user'));
    }


    public function showPersonal($id)
    {
        BrowserHistoryController::add(
            'Horario personal', url()->current()
        );
        $schedule = Schedule::find($id);
        $user = auth()->user();
        if (!$schedule and !$schedule->shifts->isEmpty()) {
            $shifts = $schedule->shifts->filter(function ($shift) use ($user) {
                return in_array($user->id, $shift->users->pluck('id')->toArray());            });
        } else {
            $shifts = collect(); // vacío
        }
        // Determinar el mes del primer turno (shift) en el schedule
        $firstShift = collect($schedule->shifts)->first();
        if ($firstShift) {
            $month = Carbon::parse($firstShift['start'])->startOfMonth();
        } else {
            $month = Carbon::now()->startOfMonth(); // Mes actual si no hay turnos
        }

        // Ajustar para que comience el calendario desde el lunes anterior
        $startOfCalendar = $month->copy()->startOfMonth()->startOfWeek(Carbon::MONDAY);
        $endOfCalendar = $month->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);

        // Generar las fechas de todo el rango ajustado
        $days = collect();
        $currentDay = $startOfCalendar;

        while ($currentDay <= $endOfCalendar) {
            $hasShift = $shifts->some(function ($shift) use ($currentDay) {
                return $currentDay->between($shift->start->startOfDay(), $shift->end->endOfDay());
            });
            $days->push([
                'date' => $currentDay->copy(),
                'day_of_week' => $currentDay->dayOfWeek,
                'is_current_month' => $currentDay->month === $month->month,
                'is_passed' => $currentDay->isBefore(now()->startOfDay()),
                'is_working_day' => !$currentDay->isWeekend() && $hasShift,
                'shifts'=> $schedule->shifts->filter(function ($shift) use ($user, $currentDay) {
                    return in_array($user->id, $shift->users->pluck('id')->toArray()) && Carbon::parse($shift->start)->isSameDay($currentDay);
                })
            ]);
            $currentDay->addDay();
        }
        $nextShift= $schedule->shifts->filter(function ($shift) use ($user) {
            return in_array($user->id, $shift->users->pluck('id')->toArray());
        })->first(function ($shift) {
            return Carbon::parse($shift['start'])->isAfter(now());
        });
        return view('schedules/schedule-personal-view', compact('schedule','user', 'shifts', 'days', 'nextShift'));
    }

    public function stats()
    {
        $schedule = Schedule::find(1);

        $user = auth()->user();
        $dataReceived = FastApiController::sendStats();
        if($dataReceived==null){
            return redirect('/horario')->withErrors(['message' => 'Error al generar gráfico.']);
        }
        $img=base64_encode($dataReceived->body());
        $imgUrl = 'data:image/png;base64,' . $img;
        return view('schedules/stats', compact('schedule','user', 'imgUrl'));
    }
}
