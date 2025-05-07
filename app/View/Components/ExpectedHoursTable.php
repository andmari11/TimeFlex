<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Section;
use App\Models\ExpectedHours;

class ExpectedHoursTable extends Component
{
    public $section;
    public $sections;
    public $defaultSectionId;
    public $currentMonth;
    public $currentYear;
    public $expectedHours;

    public function __construct(Section $section = null, $sections = [], $defaultSectionId = null)
    {
        $this->section = $section;
        $this->sections = $sections;

        // si no viene seccion por defecto, se asigna 0 (todas)
        $this->defaultSectionId = $defaultSectionId ?? 0;
        $this->currentMonth = now()->month;
        $this->currentYear  = now()->year;
    }

    public function render()
    {
        return view('sections.expectedhourstable');
    }
}
