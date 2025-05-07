<?php

namespace Database\Factories;

use App\Models\Schedule;
use App\Models\Section;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'section_id' => 2,
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->optional()->paragraph(),
            'status' => 'not_optimized'
        ];
    }

    public static function createMultiples()
    {
        $secciones = Section::all()->where('default', false);
        $meses = [
            'Abril' => ['start' => '2025-04-01 00:00:00', 'end' => '2025-04-30 23:59:00'],
            'Mayo'  => ['start' => '2025-05-01 00:00:00', 'end' => '2025-05-31 23:59:00']
        ];
        foreach ($secciones as $seccion) {
            foreach ($meses as $mes => $rango) {
                $schedule = Schedule::create([
                    'section_id' => $seccion->id,
                    'name' => "$seccion->name $mes",
                    'description' => "Horario de la sección " . $seccion->nombre . " para el mes de $mes",
                    'status' => 'not_optimized',
                    'start_date' => Carbon::parse($rango['start']),
                    'end_date' => Carbon::parse($rango['end']),
                ]);

            }
        }
    }
}
