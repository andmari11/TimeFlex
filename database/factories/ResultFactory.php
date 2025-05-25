<?php

namespace Database\Factories;

use App\Models\File;
use App\Models\Form;
use App\Models\Question;
use App\Models\Result;
use App\Models\Schedule;
use App\Models\ShiftType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Result>
 */
class ResultFactory extends Factory
{
    /**
     * El modelo asociado a esta factoría.
     *
     * @var string
     */
    protected $model = Result::class;

    /**
     * Define el estado por defecto del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $question = Question::inRandomOrder()->first();
        $form = $question ? $question->form : Form::inRandomOrder()->first();
        $user = User::inRandomOrder()->first();
        $schedule = Schedule::inRandomOrder()->first();

        return [
            'id_question' => $question ? $question->id : null,
            'respuesta' => $this->generateResponse($question, $schedule), // Generar respuesta basada en el tipo de pregunta y el horario
            'id_question_type' => $question ? $question->id_question_type : null,
            'id_user' => $user ? $user->id : null,
            'id_form' => $form ? $form->id : null,
            'id_schedule' => $schedule ? $schedule->id : null,
        ];
    }

    private function generateResponse($question, $schedule)
    {
        if (!$question) {
            return null;
        }

        switch ($question->id_question_type) {
            case 1:
                return $this->faker->date();
            case 2:
                return $this->faker->word();
            case 3:
                return $this->faker->numberBetween(1, 10);
            case 4:
                return ShiftType::where('schedule_id', $schedule->id)->inRandomOrder()->first()?->id;
            case 5:
                return $this->faker->date();
            case 6:
                return $this->faker->sentence();
            case 7:
                return implode(',', $this->faker->words(rand(1, 3)));
            case 8:
                return $this->faker->randomNumber(2);
            case 9:
                $file = File::inRandomOrder()->first();
                if (!$file) {
                    $file = File::factory()->create();
                }

                return $file->id;
            default:
                return $this->faker->word();
        }
    }

    public static function createMultiples()
    {
        $forms = Form::all();

        foreach ($forms as $form) {
            $questions = $form->questions;
            $users = User::inRandomOrder()->take(rand(5, 10))->get();

            foreach ($users as $user) {
                foreach ($questions as $question) {
                    $schedule = Schedule::inRandomOrder()->first();

                    Result::create([
                        'id_question' => $question->id,
                        'respuesta' => (new self)->generateResponse($question, $schedule),
                        'id_question_type' => $question->id_question_type,
                        'id_user' => $user->id,
                        'id_form' => $form->id,
                        'id_schedule' => $schedule->id,
                    ]);
                }
            }
        }
    }
}
