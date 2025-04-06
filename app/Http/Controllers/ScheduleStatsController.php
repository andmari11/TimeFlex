<?php

namespace App\Http\Controllers;
use App\Models\Section;
class ScheduleStatsController
{
    public function index(Section $section)
    {
        return view('estadisticashorario', compact('section'));
    }

}
