<?php

namespace App\Http\Controllers;

use App\Models\ExpectedHours;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
class ExpectedHoursController
{
    // guarda/actualiza las horas esperadas trabajadas de un usuario
    public function storeOrUpdate(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'section_id' => 'required',
            'month' => 'required',
            'year' => 'required|integer',
            'morning_hours' => 'required|integer|min:0',
            'afternoon_hours' => 'required|integer|min:0',
            'night_hours' => 'required|integer|min:0',
        ]);

        // cambiamos el mes de numero a string con la primera letra en mayusc
        $monthNumber = (int) $validated['month'];
        $validated['month'] = ucfirst(Carbon::create()->locale('es')->month($monthNumber)->monthName);

        // en caso de ser todas, obtenemos la seccion del usuario
        if ($validated['section_id'] === 'all') {
            $user = User::find($validated['user_id']);
            // error si no podemos encontrarla
            if (!$user || !$user->section_id) {
                return response()->json(['error' => 'No se puede determinar la sección del usuario.'], 400);
            }
            $validated['section_id'] = $user->section_id;
        }

        $expectedHour = ExpectedHours::updateOrCreate(
            [
                'user_id' => $validated['user_id'],
                'section_id' => $validated['section_id'],
                'month' => $validated['month'],
                'year' => $validated['year'],
            ],
            [
                'morning_hours' => $validated['morning_hours'],
                'afternoon_hours' => $validated['afternoon_hours'],
                'night_hours' => $validated['night_hours'],
            ]
        );

        return response()->json(['success' => true, 'data' => $expectedHour]);
    }

    // pasar las horas por sección para el mes y año
    public function getBySection(Request $request)
    {
        $sectionId   = (int)$request->query('section_id');
        $monthNumber = (int)$request->query('month');
        $year        = now()->year;
        // pasamos el mes de numero a string
        $monthName = ucfirst(Carbon::create()->locale('es')->month($monthNumber)->monthName);

        $q = ExpectedHours::with('user');
        // saltamos sin seccion
        if ($sectionId > 0) {
            $q->where('section_id', $sectionId);
        }
        $result = $q
            ->where('month', $monthName)
            ->where('year', $year)
            ->get();

        return response()->json($result);
    }


}
