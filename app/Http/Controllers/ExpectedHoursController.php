<?php

namespace App\Http\Controllers;

use App\Models\ExpectedHours;
use App\Models\User;
use Illuminate\Http\Request;
class ExpectedHoursController
{
    // guarda/actualiza las horas esperadas trabajadas de un usuario
    public function storeOrUpdate(Request $request)
    {
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
        $sectionId = $request->input('section_id');
        $month = $request->input('month');

        $expectedHours = ExpectedHours::where('section_id', $sectionId)
            ->where('month', $month)
            ->with('user:id,name')
            ->get();

        return response()->json($expectedHours);
    }
}
