<?php

namespace Database\Factories;

use App\Models\ExpectedHours;
use App\Models\Section;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExpectedHours>
 */
class ExpectedHoursFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
        ];
    }

    public static function createMultiples(){
        $defaultMorning = 80;
        $defaultAfternoon = 60;
        $defaultNight = 50;
        $months = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio'
        ];
        $year = now()->year;
        Section::all()->each(function ($section) use ($months, $year, $defaultMorning, $defaultAfternoon, $defaultNight) {
            $section->users->each(function ($user) use ($section, $months, $year, $defaultMorning, $defaultAfternoon, $defaultNight) {
                foreach ($months as $month) {
                    ExpectedHours::updateOrCreate(
                        [
                            'user_id' => $user->id,
                            'month' => $month,
                            'year' => $year,
                        ],
                        [
                            'section_id' => $section->id,
                            'morning_hours' => $defaultMorning,
                            'afternoon_hours' => $defaultAfternoon,
                            'night_hours' => $defaultNight,
                        ]
                    );
                }
            });
        });
    }
}
