<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FastApiController extends Controller
{
    public function sendData(){
        $data=[
            "name" => "hola",
            "section_id" => auth()->user()->section->id,
        ];

        $schedule=Schedule::create($data);
        $data['id']=$schedule->id;
        $data['usersJSON'] = json_encode([
            [
                'user_id' => '111',
                'request' => [
                    'holidays' => [0, 1]
                ]
            ],
            [
                'user_id' => '222',
                'request' => [
                    'holidays' => [5, 6]
                ]
            ],
            [
                'user_id' => '333',
                'request' => [
                    'holidays' => [3, 4]
                ]
            ]
        ]);
        $data['shiftsJSON'] = json_encode([
            [
                'day' => '2024-11-06',
                'shifts' => [
                    [
                        'time' => '09:00-14:00',
                        'start' => '2024-11-06T09:00:00Z',
                        'end' => '2024-11-06T14:00:00Z',
                        'assigned_users' => [],
                        'users_needed' => 2
                    ]
                ]
            ],
            [
                'day' => '2024-11-07',
                'shifts' => [
                    [
                        'time' => '09:00-14:00',
                        'start' => '2024-11-07T09:00:00Z',
                        'end' => '2024-11-07T14:00:00Z',
                        'assigned_users' => [],
                        'users_needed' => 2
                    ]
                ]
            ],
            [
                'day' => '2024-11-08',
                'shifts' => [
                    [
                        'time' => '09:00-14:00',
                        'start' => '2024-11-08T09:00:00Z',
                        'end' => '2024-11-08T14:00:00Z',
                        'assigned_users' => [],
                        'users_needed' => 2
                    ]
                ]
            ],
            [
                'day' => '2024-11-09',
                'shifts' => [
                    [
                        'time' => '09:00-14:00',
                        'start' => '2024-11-09T09:00:00Z',
                        'end' => '2024-11-09T14:00:00Z',
                        'assigned_users' => [],
                        'users_needed' => 2
                    ]
                ]
            ],
            [
                'day' => '2024-11-10',
                'shifts' => [
                    [
                        'time' => '09:00-14:00',
                        'start' => '2024-11-10T09:00:00Z',
                        'end' => '2024-11-10T14:00:00Z',
                        'assigned_users' => [],
                        'users_needed' => 2
                    ]
                ]
            ],
            [
                'day' => '2024-11-11',
                'shifts' => [
                    [
                        'time' => '09:00-14:00',
                        'start' => '2024-11-11T09:00:00Z',
                        'end' => '2024-11-11T14:00:00Z',
                        'assigned_users' => [],
                        'users_needed' => 2
                    ]
                ]
            ],
            [
                'day' => '2024-11-12',
                'shifts' => [
                    [
                        'time' => '09:00-14:00',
                        'start' => '2024-11-12T09:00:00Z',
                        'end' => '2024-11-12T14:00:00Z',
                        'assigned_users' => [],
                        'users_needed' => 2
                    ]
                ]
            ]
        ]);


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

        $schedule = Schedule::findOrFail($data['id']);
        if($schedule){
            if(!isset($data['scheduleJSON'])){
                $schedule->update([
                    'status' => $data['status']
                ]);
            }
            else{
                $schedule->update([
                    'scheduleJSON' => json_encode($data['scheduleJSON']),
                    'status' => $data['status']
                ]);
            }

            return response()->json(['message' => 'Datos recibidos y guardados correctamente'], 200);
        }

        return response()->json(['message' => 'Se ha producido un error'], 404);
    }
}
