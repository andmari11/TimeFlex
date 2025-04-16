<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Holidays;
use App\Models\Schedule;
use App\Models\Shift;
use App\Models\User;
use App\Models\Section;
use App\Models\ExpectedHours;
use App\Models\QuestionType;
use App\Models\Satisfaction;
use App\Models\Notification;
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
            'weight' => 5,
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

        for ($mes = 1; $mes <= 12; $mes++) {
            for ($seccion = 1; $seccion <= 3; $seccion++) {
                // creamos 1 o 2 schedules por sección y mes
                $numSchedules = rand(1, 2);
                for ($i = 0; $i < $numSchedules; $i++) {
                    // ponemos valores por defecto de inicio y final de los schedules
                    $diaInicio = rand(1, 15);
                    $startDate = Carbon::create(2025, $mes, $diaInicio);
                    $endDate = $startDate->copy()->addDays(rand(5, 12));
                    Schedule::create([
                        'section_id' => $seccion,
                        'name' => fake()->sentence(3),
                        'description' => fake()->optional()->paragraph(),
                        'status' => 'not_optimized',
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                    ]);
                }
            }
        }

        for ($mes = 1; $mes <= 12; $mes++) {
            $diasEnMes = Carbon::create(2025, $mes, 1)->daysInMonth;

            for ($dia = 1; $dia <= $diasEnMes; $dia++) {
                foreach (Section::all() as $section) {
                    $fecha = Carbon::create(2025, $mes, $dia);

                    // turno mañana
                    Shift::factory()->create([
                        'start' => $fecha->copy()->setTime(9, 0),
                        'end' => $fecha->copy()->setTime(15, 0),
                    ]);

                    // turno tarde
                    Shift::factory()->create([
                        'start' => $fecha->copy()->setTime(15, 0),
                        'end' => $fecha->copy()->setTime(21, 0),
                    ]);

                    // turno noche
                    Shift::factory()->create([
                        'start' => $fecha->copy()->setTime(21, 0),
                        'end' => $fecha->copy()->addDay()->setTime(4, 0),
                    ]);
                }
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

            $schedules = Schedule::all();
            foreach ($users as $usuario) {
                for ($mes = 1; $mes <= 12; $mes++) {
                    // buscamos schedules que comiencen en ese mes
                    $schedulesDelMes = $schedules->filter(function ($schedule) use ($mes) {
                        return Carbon::parse($schedule->start_date)->month === $mes;
                    });
                    if ($schedulesDelMes->isNotEmpty()) {
                        // elegimos 1 o 2 schedules  del mes
                        $satisfactionsACrear = $schedulesDelMes->random(min(rand(1, 2), $schedulesDelMes->count()));
                        foreach ($satisfactionsACrear as $schedule) {
                            Satisfaction::create([
                                'user_id' => $usuario->id,
                                'schedule_id' => $schedule->id,
                                'score' => rand(2, 10),
                            ]);
                        }
                    }
                }
            }



        }

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
        foreach ($users as $user) {
            $numberHolidays = rand(55,60);
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
        QuestionType::create([ 'name' => 'Calendario', 'description' => 'Pregunta basada en una fecha seleccionable mediante un calendario.' ]);
        QuestionType::create([ 'name' => 'Selector', 'description' => 'Pregunta con múltiples opciones seleccionables a través de un menú desplegable.' ]);
        QuestionType::create([ 'name' => 'Gradual', 'description' => 'Pregunta con una escala gradual para evaluar.' ]);
        QuestionType::create([ 'name' => 'Turnos', 'description' => 'Pregunta para obtener información para los turnos deseados.' ]);
        QuestionType::create([ 'name' => 'Vacaciones', 'description' => 'Pregunta para obtener información para las vacaciones.' ]);
        QuestionType::create(['name' => 'Texto Libre', 'description' => 'Pregunta que permite ingresos de texto sin restricciones predefinidas.']);
        QuestionType::create(['name' => 'Opción Múltiple', 'description' => 'Pregunta en la que el usuario puede elegir una o varias opciones mediante casillas de verificación.']);
        QuestionType::create(['name' => 'Numérica', 'description' => 'Pregunta que exige como respuesta un valor numérico.']);
        QuestionType::create(['name' => 'Carga de Archivo', 'description' => 'Pregunta que permite adjuntar un archivo en la respuesta.']);

        // prueba para ver si funciona bien shift distribution
        $usuarioPrueba = User::factory()->create([
            'name' => 'prueba',
            'email' => 'prueba@prueba.com',
            'password' => Hash::make('password'),
            'company_id' => 1,
            'role' => 'employee',
            'section_id' => 2,
        ]);
        $fechaInicio = Carbon::create(2025, 4, 14);
        $fechaFinal = Carbon::create(2025, 4, 16);
        $turnoEspecial = Shift::factory()->create([
            'start' => $fechaInicio->copy()->setTime(9, 0),
            'end' => $fechaFinal->copy()->setTime(16, 0),
        ]);
        DB::table('shift_user')->insert([
            'user_id' => $usuarioPrueba->id,
            'shift_id' => $turnoEspecial->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        $defaultMorning = 80;
        $defaultAfternoon = 60;
        $defaultNight = 50;
        $months = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];
        $year = now()->year;
        Section::all()->each(function ($section) use ($months, $year, $defaultMorning, $defaultAfternoon, $defaultNight) {
            $section->users->each(function ($user) use ($section, $months, $year, $defaultMorning, $defaultAfternoon, $defaultNight) {
                foreach ($months as $month) {
                    ExpectedHours::updateOrCreate(
                        [
                            'user_id' => $user->id,
                            'month' => $month,
                            'year' => $year,
                        ],
                        [
                            'section_id' => $section->id,
                            'morning_hours' => $defaultMorning,
                            'afternoon_hours' => $defaultAfternoon,
                            'night_hours' => $defaultNight,
                        ]
                    );
                }
            });
        });

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

        Notification::create([
            'user_id' => 11,
            'tipo' => 'ayuda',
            'message' => 'Has solicitado ayuda a Administración',
            'email' => 'usuario11@example.com',
            'nombre' => 'Juan',
            'apellidos' => 'Pérez',
            'duda' => '¿Cómo cambio mi contraseña?',
            'read' => false,
        ]);

        Notification::create([
            'user_id' => 11,
            'tipo' => 'turno',
            'message' => 'Tu turno del jueves ha sido actualizado',
            'email' => 'usuario11@example.com',
            'nombre' => 'Juan',
            'apellidos' => 'Pérez',
            'duda' => null,
            'read' => false,
        ]);

        Notification::create([
            'user_id' => 11,
            'tipo' => 'sistema',
            'message' => 'Nueva actualización del sistema disponible',
            'email' => 'usuario11@example.com',
            'nombre' => 'Juan',
            'apellidos' => 'Pérez',
            'duda' => null,
            'read' => true,
        ]);
    }
}
