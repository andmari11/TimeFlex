<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Schedule;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FastApiController extends Controller
{
    public function sendData(){
        $data=[
            "name" => "hola",
            "section_id" => 2,
        ];

        $schedule=Schedule::create($data);
        $data['id'] = $schedule->id;
        $data['usersJSON'] = json_encode(
            [
                [
                    "user_id" => 1,
                    "holidays" => [
                        date('Y-m-d H:i:s', strtotime('2024-12-01')),  // 1 de diciembre de 2024
                        date('Y-m-d H:i:s', strtotime('2024-12-02'))   // 2 de diciembre de 2024
                    ]
                ],
                [
                    "user_id" => 2,
                    "holidays" => [
                        date('Y-m-d H:i:s', strtotime('2024-12-05')),  // 5 de diciembre de 2024
                        date('Y-m-d H:i:s', strtotime('2024-12-06'))   // 6 de diciembre de 2024
                    ]
                ],
                [
                    "user_id" => 3,
                    "holidays" => [
                        date('Y-m-d H:i:s', strtotime('2024-12-03')),  // 3 de diciembre de 2024
                        date('Y-m-d H:i:s', strtotime('2024-12-04'))   // 4 de diciembre de 2024
                    ]
                ]
            ]
        );
        for ($i = 0; $i < 5; $i++) {
            // Crear el turno de mañana
            Shift::factory()->create([
                'schedule_id' => $schedule->id,  // Añadir el schedule_id aquí
                'start' => now()->addDays($i)->setTime(9, 0),
                'end' => now()->addDays($i)->setTime(15, 0),
            ]);

            // Crear el turno de tarde
            Shift::factory()->create([
                'schedule_id' => $schedule->id,  // Añadir el schedule_id aquí
                'start' => now()->addDays($i)->setTime(15, 0),
                'end' => now()->addDays($i)->setTime(21, 0),
            ]);

            // Crear el turno de noche
            Shift::factory()->create([
                'schedule_id' => $schedule->id,  // Añadir el schedule_id aquí
                'start' => now()->addDays($i)->setTime(21, 0),
                'end' => now()->addDays($i + 1)->setTime(4, 0),
            ]);
        }
        $data['shiftsJSON'] = json_encode($schedule->shifts);
        try{
            $response = Http::timeout(5)->post(config('services.fastApi.url') . 'api/', $data);
            if ($response->failed()) {
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
    public function receiveData(){

        $data=request()->validate([
            "id"=>"required",
            "scheduleJSON"=>"array",
            "status"=>"required",
        ]);

        $schedule = Schedule::find($data['id']);
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
}
