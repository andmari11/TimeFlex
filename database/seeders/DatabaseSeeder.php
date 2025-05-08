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
use App\Models\UserNotificationsPreference;
use Database\Factories\ExpectedHoursFactory;
use Database\Factories\HolidaysFactory;
use Database\Factories\NotificationFactory;
use Database\Factories\ScheduleFactory;
use Database\Factories\SectionFactory;
use Database\Factories\ShiftExchangesFactory;
use Database\Factories\ShiftFactory;
use Database\Factories\ShiftTypeFactory;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use Carbon\Carbon;
use Database\Factories\FormFactory;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //crea 1 empresa
        Company::factory(config('const.seeder.companies'))->create();

        SectionFactory::createMultiples();

        UserFactory::createMultiples();

        ScheduleFactory::createMultiples();

        ShiftFactory::createMultiples();

        $users = User::all();
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

        QuestionType::create(['name' => 'Calendario', 'description' => 'Pregunta basada en una fecha seleccionable mediante un calendario.']);
        QuestionType::create(['name' => 'Selector', 'description' => 'Pregunta con múltiples opciones seleccionables a través de un menú desplegable.']);
        QuestionType::create(['name' => 'Gradual', 'description' => 'Pregunta con una escala gradual para evaluar.']);
        QuestionType::create(['name' => 'Turnos', 'description' => 'Pregunta para obtener información para los turnos deseados.']);
        QuestionType::create(['name' => 'Vacaciones', 'description' => 'Pregunta para obtener información para las vacaciones.']);
        QuestionType::create(['name' => 'Texto Libre', 'description' => 'Pregunta que permite ingresos de texto sin restricciones predefinidas.']);
        QuestionType::create(['name' => 'Opción Múltiple', 'description' => 'Pregunta en la que el usuario puede elegir una o varias opciones mediante casillas de verificación.']);
        QuestionType::create(['name' => 'Numérica', 'description' => 'Pregunta que exige como respuesta un valor numérico.']);
        QuestionType::create(['name' => 'Carga de Archivo', 'description' => 'Pregunta que permite adjuntar un archivo en la respuesta.']);

        FormFactory::createMultiples();
        HolidaysFactory::createMultiples();
        NotificationFactory::createMultiples();
        ExpectedHoursFactory::createMultiples();
        ShiftExchangesFactory::CreateMultiples();


    }
}
