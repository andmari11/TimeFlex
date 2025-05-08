<?php

namespace Database\Factories;

use App\Models\Holidays;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Holidays>
 */
class HolidaysFactory extends Factory
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

    public static function createMultiples()
    {

        $states = ['Accepted', 'Accepted', 'Accepted', 'Pending', 'Pending']; // pongo mas accepted para que salgan mas
        // generacion de vacaciones para cada mes de 2025
        for ($mes = 1; $mes <= 12; $mes++) {
            for ($i = 0; $i < 150; $i++) {
                $dia = rand(1, Carbon::create(2025, $mes, 1)->daysInMonth);
                $dia_vacaciones = Carbon::create(2025, $mes, $dia);
                Holidays::create([
                    'fecha_solicitud' => now(),
                    'dia_vacaciones' => $dia_vacaciones,
                    'estado' => $states[array_rand($states)],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        // asignacion de vacaciones a los usuarios
        $holidays = Holidays::all();
        $users = User::all();
        foreach ($users as $user) {
            $numberHolidays = rand(55, 60);
            $allHolidays = $holidays->random($numberHolidays)->pluck('id');
            foreach ($allHolidays as $holidayId) {
                DB::table('holidays_user')->insert([
                    'holidays_id' => $holidayId,
                    'user_id' => $user->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
