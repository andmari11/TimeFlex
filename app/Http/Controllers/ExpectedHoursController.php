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
            'section_id' => 'required|exists:sections,id',
            'month' => 'required',
            'year' => 'required|integer',
            'morning_hours' => 'required|integer|min:0',
            'afternoon_hours' => 'required|integer|min:0',
            'night_hours' => 'required|integer|min:0',
        ]);

        // cambiamos el mes de numero a string con la primera letra en mayusc
        $monthNumber = (int) $validated['month'];
        $validated['month'] = ucfirst(Carbon::create()->locale('es')->month($monthNumber)->monthName);

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
        $sectionId = $request->get('section_id');
        // conversion mes entero a string
        $monthNumber = (int) $request->get('month');
        $month = ucfirst(\Carbon\Carbon::create()->locale('es')->month($monthNumber)->monthName);
        $year = now()->year;
        $query = ExpectedHours::with('user')->where('month', $month)->where('year', $year);

        if ($sectionId !== 'all') {
            $query->where('section_id', $sectionId);
        }

        return response()->json($query->get());
    }






}
