<?php

namespace Database\Factories;

use App\Models\Schedule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shift>
 */
class ShiftFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $shifts = [
            ['start' => '09:00:00', 'end' => '15:00:00'],
            ['start' => '15:00:00', 'end' => '21:00:00'],
            ['start' => '21:00:00', 'end' => '04:00:00'],
        ];
        $shift = $this->faker->randomElement($shifts);
        $date = now()->addDays($this->faker->numberBetween(1, 5));

        return [
            'schedule_id' => rand(1, 38),
            'notes' => $this->faker->optional()->sentence(),
            'start' => $date->copy()->setTimeFromTimeString($shift['start']),
            'end' => $shift['end'] === '04:00:00' ?
                $date->copy()->addDay()->setTimeFromTimeString($shift['end']) :
                $date->copy()->setTimeFromTimeString($shift['end']),
            'users_needed' => $this->faker->numberBetween(1, 3),
            'type' => $this->faker->numberBetween(0, 2),
        ];


    }
}
