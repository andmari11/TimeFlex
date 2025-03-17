<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\ShiftType;
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
        ], [
            'notes.required' => 'El campo notas es obligatorio',
            'start.required' => 'El campo inicio es obligatorio',
            'end.required' => 'El campo fin es obligatorio',
            'end.after' => 'La fecha de fin debe ser posterior a la fecha de inicio',
            'users_needed.required' => 'El campo usuarios necesarios es obligatorio',
            'users_needed.numeric' => 'El campo usuarios necesarios debe ser numérico',
            'period.required' => 'El campo periodo es obligatorio',
            'period.numeric' => 'El campo periodo debe ser numérico',
        ]);

        $schedule = Schedule::findOrFail($schedule_id);
        // Obtener el último ID de la base de datos y sumarle 1
        $lastId = ShiftType::max('id');
        $newId = $lastId + 1;

        // Obtener el schedule_id que se esta haciendo referencia
        $scheduleId = $schedule_id;


        // Combinar el nuevo ID, company_id y los atributos validados
        $attributesSchedule = array_merge($attributesSchedule, [
            'id' => $newId,
            'schedule_id' => $scheduleId,
        ]);

        // Crear un nuevo horario con los atributos combinados
        $shiftType = ShiftType::create($attributesSchedule);

        // Redirigir al menú principal
        return redirect('/horario/'.$schedule_id.'/edit');
    }


}
