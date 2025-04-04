<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Section;
use App\Models\ExpectedHours;

class ExpectedHoursTable extends Component
{
    public $sections;
    public $currentMonth;
    public $currentYear;
    public $expectedHours;

    public function __construct()
    {
        $this->sections = Section::all(); // Para el select
        $this->currentMonth = now()->month;
        $this->currentYear = now()->year;
        $defaultSection = Section::where('name', 'Administradores')->first();
        $defaultSectionId = $defaultSection?->id;

        $this->expectedHours = ExpectedHours::where('section_id', $defaultSectionId)
            ->where('month', $this->currentMonth)
            ->where('year', $this->currentYear)
            ->with('user')
            ->get();

        $this->defaultSectionId = $defaultSectionId;
    }

    public function render()
    {
        return view('sections.expectedhourstable', [
            'defaultSectionId' => $this->defaultSectionId,
        ]);
    }
}
