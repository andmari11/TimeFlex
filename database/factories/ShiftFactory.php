<?php

namespace Database\Factories;

use App\Models\Schedule;
use App\Models\Shift;
use App\Models\User;
use Carbon\Carbon;
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

    public static function createMultiples(){
        $shifts = [
            ['start' => '09:00:00', 'end' => '15:00:00'],
            ['start' => '15:00:00', 'end' => '21:00:00'],
            ['start' => '21:00:00', 'end' => '04:00:00'],
        ];
        foreach (Schedule::all() as $schedule) {
            $inicioHorario = Carbon::parse($schedule->start_date);
            $finHorario = Carbon::parse($schedule->end_date);
            for ($dia = $inicioHorario->copy(); $dia->lte($finHorario); $dia->addDay()) {
                foreach ($shifts as $turno) {
                    $inicioTurno = Carbon::parse($dia->format('Y-m-d') . ' ' . $turno['start']);
                    $finTurno = Carbon::parse($dia->format('Y-m-d') . ' ' . $turno['end']);

                    if ($finTurno->lt($inicioTurno)) {
                        $finTurno->addDay();
                    }

                    if ($inicioTurno->lt($schedule->start_date) || $finTurno->gt($schedule->end_date)) {
                        continue;
                    }

                    $shift = Shift::create([
                        'schedule_id' => $schedule->id,
                        'start' => $inicioTurno,
                        'end' => $finTurno,
                        'users_needed' => rand(1, 4),
                        'type' => rand(0, 2),
                        'notes' => null,
                    ]);

                    $trabajadores = User::all()->where('section_id', $schedule->section_id);

                    if ($trabajadores and $trabajadores->count() > 0) {
                        for($i = 0; $i < $shift->users_needed; $i++){
                            $trabajadorAleatorio = $trabajadores->random();
                            $shift->users()->attach($trabajadorAleatorio->id);
                        }
                        $shift->save();
                    }
                }
            }
        }
    }

}
