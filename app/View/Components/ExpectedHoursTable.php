<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Section;

class ExpectedHoursTable extends Component
{
    public $sections;
    public $currentMonth;
    public $currentYear;

    public function __construct()
    {
        $this->sections = Section::all(); // Para el select
        $this->currentMonth = now()->month;
        $this->currentYear = now()->year;
    }

    public function render()
    {
        return view('sections.expectedhourstable');
    }
}
