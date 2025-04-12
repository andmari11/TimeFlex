<?php

namespace App\Http\Controllers\Schedules;

use App\Http\Controllers\BrowserHistoryController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\FastApiController;
use App\Http\Controllers\ShiftTypeController;
use App\Models\Schedule;
use App\Models\ShiftType;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Section;
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
        }
        else{
            BrowserHistoryController::add(
                'Horarios personales', url()->current()
            );
            $schedules = auth()->user()->section->schedules->reverse()->map(function ($schedule) {
                return $schedule;
            });

        }

        $schedules = $schedules->isEmpty() ? collect([]) : $schedules->toQuery()->orderBy('created_at', 'desc')->paginate(9);
        return view('horario', compact('schedules'));

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
        $nextShiftMonth = Carbon::parse($nextShift->start)->format('m');
        $currentPage = $scheduleData['calendars']->search(function ($calendar) use ($nextShiftMonth) {
            return $calendar['month_id']==($nextShiftMonth);
        });
        $currentPage=($currentPage+1);
        return view('schedules/schedule-personal-view', array_merge($scheduleData, compact('nextShift', 'currentPage')));
    }

    public static function preparePersonalScheduleData($id)
    {
        BrowserHistoryController::add('Horario personal', url()->current());

        $schedule = Schedule::findOrFail($id);
        $user = auth()->user();

        $shifts = $schedule->shifts->filter(fn($shift) => in_array($user->id, $shift->users->pluck('id')->toArray()));

        $months = $shifts->map(function ($shift) {
            return Carbon::parse($shift['start'])->startOfMonth();
        })->unique()->sort();

        if($months->isEmpty()) {
            $months=[(Carbon::now()->startOfMonth())];
        }

        $firstShift = $schedule->shifts->first();
//        $month = $firstShift ? Carbon::parse($firstShift['start'])->startOfMonth() : Carbon::now()->startOfMonth();

//        $startOfCalendar = $month->copy()->startOfMonth()->startOfWeek(Carbon::MONDAY);
//        $endOfCalendar = $month->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);
        $calendars = collect();
        foreach($months as $month) {
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
            $calendars->push([
                'month_id' => $month->format('m'),
                'month' => self::monthToSpanish($month->format('m')),
                'days' => $days
            ]);
        }

        return compact('schedule', 'user', 'shifts', 'calendars');
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
        $nextShiftMonth = Carbon::parse($shiftToView->start)->format('m');

        $currentPage = $scheduleData['months']->search(function ($calendar) use ($nextShiftMonth) {
            return $calendar['month_id']==($nextShiftMonth);
        });
        $currentPage=($currentPage+1);
        return view('schedules/single-schedule-shift-view', array_merge($scheduleData, compact('shiftToView', 'currentPage')));
    }

    public function showUser($id_schedule, $id_user)
    {
        $scheduleData = $this->prepareScheduleData($id_schedule);
        $userToView = User::findOrFail($id_user);
        $usersShifts = $scheduleData['schedule']->shifts->filter(fn($shift) => in_array($userToView->id, $shift->users->pluck('id')->toArray()));

        return view('schedules/single-schedule-user-view', array_merge($scheduleData, compact('userToView','usersShifts')));
    }

    public static function prepareScheduleData($id)
    {
        $schedule = Schedule::findOrFail($id);
        $shifts = collect($schedule->shifts);

        $months = $shifts->map(function ($shift) {
            return Carbon::parse($shift['start'])->startOfMonth();
        })->unique()->sort();

        // guardar en el historial del navegador
        BrowserHistoryController::add("Horario " . $schedule->section->name, url()->current());

        $calendars = collect();

        foreach ($months as $month) {
            // ajustar para que el calendario comience el lunes anterior
            $startOfCalendar = $month->copy()->startOfMonth()->startOfWeek(Carbon::MONDAY);
            $endOfCalendar = $month->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);

            // generar las fechas dentro del rango
            $days = collect();
            for ($currentDay = $startOfCalendar; $currentDay <= $endOfCalendar; $currentDay->addDay()) {
                $days->push([
                    'date' => $currentDay->copy(),
                    'day_of_week' => $currentDay->dayOfWeek,
                    'is_current_month' => $currentDay->month === $month->month,
                ]);
            }

            $calendars->push([
                'month_id' => $month->format('m'),
                'month' => self::monthToSpanish($month->format('m')),
                'days' => $days
            ]);
         }
        return [
            'schedule' => $schedule,
            'months' => $calendars,
            'user' => auth()->user()
        ];
    }

    public static function monthToSpanish($monthName){
        $monthNameInSpanish = "";
        switch($monthName) {
            case 1:
                $monthNameInSpanish = "Enero";
                break;
            case 2:
                $monthNameInSpanish = "Febrero";
                break;
            case 3:
                $monthNameInSpanish = "Marzo";
                break;
            case 4:
                $monthNameInSpanish = "Abril";
                break;
            case 5:
                $monthNameInSpanish = "Mayo";
                break;
            case 6:
                $monthNameInSpanish = "Junio";
                break;
            case 7:
                $monthNameInSpanish = "Julio";
                break;
            case 8:
                $monthNameInSpanish = "Agosto";
                break;
            case 9:
                $monthNameInSpanish = "Septiembre";
                break;
            case 10:
                $monthNameInSpanish = "Octubre";
                break;
            case 11:
                $monthNameInSpanish = "Noviembre";
                break;
            case 12:
                $monthNameInSpanish = "Diciembre";
                break;
            default:
                $monthNameInSpanish = "Mes inválido";
                break;
        }
        return $monthNameInSpanish;
    }
    public function create()
    {
        $sections = Section::all();
        return view('schedules.register', compact('sections'));
    }
    public function store()
    {
        // TODO: comprobar que sea admin de la empresa

        // Validar los atributos del horario
        $attributesSchedule = request()->validate([
            'name' => ['required', 'unique:schedules,name'],
            'description' => ['required'],
            'section_id' => ['required', 'exists:sections,id'],
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date'
        ], [
            'name.required' => 'Es necesario introducir el nombre del horario a registrar',
            'name.unique' => 'Ya existe un horario con este nombre',
            'description.required' => 'Es necesario introducir una descripción',
            'section_id.required' => 'Es necesario seleccionar una sección',
            'section_id.exists' => 'La sección seleccionada no es válida',
        ]);

        // Obtener el último ID de la base de datos y sumarle 1
        $lastId = Schedule::max('id');
        $newId = $lastId + 1;

        // Obtener el company_id del usuario autenticado
        $companyId = auth()->user()->company->id;

        // Combinar el nuevo ID, company_id y los atributos validados
        $attributesSchedule = array_merge($attributesSchedule, [
            'id' => $newId,
            'company_id' => $companyId,
        ]);

        // Crear un nuevo horario con los atributos combinados
        $schedule = Schedule::create($attributesSchedule);

        // Redirigir al menú principal
        return redirect('/horario/'. $schedule->id . '/edit');
    }

    public function edit($id)
    {
        $sections = Section::all();
        $schedule = Schedule::findOrFail($id);
        $shifttypes = $schedule->shiftTypes;
        return view('schedules.edit', compact('schedule', 'sections', 'shifttypes'));
    }

    public function update($id)
    {
        // Validar los atributos del horario
        request()->validate([
            'name' => ['required'],
            'description' => ['required'],
            'section_id' => ['required', 'exists:sections,id'],
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date'

        ]);

        // Encontrar el horario y actualizarlo
        $schedule = Schedule::findOrFail($id);
        $schedule->update([
            'name' => request('name'),
            'description' => request('description'),
            'section_id' => request('section_id'),
            'start_date' => request('start_date'),
            'end_date' => request('end_date')
        ]);

        // Redirigir al menú principal
        return redirect('/horario/'.$id);


    }

    public function destroy(int $id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();
        return redirect('/horario/'.$id);
    }

    public function regenerateShifts($id)
    {
        $schedule = Schedule::findOrFail($id);

        $schedule->shifts()->delete();
        $schedule->shiftTypes()->each(function ($shiftType) {
            ShiftTypeController::generateShifts($shiftType);
        });
        $schedule->status = 'regenerado';
        $schedule->save();
        return redirect('/horario/'.$id );
    }

}
