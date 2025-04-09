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

}
