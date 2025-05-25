<?php

namespace Database\Factories;

use App\Models\Schedule;
use App\Models\ShiftType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ShiftType>
 */
class ShiftTypeFactory extends Factory
{
    public function definition(): array
    {
        $shifts = [
            ['start' => '09:00:00', 'end' => '15:00:00'],
            ['start' => '15:00:00', 'end' => '21:00:00'],
            ['start' => '21:00:00', 'end' => '04:00:00'],
        ];

        $shift = $this->faker->randomElement($shifts);
        $period = $this->faker->numberBetween(0, 4);

        return [
            'schedule_id' => Schedule::inRandomOrder()->first()?->id ?? 1,
            'notes' => $this->faker->optional()->sentence(),
            'start' => $shift['start'],
            'end' => $shift['end'],
            'period' => $period,
            'users_needed' => $this->faker->numberBetween(1, 4),
            'weekends_excepted' => $this->faker->boolean(20),
        ];
    }


    public static function createMultiples(int $turnosPorHorario = 3): void
    {
        $shifts = [
            ['start' => '09:00:00', 'end' => '15:00:00'],
            ['start' => '15:00:00', 'end' => '21:00:00'],
            ['start' => '21:00:00', 'end' => '04:00:00'],
        ];

        foreach (Schedule::all() as $schedule) {
            for ($i = 0; $i < $turnosPorHorario; $i++) {
                $turno = fake()->randomElement($shifts);

                ShiftType::create([
                    'schedule_id' => $schedule->id,
                    'notes' => fake()->optional()->sentence(),
                    'start' => $turno['start'],
                    'end' => $turno['end'],
                    'period' => fake()->numberBetween(0, 4),
                    'users_needed' => fake()->numberBetween(1, 3),
                    'weekends_excepted' => fake()->boolean(30),
                ]);
            }
        }
    }
}
