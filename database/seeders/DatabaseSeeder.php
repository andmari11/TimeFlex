<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Schedule;
use App\Models\Shift;
use App\Models\User;
use App\Models\Section;
use App\Models\QuestionType;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
