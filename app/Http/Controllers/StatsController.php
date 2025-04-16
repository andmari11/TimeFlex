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
use App\Models\ExpectedHours;
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

    public function getTotalEmployees()
    {
        $total = DB::table('users')->count();
        return response()->json(['total_employees' => $total]);
    }
    public function getTotalShiftHours()
    {
        $users = User::with('shifts')->get();
        $totalHours = 0;

        foreach ($users as $user) {
            foreach ($user->shifts as $shift) {
                $start = Carbon::parse($shift->start);
                $end = Carbon::parse($shift->end);

                $hours = $start->diffInHours($end);
                $totalHours += $hours;
            }
        }

        return response()->json(['total_shift_hours' => $totalHours]);
    }

    public function getShiftDistribution($id)
    {
        $userId = intval($id);
        $franjas = [
            '9:00 - 15:00' => 0,
            '15:00 - 21:00' => 0,
            '21:00 - 04:00' => 0
        ];

        $shifts = DB::table('shift_user')
            ->join('shifts', 'shifts.id', '=', 'shift_user.shift_id')
            ->where('shift_user.user_id', $userId)
            ->whereYear('shifts.start', now()->year)
            ->whereMonth('shifts.start', now()->month)
            ->get(['shifts.start', 'shifts.end']);

        foreach ($shifts as $shift) {
            $inicio = Carbon::parse($shift->start);
            $fin = Carbon::parse($shift->end);
            if ($fin->lessThan($inicio)) {
                $fin->addDay();
            }
            // aqui llevamos la cuenta de los tipos de turno ya contados por dia
            $marcados = [];
            while ($inicio < $fin) {
                $hora = $inicio->hour;
                if ($hora >= 9 && $hora < 15) {
                    $tipo = '9:00 - 15:00';
                    $dia = $inicio->copy()->startOfDay();
                } elseif ($hora >= 15 && $hora < 21) {
                    $tipo = '15:00 - 21:00';
                    $dia = $inicio->copy()->startOfDay();
                } else {
                    $tipo = '21:00 - 04:00';
                    // entre medianoche y 08:59 ponemos que pertenece al turno de noche del dia anterior
                    $dia = $hora >= 21
                        ? $inicio->copy()->startOfDay()
                        : $inicio->copy()->subDay()->startOfDay();
                }
                // clave unica para no contar varias veces el mismo tipo de turno en el dia
                $clave = $tipo . '_' . $dia->toDateString();
                if (!in_array($clave, $marcados)) {
                    $franjas[$tipo]++;
                    $marcados[] = $clave;
                }

                $inicio->addHour();
            }
        }

        return response()->json($franjas);
    }



    public function getSatisfaccion($userId, $sectionId)
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

    public function getActualVsExpected($id)
    {
        $year = now()->year;
        $month = now()->month;

        // obtenemos los datos esperados (horas trabajadas) y si no existen, inicializamos a 0
        $monthName = ucfirst(\Carbon\Carbon::create()->month($month)->locale('es')->monthName);
        $expected = \App\Models\ExpectedHours::where('user_id', $id)
            ->where('month', $monthName)
            ->where('year', $year)
            ->first();
        $expectedData = $expected ? [
            'morning' => $expected->morning_hours,
            'afternoon' => $expected->afternoon_hours,
            'night' => $expected->night_hours,
        ] : [
            'morning' => 0,
            'afternoon' => 0,
            'night' => 0,
        ];

        // obtenemos los datos reales de horas trabajadas en el mes
        $shifts = DB::table('shift_user')
            ->join('shifts', 'shifts.id', '=', 'shift_user.shift_id')
            ->where('shift_user.user_id', $id)
            ->whereYear('shifts.start', $year)
            ->whereMonth('shifts.start', $month)
            ->select('shifts.start', 'shifts.end')
            ->get();

        $workedData = [
            'morning' => 0,
            'afternoon' => 0,
            'night' => 0,
        ];

        foreach ($shifts as $shift) {
            $start = Carbon::parse($shift->start);
            $end = Carbon::parse($shift->end);
            if ($end->lessThan($start)) {
                $end->addDay();
            }
            $hours = $start->diffInHours($end);
            $hour = (int) $start->format('H');
            if ($hour >= 9 && $hour < 15) {
                $workedData['morning'] += $hours;
            } elseif ($hour >= 15 && $hour < 21) {
                $workedData['afternoon'] += $hours;
            } else {
                $workedData['night'] += $hours;
            }
        }

        return response()->json([
            'expected' => $expectedData,
            'worked' => $workedData,
        ]);
    }

    // obtenemos los cambios de turno aceptados vs solicitados para cada mes para el usuario
    public function getUserShiftExchanges()
    {
        $userId = auth()->id();

        // cambios de turno solicitados por mes
        $solicitadosPorMes = DB::table('shift_exchanges')
            ->join('shifts', 'shift_exchanges.shift_demander_id', '=', 'shifts.id')
            ->selectRaw('strftime("%m", shifts.start) as mes, COUNT(*) as total')
            ->where('shift_exchanges.demander_id', $userId)
            ->groupBy('mes')
            ->pluck('total', 'mes')
            ->toArray();

        // cambios de turno aceptados por mes
        $aceptadosPorMes = DB::table('shift_exchanges')
            ->join('shifts', 'shift_exchanges.shift_demander_id', '=', 'shifts.id')
            ->selectRaw('strftime("%m", shifts.start) as mes, COUNT(*) as total')
            ->where('shift_exchanges.demander_id', $userId)
            ->where('shift_exchanges.status', 'Accepted')
            ->groupBy('mes')
            ->pluck('total', 'mes')
            ->toArray();

        // arrays para almacenar los resultados
        $solicitados = [];
        $aceptados = [];

        // para cada mes guardamos los datos calculados o 0 si no hay
        for ($i = 1; $i <= 12; $i++) {
            $mes = str_pad($i, 2, '0', STR_PAD_LEFT);
            $solicitados[] = $solicitadosPorMes[$mes] ?? 0;
            $aceptados[] = $aceptadosPorMes[$mes] ?? 0;
        }

        return response()->json([
            'solicitados' => $solicitados,
            'aceptados' => $aceptados,
        ]);
    }

    public function getUserHolidaysEvolution()
    {
        $userId = auth()->id();

        // vacaciones solicitadas por mes
        $solicitadasPorMes = DB::table('holidays')
            ->join('holidays_user', 'holidays.id', '=', 'holidays_user.holidays_id')
            ->selectRaw('strftime("%m", holidays.dia_vacaciones) as mes, COUNT(*) as total')
            ->where('holidays_user.user_id', $userId)
            ->groupBy('mes')
            ->pluck('total', 'mes')
            ->toArray();

        // vacaciones aceptadas por mes
        $aceptadasPorMes = DB::table('holidays')
            ->join('holidays_user', 'holidays.id', '=', 'holidays_user.holidays_id')
            ->selectRaw('strftime("%m", holidays.dia_vacaciones) as mes, COUNT(*) as total')
            ->where('holidays_user.user_id', $userId)
            ->where('holidays.estado', 'Accepted')
            ->groupBy('mes')
            ->pluck('total', 'mes')
            ->toArray();

        // arrays para almacenar los resultados
        $solicitadas = [];
        $aceptadas = [];

        // para cada mes guardamos los datos calculados o 0 si no hay
        for ($i = 1; $i <= 12; $i++) {
            $mes = str_pad($i, 2, '0', STR_PAD_LEFT);
            $solicitadas[] = $solicitadasPorMes[$mes] ?? 0;
            $aceptadas[] = $aceptadasPorMes[$mes] ?? 0;
        }

        return response()->json([
            'solicitadas' => $solicitadas,
            'aceptadas' => $aceptadas,
        ]);
    }

    //obtenemos la satisfaccion media del usuario de forma mensual desglosando su distribución de turnos
    public function getMonthlyShiftSatisfaction()
    {
        $userId = auth()->id();
        $tipoTurnosMensual = [];
        $contador = [];

        // obtenemos los turnos del usuario y su horario (inicio - fin)
        $turnos = DB::table('shift_user')
            ->join('shifts', 'shift_user.shift_id', '=', 'shifts.id')
            ->join('schedules', 'shifts.schedule_id', '=', 'schedules.id')
            ->where('shift_user.user_id', $userId)
            ->select('shifts.start', 'shifts.end')
            ->get();

        // recorremos cada turno hora a hora
        foreach ($turnos as $turno) {
            $inicio = \Carbon\Carbon::parse($turno->start);
            $fin = \Carbon\Carbon::parse($turno->end);
            $actual = $inicio->copy();
            $marcados = [];

            //vamos acumulando tipos de turnos comprendidos entre el final e inicio del turno actual
            while ($actual <= $fin) {
                $hora = $actual->hour;
                if ($hora >= 9 && $hora < 15) {
                    $tipo = 'Mañana';
                    $fecha = $actual->copy()->startOfDay();
                } elseif ($hora >= 15 && $hora < 21) {
                    $tipo = 'Tarde';
                    $fecha = $actual->copy()->startOfDay();
                } else {
                    $tipo = 'Noche';
                    // si esta entre 00:00 y 08:59, es del turno de noche del dia anterior
                    $fecha = $hora >= 21
                        ? $actual->copy()->startOfDay()
                        : $actual->copy()->subDay()->startOfDay();
                }

                $claveFechaTipo = $tipo . '_' . $fecha->toDateString();

                // si aun no hemos contado este tipo de turno en este día
                if (!in_array($claveFechaTipo, $marcados)) {
                    // obtenemos el mes y la clave (por ejemplo Tarde_4 -> seria turnos de tarde de abril) y actualizamos el numero de turnos para esa clave
                    $mes = (int) $fecha->format('m');
                    $claveMesTipo = $tipo . '_' . $mes;
                    $contador[$claveMesTipo] = ($contador[$claveMesTipo] ?? 0) + 1;
                    $tipoTurnosMensual[$claveMesTipo] = [
                        'tipo' => $tipo,
                        'mes' => $mes
                    ];
                    // guardamos en el array marcados el tipo de turno del dia que corresponda para tenerlo en cuenta en siguientes iteraciones
                    $marcados[] = $claveFechaTipo;
                }
                // avanzamos 1 hora para ver si entramos en otro tipo de turno
                $actual->addHour();
            }
        }

        // obtenemos la media de satisfacción mensual del usuario
        $satisfaccionPorMes = DB::table('satisfactions')
            ->where('user_id', $userId)
            ->selectRaw('strftime("%m", created_at) as mes, AVG(score) as media')
            ->groupBy('mes')
            ->pluck('media', 'mes')
            ->toArray();

        // ahora construyo la respuesta para Highcharts
        $tipos = ['Mañana', 'Tarde', 'Noche'];
        $series = [];

        foreach ($tipos as $tipo) {
            $datosTipo = [];
            foreach ($tipoTurnosMensual as $clave => $info) {
                // transformamos el mes en un string de 2 digitos y redondeamos la satisfaccion media del mes a 1 decimal
                $mesKey = str_pad($info['mes'], 2, '0', STR_PAD_LEFT);
                $satisfaccionMes = round($satisfaccionPorMes[$mesKey] ?? 5.0, 1);
                if ($info['tipo'] === $tipo) {
                    $datosTipo[] = [
                        'x' => $info['mes'] - 1,
                        'y' => $satisfaccionMes,
                        'z' => $contador[$clave],
                        'name' => $tipo
                    ];
                }
            }

            $series[] = [
                'name' => $tipo,
                'data' => $datosTipo
            ];
        }

        return response()->json($series);
    }

    // funcion que obtiene los datos para comparar la satisfaccion del empleado vs seccion mes a mes
    public function getMonthlySatisfactionComparison()
    {
        $userId = auth()->id();
        $user = User::findOrFail($userId);
        $sectionId = $user->section_id;

        // obtenemos la satisfacción mensual del usuario
        $satisfaccionesUsuario = DB::table('satisfactions')
            ->join('schedules', 'satisfactions.schedule_id', '=', 'schedules.id')
            ->where('satisfactions.user_id', $userId)
            ->selectRaw('strftime("%m", schedules.start_date) as mes, AVG(score) as media')
            ->groupBy('mes')
            ->pluck('media', 'mes');

        // obtenemos la satisfacción mensual de la sección
        $satisfaccionesSeccion = DB::table('satisfactions')
            ->join('schedules', 'satisfactions.schedule_id', '=', 'schedules.id')
            ->join('users', 'satisfactions.user_id', '=', 'users.id')
            ->where('users.section_id', $sectionId)
            ->selectRaw('strftime("%m", schedules.start_date) as mes, AVG(score) as media')
            ->groupBy('mes')
            ->pluck('media', 'mes');

        // creamos arrays para almacenar los resultados de cada mes
        $empleado = [];
        $seccion = [];

        // rellenamos los arrays con la media redondeada a 1 decimal
        for ($i = 1; $i <= 12; $i++) {
            $mes = str_pad($i, 2, '0', STR_PAD_LEFT);
            $empleado[] = round($satisfaccionesUsuario[$mes] ?? 0, 1);
            $seccion[] = round($satisfaccionesSeccion[$mes] ?? 0, 1);
        }

        return response()->json([
            'empleado' => $empleado,
            'seccion' => $seccion
        ]);
    }


}
