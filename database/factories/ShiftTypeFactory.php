<?php

namespace Database\Factories;

use App\Models\Schedule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ShiftType>
 */
class ShiftTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('2025-05-01 06:00:00', '2025-05-01 14:00:00');
        $end = (clone $start)->modify('+8 hours');

        return [
            'schedule_id' => Schedule::inRandomOrder()->first()?->id, // fallback
            'notes' => $this->faker->optional()->sentence(),
            'start' => $start,
            'end' => $end,
            'users_needed' => $this->faker->numberBetween(1, 5),
            'period' => $this->faker->numberBetween(1, 7), // Ej: cada X días
            'weekends_excepted' => $this->faker->boolean(30), // 30% probabilidad de excluir fines de semana
        ];
    }

    public static function createMultiples()
    {

    }
}
