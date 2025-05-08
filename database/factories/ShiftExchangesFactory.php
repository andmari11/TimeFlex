<?php

namespace Database\Factories;

use App\Models\Shift;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ShiftExchangesFactory extends Factory
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

    public static function CreateMultiples()
    {
        //metemos los ids de users y shifts en arrays
        $users_ids = User::pluck('id')->toArray();
        $shifts_ids = Shift::pluck('id')->toArray();

        // creamos 60 estados y los mezclamos aleatoriamente
        $statusarray = array_merge(
            array_fill(0, 600, 'Accepted'),
            array_fill(0, 400, 'Pending'),
            array_fill(0, 150, 'Declined')
        );
        shuffle($statusarray);

        foreach (range(1, 1150) as $i) {
            DB::table('shift_exchanges')->insert([
                'demander_id' => $demander = fake()->randomElement($users_ids),
                'receiver_id' => $receiver = fake()->randomElement(array_diff($users_ids, [$demander])),
                'shift_demander_id' => fake()->randomElement($shifts_ids),
                'shift_receiver_id' => fake()->randomElement($shifts_ids),
                'reason' => '',
                'status' => $statusarray[$i - 1],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
