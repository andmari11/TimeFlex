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


}
