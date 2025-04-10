<?php

namespace App\Http\Controllers;
use App\Models\Section;
use App\Models\Shift;
use Illuminate\Support\Facades\DB;

class ScheduleStatsController
{
    public function index(Section $section)
    {
        return view('estadisticashorario', compact('section'));
    }

    // obtenemos el numero de usuarios necesarios para cubrir los turnos de cada dia para la seccion especificada
    public function getDemandPerDay($sectionId, $month, $year)
    {

        $data = Shift::select(DB::raw('DATE(start) as day'), DB::raw('SUM(users_needed) as total'))
            ->whereMonth('start', $month)
            ->whereYear('start', $year)
            ->whereHas('schedule', function ($query) use ($sectionId) {
                $query->where('section_id', $sectionId);
            })
            ->groupBy(DB::raw('DATE(start)'))
            ->orderBy('day')
            ->get();

        return response()->json($data);
    }

    // obtenemos el numero de peticiones aceptadas de vacaciones de cada dia para la seccion especificada
    public function getHolidays($sectionId, $month, $year)
    {

        $holidays = DB::table('holidays')
            ->join('holidays_user', 'holidays.id', '=', 'holidays_user.holidays_id')
            ->join('users', 'holidays_user.user_id', '=', 'users.id')
            ->where('users.section_id', $sectionId)
            ->where('estado', 'Accepted')
            ->whereMonth('dia_vacaciones', $month)
            ->whereYear('dia_vacaciones', $year)
            ->select(DB::raw("DATE(dia_vacaciones) as day"), DB::raw('count(*) as total'))
            ->groupBy(DB::raw("DATE(dia_vacaciones)"))
            ->orderBy('day')
            ->get();

        return response()->json($holidays);
    }

    // obtenemos los 4 peores y mejores dias del mes en cuanto a peticiones sin resolver de vacaciones para la seccion especificada
    public function getPendingHolidays($sectionId, $month, $year)
    {
        $today = now()->startOfDay(); // usamos la fecha actual para en caso de empate poner el dia mas cercano a la fecha actual

        $holidays = DB::table('holidays')
            ->join('holidays_user', 'holidays.id', '=', 'holidays_user.holidays_id')
            ->join('users', 'holidays_user.user_id', '=', 'users.id')
            ->where('users.section_id', $sectionId)
            ->where('estado', 'Pending')
            ->whereMonth('dia_vacaciones', $month)
            ->whereYear('dia_vacaciones', $year)
            ->select(
                DB::raw("DATE(dia_vacaciones) as day"),
                DB::raw('COUNT(*) as total'),
                DB::raw("ABS(JULIANDAY(dia_vacaciones) - JULIANDAY('$today')) as diff") // para obtener el dia mas cercano en caso de empate
            )
            ->groupBy('day')
            ->orderBy('total', 'desc')
            ->orderBy('diff', 'asc')
            ->get();

        // mejores dias del mes (menos solicitudes)
        $mejores = collect($holidays)
            ->sortBy(['total', 'diff'])
            ->take(4);

        // peores dias del mes (mas solicitudes)
        $peores = collect($holidays)
            ->sortByDesc('total')
            ->sortBy('diff')
            ->take(4);

        return response()->json($mejores->merge($peores)->unique('day')->values());
    }


}
