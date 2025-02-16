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
            "section_id" => auth()->user()->section->id,
        ];

        $schedule=Schedule::create($data);
        $data['id'] = $schedule->id;
        $worker_preferences = [
            [
                "user_id" => auth()->user()->id,
                "holidays" => json_encode([
                    Carbon::now()->startOfMonth()->toDateTimeString(),  // Primer día del mes actual
                    Carbon::now()->addDay()->startOfMonth()->toDateTimeString()  // Segundo día del mes actual
                ]),
            ],
            [
                "user_id" => 2,
                "holidays" => json_encode([
                    Carbon::now()->addDays(4)->startOfMonth()->toDateTimeString(),  // Día 5 del mes actual
                    Carbon::now()->addDays(5)->startOfMonth()->toDateTimeString()   // Día 6 del mes actual
                ])
            ],
            [
                "user_id" => 3,
                "holidays" => json_encode([
                    Carbon::now()->addDays(2)->startOfMonth()->toDateTimeString(),  // Día 3 del mes actual
                    Carbon::now()->addDays(3)->startOfMonth()->toDateTimeString()   // Día 4 del mes actual
                ])
            ]
        ];
        foreach ($worker_preferences as $worker_preference) {
            WorkerPreference::create($worker_preference);
        }
        $data['usersJSON'] =json_encode($worker_preferences);


        $month = (Carbon::now()->month+1)%12;  // Obtener el mes actual
        $year = Carbon::now()->year;    // Obtener el año actual

        for ($i = 1; $i <= 5; $i++) {
            // Crear el turno de mañana
            Shift::factory()->create([
                'schedule_id' => $schedule->id,
                'start' => Carbon::createFromFormat('Y-m-d H:i:s', "$year-$month-$i 09:00:00")->toDateTimeString(),
                'end' => Carbon::createFromFormat('Y-m-d H:i:s', "$year-$month-$i 15:00:00")->toDateTimeString(),
            ]);

            // Crear el turno de tarde
            Shift::factory()->create([
                'schedule_id' => $schedule->id,
                'start' => Carbon::createFromFormat('Y-m-d H:i:s', "$year-$month-$i 15:00:00")->toDateTimeString(),
                'end' => Carbon::createFromFormat('Y-m-d H:i:s', "$year-$month-$i 21:00:00")->toDateTimeString(),
            ]);

            // Crear el turno de noche
            Shift::factory()->create([
                'schedule_id' => $schedule->id,
                'start' => Carbon::createFromFormat('Y-m-d H:i:s', "$year-$month-$i 21:00:00")->toDateTimeString(),
                'end' => Carbon::createFromFormat('Y-m-d H:i:s', "$year-$month-$i 04:00:00")->toDateTimeString(),
            ]);
        }
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
                'message' => "Nuevo horario {$schedule->name} disponible. ($data[status])",
                'url' => "/horario/{$schedule->id}",
                'read' => false,
                'tipo' => 'normal',
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
}
