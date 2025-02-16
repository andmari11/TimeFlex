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
        $scheduleData = $this->prepareScheduleData($id);

        return view('schedules/single-schedule-view', $scheduleData);
    }
    public function showPersonal($id)
    {
        $scheduleData = $this->preparePersonalScheduleData($id);
        $nextShift = $scheduleData['shifts']->first(function ($shift) {
            return Carbon::parse($shift['start'])->isAfter(now());
        });

        return view('schedules/schedule-personal-view', array_merge($scheduleData, compact('nextShift')));
    }

    public function showPersonalShift($id_schedule, $id_shift)
    {
        $scheduleData = $this->preparePersonalScheduleData($id_schedule);
        $nextShift = $scheduleData['schedule']->shifts->find($id_shift);

        return view('schedules/schedule-personal-view', array_merge($scheduleData, compact('nextShift')));
    }

    private function preparePersonalScheduleData($id)
    {
        BrowserHistoryController::add('Horario personal', url()->current());

        $schedule = Schedule::findOrFail($id);
        $user = auth()->user();

        $shifts = $schedule->shifts->filter(fn($shift) => in_array($user->id, $shift->users->pluck('id')->toArray()));

        $firstShift = $schedule->shifts->first();
        $month = $firstShift ? Carbon::parse($firstShift['start'])->startOfMonth() : Carbon::now()->startOfMonth();

        $startOfCalendar = $month->copy()->startOfMonth()->startOfWeek(Carbon::MONDAY);
        $endOfCalendar = $month->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);

        $days = collect();
        for ($currentDay = $startOfCalendar; $currentDay <= $endOfCalendar; $currentDay->addDay()) {
            $hasShift = $shifts->some(fn($shift) => $currentDay->between(
                Carbon::parse($shift->start)->startOfDay(),
                Carbon::parse($shift->end)->endOfDay()
            ));

            $days->push([
                'date' => $currentDay->copy(),
                'day_of_week' => $currentDay->dayOfWeek,
                'is_current_month' => $currentDay->month === $month->month,
                'is_passed' => $currentDay->isBefore(now()->startOfDay()),
                'is_working_day' => !$currentDay->isWeekend() && $hasShift,
                'shifts' => $shifts->filter(fn($shift) => Carbon::parse($shift->start)->isSameDay($currentDay))
            ]);
        }

        return compact('schedule', 'user', 'shifts', 'days');
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

    public function showShift($id_schedule, $id_shift)
    {
        $scheduleData = $this->prepareScheduleData($id_schedule);
        $shiftToView = $scheduleData['schedule']->shifts->find($id_shift);

        return view('schedules/single-schedule-shift-view', array_merge($scheduleData, compact('shiftToView')));
    }

    private function prepareScheduleData($id)
    {
        $schedule = Schedule::findOrFail($id);

        // Determinar el mes del primer turno (shift)
        $firstShift = collect($schedule->shifts)->first();
        $month = $firstShift ? Carbon::parse($firstShift['start'])->startOfMonth() : Carbon::now()->startOfMonth();

        // Guardar en el historial del navegador
        BrowserHistoryController::add("Horario " . $schedule->section->name, url()->current());

        // Ajustar para que el calendario comience el lunes anterior
        $startOfCalendar = $month->copy()->startOfMonth()->startOfWeek(Carbon::MONDAY);
        $endOfCalendar = $month->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);

        // Generar las fechas dentro del rango
        $days = collect();
        for ($currentDay = $startOfCalendar; $currentDay <= $endOfCalendar; $currentDay->addDay()) {
            $days->push([
                'date' => $currentDay->copy(),
                'day_of_week' => $currentDay->dayOfWeek,
                'is_current_month' => $currentDay->month === $month->month,
            ]);
        }

        return [
            'schedule' => $schedule,
            'days' => $days,
            'user' => auth()->user()
        ];
    }

}
