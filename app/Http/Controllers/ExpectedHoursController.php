<?php

namespace App\Http\Controllers;

use App\Models\ExpectedHours;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
class ExpectedHoursController
{
    // guarda/actualiza las horas esperadas trabajadas de un usuario
    public function storeOrUpdate(Request $request){
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'month' => 'required|string',
            'year' => 'required|integer',
            'morning_hours' => 'required|integer|min:0',
            'afternoon_hours' => 'required|integer|min:0',
            'night_hours' => 'required|integer|min:0',
        ]);

        $expectedHour = ExpectedHours::updateOrCreate(
            [
                'user_id' => $validated['user_id'],
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
        $sectionId = $request->get('section_id');
        $monthNumber = $request->get('month');
        $month = \Carbon\Carbon::create()->month($monthNumber)->locale('es')->monthName;
        $month = ucfirst($month); // ponemos la primera letra en mayusc
        $year = now()->year;

        $expectedHours = ExpectedHours::with('user')
            ->where('section_id', $sectionId)
            ->where('month', $month)
            ->where('year', $year)
            ->get();

        return response()->json($expectedHours);
    }


}
