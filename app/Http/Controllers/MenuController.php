<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Schedule;
use App\Models\Section;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(){
        $section=null;
        $lastSchedule = auth()->user()->section->schedules->last()->id;

        if (is_object($lastSchedule) && isset($lastSchedule->id)) {
            $days = MenuController::prepareScheduleData($lastSchedule->id);
        } else {
            $days = MenuController::prepareEmptyData();
        }
        $nextShift = $this->getNextShift($days);
        return view('menu', compact('section', 'days', 'nextShift'));
    }

    public function indexAdmin($id){
        $section=auth()->user()->company->sections()->where('id', $id)->first();
        $lastSchedule = $section->schedules->last();

        if (is_object($lastSchedule) && isset($lastSchedule->id)) {
            $days = MenuController::prepareScheduleData($lastSchedule->id);
        } else {
            $days = MenuController::prepareEmptyData();
        }
        if(!$section){
            abort(404);
        }
        $nextShift = $this->getNextShift($days);

        return view('menu', compact('section', 'days', 'nextShift'));
    }
    private function getNextShift($days){
        $nextShiftDay = $days->first(function ($day) {
            return $day['shifts']->first(function ($shift) {
                return Carbon::parse($shift->start)->isAfter(now()) && in_array(auth()->user()->id, $shift->users->pluck('id')->toArray());
            });
        });

        return $nextShiftDay ? $nextShiftDay['shifts']->first() : null;
    }
    private function prepareScheduleData($id)
    {
        $schedule = Schedule::findOrFail($id);
        $shifts = $schedule->shifts;

        // Guardar en el historial del navegador
        BrowserHistoryController::add("Horario " . $schedule->section->name, url()->current());

        // Obtener la fecha actual
        $startDate = Carbon::now()->startOfDay();
        $endDate = $startDate->copy()->addDays(4); // Próximos 5 días (hoy + 4 días)

        // Generar las fechas dentro del rango
        $days = collect();
        for ($currentDay = $startDate; $currentDay <= $endDate; $currentDay->addDay()) {
            $days->push([
                'date' => $currentDay->copy(),
                'day_of_week' => $this->getDayName($currentDay->dayOfWeek),
                'is_today' => $currentDay->isToday(),
                'shifts' => $shifts->filter(fn($shift) => Carbon::parse($shift->start)->isSameDay($currentDay) and
                    (auth()->user()->role === 'admin' or in_array(auth()->user()->id, $shift->users->pluck('id')->toArray())))
            ]);
        }

        return ($days);
    }
    private function prepareEmptyData()
    {
        // Obtener la fecha actual
        $startDate = Carbon::now()->startOfDay();
        $endDate = $startDate->copy()->addDays(4); // Próximos 5 días (hoy + 4 días)

        // Generar las fechas dentro del rango sin turnos
        $days = collect();
        for ($currentDay = $startDate; $currentDay <= $endDate; $currentDay->addDay()) {
            $days->push([
                'date' => $currentDay->copy(),
                'day_of_week' => $this->getDayName($currentDay->dayOfWeek),
                'is_today' => $currentDay->isToday(),
                'shifts' => collect() // Vacío porque no hay turnos
            ]);
        }

        return $days;
    }
    function getDayName($index) {
        switch ($index) {
            case 0: return "Domingo";
            case 1: return "Lunes";
            case 2: return "Martes";
            case 3: return "Miércoles";
            case 4: return "Jueves";
            case 5: return "Viernes";
            case 6: return "Sábado";
            default: return "Índice no válido";
        }
    }
}
