<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Schedule;
use App\Models\Shift;
use App\Models\User;
use App\Models\Section;
use App\Models\QuestionType;
use App\Models\Satisfaction;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //crea 1 empresa
        Company::factory(config('const.seeder.companies'))->create();
        //crea 3 secciones, admin y sin seccion
        Section::factory(1)->create([
            'name'  => "Administradores",
            "company_id"=> 1,
            'default'=>true
        ]);
        Section::factory(1)->create([
            'id' => 0,
            'name' => 'Sin sección',
            'company_id' => 1,
            'default'=>true
        ]);
        Section::factory(config('const.seeder.sections'))->create();
        //crea 10 trabajadores
        User::factory(config('const.seeder.employees'))->create();

        //crea 1 admin
        User::factory(1)->create([
            'name' => fake()->name(),
            'email' => "admin@admin.com",
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'company_id' => 1,
            'role' => 'admin',
            'section_id' => 1
        ]);

        //crea 1 user
        User::factory(1)->create([
            'name' => fake()->name(),
            'email' => "user@user.com",
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'company_id' => 1,
            'role' => 'employee',
            'section_id' => 0
        ]);
        Schedule::factory(2)->create();

        for ($i = 0; $i < 12; $i++) {
            for($j = 1; $j < 4; $j++){
                $startDate = Carbon::create(2025, $i + 1, 1);
                $endDate = $startDate->copy()->endOfMonth();

                Schedule::create([
                    'section_id' => $j,
                    'name' => fake()->sentence(3),
                    'description' => fake()->optional()->paragraph(),
                    'status' => 'not_optimized',
                    'start_date' => $startDate,
                    'end_date' => $endDate
                ]);
            }
        }
        for($j = 0; $j < 3; $j++){ //Antes 40
            for ($i = 0; $i < 5; $i++) {
                Shift::factory()->create([
                    'start' => now()->addDays($j * 7 + $i)->setTime(9, 0),
                    'end' => now()->addDays($j * 7 + $i)->setTime(15, 0),
                ]);

                Shift::factory()->create([
                    'start' => now()->addDays($j * 7 + $i)->setTime(15, 0),
                    'end' => now()->addDays($j * 7 + $i)->setTime(21, 0),
                ]);

                Shift::factory()->create([
                    'start' => now()->addDays($j * 7 + $i)->setTime(21, 0),
                    'end' => now()->addDays($j * 7 + $i + 1)->setTime(4, 0),
                ]);
            }
        }
        $users = User::all();
        $shifts = Shift::all();

        foreach ($users as $user) {
            // entre 25 y 38 turnos aleatorios para cada usuario (unicos)
            $numberShifts = rand(25,38);
            $assignedShifts = $shifts->random($numberShifts)->pluck('id');
            // asignacion de turnos
            foreach ($assignedShifts as $shiftId) {
                DB::table('shift_user')->insert([
                    'user_id' => $user->id,
                    'shift_id' => $shiftId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            $usedNumbers = [];

            for ($i = 0; $i < 5; $i++) {
                do {
                    $numero = rand(1, 30);
                } while (in_array($numero, $usedNumbers));

                $usedNumbers[] = $numero;
                Satisfaction::create([
                    'user_id' => $user->id,
                    'schedule_id' => $numero,
                    'score' => rand(3,10)
                ]);
            }
        }
        QuestionType::create([ 'name' => 'Calendario', 'description' => 'Pregunta basada en una fecha seleccionable mediante un calendario.' ]);
        QuestionType::create([ 'name' => 'Selector', 'description' => 'Pregunta con múltiples opciones seleccionables a través de un menú desplegable.' ]);
        QuestionType::create([ 'name' => 'Gradual', 'description' => 'Pregunta con una escala gradual para evaluar.' ]);
        QuestionType::create([ 'name' => 'Turnos', 'description' => 'Pregunta para obtener información para los turnos deseados.' ]);
        QuestionType::create([ 'name' => 'Vacaciones', 'description' => 'Pregunta para obtener información para las vacaciones.' ]);
        QuestionType::create(['name' => 'Texto Libre', 'description' => 'Pregunta que permite ingresos de texto sin restricciones predefinidas.']);
        QuestionType::create(['name' => 'Opción Múltiple', 'description' => 'Pregunta en la que el usuario puede elegir una o varias opciones mediante casillas de verificación.']);
        QuestionType::create(['name' => 'Numérica', 'description' => 'Pregunta que exige como respuesta un valor numérico.']);
        QuestionType::create(['name' => 'Carga de Archivo', 'description' => 'Pregunta que permite adjuntar un archivo en la respuesta.']);
    }
}
