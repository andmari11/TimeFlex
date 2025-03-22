<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Question;
use App\Models\Result;
use App\Models\Section;
use App\Models\Shift;
use App\Models\ShiftExchange;
use App\Models\User;
use App\Models\UserForms;
use Illuminate\Support\Facades\Log;
use App\Models\WorkerPreference;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class StatsController
{
    public function getEmployeesPerSection()
    {
        $sections = DB::table('users')
            ->select('section_id', DB::raw('count(*) as total'))
            ->groupBy('section_id')
            ->get();

        return response()->json($sections);
    }

    public function getShiftsHours()
    {
        $user = auth()->id();
        $shifts = DB::table('shift_user')
            ->join('shifts', 'shifts.id', '=', 'shift_user.shift_id')
            ->where('shift_user.user_id',  $user)
            ->selectRaw("
            strftime('%Y', shifts.start) as year,
            strftime('%m', shifts.start) as month,
            ROUND(sum((julianday(shifts.end) - julianday(shifts.start)) * 24)) as hours
        ")
            ->groupBy('year', 'month')
            ->get();

        if ($shifts->isEmpty()) {
            return response()->json(['hours' => []]);
        }

        return response()->json(['hours' => $shifts]);
    }

    public function getShiftsHoursPerSection2025()
    {
        $sections = Section::with('users.shifts')->get();
        $results = [];

        foreach ($sections as $section) {
            $totalHours = 0; // inicializar horas totales de la seccion a 0

            foreach ($section->users as $user) {
                foreach ($user->shifts as $shift) {
                    $start = Carbon::parse($shift->start);
                    $end = Carbon::parse($shift->end);

                    if ($end->lessThan($start)) {
                        $end->addDay();
                    }

                    // solo turnos del 025
                    if ($start->year == 2025 && $end->year == 2025) {
                        $hours = $start->diffInHours($end);
                        $totalHours += $hours; // Sumar las horas al total de la sección
                    }
                }
            }

            $results[$section->name] = $totalHours;
        }

        return response()->json($results);
    }

    public function SatisfactionPerSectionPerMonth()
    {
        $results = DB::table('satisfactions')
            ->join('schedules', 'satisfactions.schedule_id', '=', 'schedules.id')
            ->join('sections', 'schedules.section_id', '=', 'sections.id')
            ->selectRaw(
                "sections.name as section_name,
            strftime('%Y-%m', schedules.start_date) as month,
            AVG(satisfactions.score) as average_score"
            )
            ->groupBy('section_name', 'month')
            ->get();
        
        $finalResults = [];
        foreach ($results as $result) {
            if (!isset($finalResults[$result->section_name])) {
                $finalResults[$result->section_name] = [];
            }
            $finalResults[$result->section_name][$result->month] = round($result->average_score, 2);
        }

        return response()->json($finalResults);
    }




    public function getSatisfaccion()
    {
        $user = auth()->id();
        $section = $user->section();

        $satisfaccionUsuario = [];
        $satisfaccionSeccion = [];

        // Iterar sobre cada mes del año
        for ($month = 1; $month <= 12; $month++) {
            // Obtener los horarios que comienzan en este mes
            $schedules = Schedule::whereMonth('start_date', $month)->pluck('id');

            // Media de satisfacción de la sección para este mes
            $seccionAvg = Satisfaction::whereIn('schedule_id', $schedules)
                ->whereHas('user', function ($query) use ($sectionId) {
                    $query->where('section_id', $sectionId);
                })
                ->avg('score');

            // Satisfacción del usuario específico en este mes
            $usuarioAvg = Satisfaction::whereIn('schedule_id', $schedules)
                ->where('user_id', $userId)
                ->avg('score');

            $satisfaccionSeccion[] = round($seccionAvg ?? 0, 2);
            $satisfaccionUsuario[] = round($usuarioAvg ?? 0, 2);
        }

        return response()->json([
            'satisfaccion_seccion' => $satisfaccionSeccion,
            'satisfaccion_usuario' => $satisfaccionUsuario
        ]);
    }


}
