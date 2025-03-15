<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Notification;
use App\Models\Schedule;
use App\Models\Shift;
use App\Models\User;
use App\Models\WorkerPreference;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FastApiController extends Controller
{
    public function sendSchedule(){
        $data=[
            "name" => "hola",
            "section_id" => 1,
        ];

        $schedule=Schedule::create($data);
        $data['id'] = $schedule->id;
        $now = Carbon::now()->addDays(2);
        $day = $now->day;  // Obtener el día actual
        $month = ($now->month)%12;  // Obtener el mes actual
        $year = $now->year;    // Obtener el año actual


        $worker_preferences = [
            [
                "user_id" => 1,
                'form_id' => 123,
                "holidays" => json_encode(["2024-12-01 00:00:00", "2024-12-04 00:00:00", "2024-12-05 00:00:00"]),
                "holidays_weight" => 1,
                "preferred_shift_types" => json_encode([0, 1, 2]),
                "preferred_shift_types_weight" => 1,
                "past_satisfaction" => json_encode([0.5, 0.0, 2.5, 1.0, 3.0])
            ],
            [
                "user_id" => 2,
                'form_id' => 123,
                "holidays" => json_encode(["2024-12-05 00:00:00", "2024-12-03 00:00:00", "2024-12-02 00:00:00"]),
                "holidays_weight" => 1,
                "preferred_shift_types" => json_encode([1, 2]),
                "preferred_shift_types_weight" => 1,
                "past_satisfaction" => json_encode([6.0, 7.5, 8.0, 1.5, 7.0])
            ],
            [
                "user_id" => 3,
                'form_id' => 123,
                "holidays" => json_encode(["2024-12-04 00:00:00", "2024-12-05 00:00:00"]),
                "holidays_weight" => 1,
                "preferred_shift_types" => json_encode([2]),
                "preferred_shift_types_weight" => 1,
                "past_satisfaction" => json_encode([1.5, 9.0, 7.5, 6.0, 8.0])
            ],
            [
                "user_id" => 4,
                'form_id' => 123,
                "holidays" => json_encode(["2024-12-05 00:00:00", "2024-12-01 00:00:00"]),
                "holidays_weight" => 1,
                "preferred_shift_types" => json_encode([0, 1]),
                "preferred_shift_types_weight" => 1,
                "past_satisfaction" => json_encode([7.0, 2.5, 8.0, 1.5, 4.0])
            ]
        ];




        foreach ($worker_preferences as $worker_preference) {
            WorkerPreference::create($worker_preference);
        }
        $data['usersJSON'] =json_encode($worker_preferences);

        $schedule->shifts = json_decode($this->generateShifts($schedule, $year, $month, $day, 5), true);
        $data['shiftsJSON'] = json_encode($schedule->shifts);
        try{
            $response = Http::timeout(5)->post(config('services.fastApi.url') . 'api/schedule', $data);
            if ($response->failed()) {
                dd($response->status(), $response->body());
                return redirect('/horario')->withErrors(['message' => 'Error sending data.']);
            }
        }
         catch (\Illuminate\Http\Client\ConnectionException $e) {
            // errores de conexion
            return redirect('/horario')->withErrors(['message' => 'Request timed out. Please try again later.']);
        }
        catch (\Exception $e) {
            // otro tipos de errores
            return redirect('/horario')->withErrors(['message' => 'Error sending data.']);
        }

        return redirect('/horario');

    }
    public function receiveSchedule(): \Illuminate\Http\JsonResponse
    {
        Log::info('Datos recibidos:', request()->all()); // Verifica lo que está llegando

        $data=request()->validate([
            "id"=>"required",
            "scheduleJSON"=>"array",
            "status"=>"required",
        ]);
        $schedule = Schedule::find($data['id']);
        foreach ($schedule->section->company->admins as $user) {
            Notification::create([
                'user_id' => $user->id,
                'message' => "Nuevo horario {$schedule->name} disponible.",
                'url' => "/horario/{$schedule->id}",
                'read' => false,
                'tipo' => 'normal',
                'shift_exchange_id' => $shiftExchange->id ?? null, // Asegurar que existe
            ]);

        }
        if($schedule) {
            $schedule->update([
                'status' => $data['status']
            ]);
            if (isset($data['scheduleJSON'])) {
                foreach ($data['scheduleJSON'] as $userId => $shiftIds) {
                    $user = User::find($userId);
                    if ($user) {
                        foreach ($shiftIds as $shiftId) {
                            $shift = Shift::find($shiftId);

                            if ($shift) {
                                $shift->users()->attach($user->id);
                            } else {
                                return response()->json(['message' => "Shift con ID {$shiftId} no encontrado."], 404);

                            }
                        }
                    } else {
                        return response()->json(['message' => "Usuario con ID {$userId} no encontrado."], 404);

                    }
                }
            }
            return response()->json(['message' => 'Datos recibidos y guardados correctamente'], 200);
        }
        return response()->json(['message' => 'Se ha producido un error'], 404);
    }

    public static function sendStats(){
        $statsInfo['id']=1;
        try{

            $response = Http::timeout(5)->post(config('services.fastApi.url') . 'api/stats', $statsInfo);

        }
        catch (\Illuminate\Http\Client\ConnectionException $e) {
            // errores de conexion
            return false;
        }
        catch (\Exception $e) {
            // otro tipos de errores
            return false;
        }

        return $response;
    }


    function generateShifts($schedule, $year, $month, $day, $daysToGenerate = 5) {
        $shifts = [];
        $shiftTypes = [
            ['start' => '09:00:00', 'end' => '15:00:00', 'type' => 0], // Mañana
            ['start' => '15:00:00', 'end' => '21:00:00', 'type' => 1], // Tarde
            ['start' => '21:00:00', 'end' => '04:00:00', 'type' => 2], // Noche
        ];

        for ($i = 0; $i < $daysToGenerate; $i++) {
            $currentDate = Carbon::createFromDate($year, $month, $day);

            if ($currentDate->month != $month) {
                break;
            }

            $scheduleId = $schedule->id;

            foreach ($shiftTypes as $shift) {
                $start = Carbon::createFromFormat('Y-m-d H:i:s', "$year-$month-$day {$shift['start']}")->toDateTimeString();

                $end = Carbon::createFromFormat('Y-m-d H:i:s', "$year-$month-$day {$shift['end']}")->toDateTimeString();
                if ($shift['end'] == '04:00:00') {
                    $end = Carbon::createFromFormat('Y-m-d H:i:s', "$year-$month-" . ($day + 1) . " {$shift['end']}")->toDateTimeString();
                }

                $shift = Shift::create([
                    "schedule_id" => $scheduleId,
                    "start" => $start,
                    "end" => $end,
                    "users_needed" => 1,
                    "type" => $shift['type']
                ]);
                $shifts[] = $shift->toArray();
            }
            $day++;

        }

        return json_encode($shifts, JSON_PRETTY_PRINT);
    }
}
