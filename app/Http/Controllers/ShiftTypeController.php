<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Shift;
use App\Models\ShiftType;
use App\Models\User;
use Illuminate\Http\Request;

class ShiftTypeController extends Controller
{
    //
    public function create($schedule_id)
    {
        $schedule=Schedule::findOrFail($schedule_id);
        return view('schedules.register-shiftype', compact('schedule'));
    }

    public function store($schedule_id)
    {
        // Validar los atributos del horario
        $attributesSchedule = request()->validate([

            'notes' => ['required'],
            'start' => 'required|date',
            'end' => 'required|date|after:start_date',
            'users_needed' => 'required|numeric',
            'period' => 'required|numeric',
            'weekends_excepted' => 'boolean',
            'workers' => 'array',
            'workers.*' => 'exists:users,id',
            ], [        ], [
            'notes.required' => 'El campo notas es obligatorio',
            'start.required' => 'El campo inicio es obligatorio',
            'end.required' => 'El campo fin es obligatorio',
            'end.after' => 'La fecha de fin debe ser posterior a la fecha de inicio',
            'users_needed.required' => 'El campo usuarios necesarios es obligatorio',
            'users_needed.numeric' => 'El campo usuarios necesarios debe ser numérico',
            'period.required' => 'El campo periodo es obligatorio',
            'period.numeric' => 'El campo periodo debe ser numérico',
            'workers.array' => 'El campo trabajadores debe ser un array',
            'workers.*.exists' => 'El trabajador seleccionado no es válido',
        ]);

        if (isset($attributesSchedule['workers']) && $attributesSchedule['users_needed'] !== null && count($attributesSchedule['workers']) > $attributesSchedule['users_needed']) {
            return back()->withErrors(['workers' => 'El número de trabajadores asignados no puede ser mayor que el número de trabajadores necesarios.']);
        }
        $attributesSchedule['weekends_excepted'] = request()->has('weekends_excepted');


        // Combinar el nuevo ID, company_id y los atributos validados
        $attributesSchedule = array_merge($attributesSchedule, [
            'schedule_id' => $schedule_id,
        ]);

        // Crear un nuevo horario con los atributos combinados
        $shiftType = ShiftType::create($attributesSchedule);

        // Asignar los trabajadores al nuevo horario
        if (isset($attributesSchedule['workers'])) {
            foreach ($attributesSchedule['workers'] as $worker) {
                $shiftType->users()->attach(User::findOrFail($worker));
            }
        }


        self::generateShifts($shiftType);


        // Redirigir al menú principal
        return redirect('/horario/'.$schedule_id.'/edit');
    }

    public function edit($id, $id_st)
    {
        $schedule = Schedule::findOrFail($id);
        $shiftType = ShiftType::findOrFail($id_st);
        return view('schedules.edit-shiftype', compact('shiftType', 'schedule'));
    }
    public function update($id, $id_st)
    {
        // Validar los atributos del horario
        $attributesSchedule = request()->validate([

            'notes' => ['required'],
            'start' => 'required|date',
            'end' => 'required|date|after:start_date',
            'users_needed' => 'required|numeric',
            'period' => 'required|numeric',
            'weekends_excepted' => 'boolean',
            'workers' => 'array',
            'workers.*' => 'exists:users,id',
        ], [], [
            'notes.required' => 'El campo notas es obligatorio',
            'start.required' => 'El campo inicio es obligatorio',
            'end.required' => 'El campo fin es obligatorio',
            'end.after' => 'La fecha de fin debe ser posterior a la fecha de inicio',
            'users_needed.required' => 'El campo usuarios necesarios es obligatorio',
            'users_needed.numeric' => 'El campo usuarios necesarios debe ser numérico',
            'period.required' => 'El campo periodo es obligatorio',
            'period.numeric' => 'El campo periodo debe ser numérico',
            'workers.array' => 'El campo trabajadores debe ser un array',
            'workers.*.exists' => 'El trabajador seleccionado no es válido',
        ]);

        if (isset($attributesSchedule['workers']) && $attributesSchedule['users_needed'] !== null && count($attributesSchedule['workers']) > $attributesSchedule['users_needed']) {
            return back()->withErrors(['workers' => 'El número de trabajadores asignados no puede ser mayor que el número de trabajadores necesarios.']);
        }


        $shiftType = ShiftType::findOrFail($id_st);

        $shiftType->update($attributesSchedule);

        return redirect('/horario/'.$id.'/edit');
    }
    public function destroy($id, $id_st)
    {
        $shiftType = ShiftType::findOrFail($id_st);
        $shiftType->delete();

        return redirect('/horario/'.$id.'/edit');
    }
    public static function generateShifts($shiftType)
    {
        $schedule = Schedule::findOrFail($shiftType->schedule_id);

        if (!$schedule->start_date || !$schedule->end_date) {
            throw new \Exception("El schedule no tiene fechas de inicio y fin definidas.");
        }

        $startDate = \Carbon\Carbon::parse($schedule->start_date);
        $endDate = \Carbon\Carbon::parse($schedule->end_date);
        $shiftStart = \Carbon\Carbon::parse($shiftType->start);
        $shiftEnd = \Carbon\Carbon::parse($shiftType->end);
        $period = $shiftType->period;
        $weekendsExcepted = $shiftType->weekends_excepted;

        if($period==0){
            $currentDate = \Carbon\Carbon::parse($shiftType->start);
            $endDate = \Carbon\Carbon::parse($shiftType->end);
        }
        else{
            $currentDate = $startDate->copy();
        }

        while ($currentDate->lte($endDate)) {
            if ($weekendsExcepted && $currentDate->isWeekend()) {
                $currentDate->addDay();
                continue;
            }

            $shift = Shift::create([
                'schedule_id'   => $schedule->id,
                'notes'         => $shiftType->notes,
                'start'         => $currentDate->copy()->setTimeFrom($shiftStart),
                'end'           => $currentDate->copy()->setTimeFrom($shiftEnd),
                'users_needed'  => $shiftType->users_needed,
                'type'          => $shiftType->id,
            ]);

            $shift ->users()->attach($shiftType->users);

            switch ($period) {
                case 0: // Único
                    return;
                case 1: // Diario
                    $currentDate->addDay();
                    break;
                case 2: // Semanal
                    $currentDate->addWeek();
                    break;
                case 3: // Mensual
                    $currentDate->addMonth();
                    break;
                case 4: // Anual
                    $currentDate->addYear();
                    break;
                default:
                    throw new \Exception("Periodicidad no válida.");
            }
        }
    }

}
