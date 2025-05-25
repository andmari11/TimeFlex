<?php

namespace Database\Factories;

use App\Models\Form;
use App\Models\Question;
use App\Models\Weight;
use App\Models\Schedule;
use App\Models\Section;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Form>
 */
class FormFactory extends Factory
{
    /**
     * Define el estado por defecto del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Obtener el primer usuario con el rol de administrador
        $adminUser = User::all()->where('role', 'admin')->first();

        return [
            'title' => $this->faker->sentence(3),
            'summary' => $this->faker->paragraph(),
            'start_date' => $this->faker->dateTimeBetween('-1 month', '+1 month'),
            'end_date' => $this->faker->dateTimeBetween('+1 month', '+2 months'),
            'id_user' => $adminUser ? $adminUser->id : null, // Asignar el usuario administrador
        ];
    }

    /**
     * Método estático para crear formularios relacionados con horarios y otros formularios.
     */
    public static function createMultiples()
    {
        foreach (Schedule::all() as $schedule) {
            $formulario = Form::create([
                'title' => "Formulario para el horario {$schedule->name}",
                'summary' => "Este formulario está relacionado con el horario {$schedule->name}.",
                'start_date' => $schedule->start_date,
                'end_date' => $schedule->end_date,
                'id_user' => User::all()->where('role', 'admin')->first()->id, // Usuario administrador
            ]);

            $formulario->sections()->attach($schedule->section_id);


            $preguntaTipo4 = Question::create([
                'id_form' => $formulario->id,
                'title' => "Pregunta de tipo 4 para el formulario {$formulario->title}",
                'id_question_type' => 4,
            ]);

            Weight::create([
                'id_question' => $preguntaTipo4->id,
                'value' => rand(1, 10),
            ]);

            $preguntaTipo5 = Question::create([
                'id_form' => $formulario->id,
                'title' => "Pregunta de tipo 5 para el formulario {$formulario->title}",
                'id_question_type' => 5,
            ]);

            Weight::create([
                'id_question' => $preguntaTipo5->id,
                'value' => rand(1, 10),
            ]);
        }

        foreach (range(1, 10) as $i) {
            $formulario = Form::create([
                'title' => "Formulario genérico $i",
                'summary' => "Este es un formulario genérico sin preguntas de tipo 4 ni 5.",
                'start_date' => Carbon::now()->addDays($i),
                'end_date' => Carbon::now()->addDays($i + 10),
                'id_user' => User::all()->where('role', 'admin')->first()->id, // Usuario administrador
            ]);

            // Buscar la sección cuyo nombre coincida con el título del formulario
            $sectionName = "Sección genérica $i"; // Ajusta el nombre de la sección según el título del formulario
            $section = Section::where('name', $sectionName)->first();
            if ($section) {
                $formulario->sections()->attach($section->id);
            }
            if (!$section) {
                $section = Section::inRandomOrder()->first();
            }

            foreach (range(1, 5) as $j) {
                Question::create([
                    'id_form' => $formulario->id,
                    'title' => "Pregunta genérica $j del formulario {$formulario->title}",
                    'id_question_type' => fake()->randomElement(array_diff(range(1, 9), [4, 5])), // Excluir 4 y 5
                ]);
            }
        }
    }
}
