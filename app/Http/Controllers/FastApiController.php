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
            "company_id" => auth()->user()->company->id,
        ];


        $schedule=Schedule::create($data);
        $data['id']=$schedule->id;
        $data['usersJSON'] = json_encode([
            [
                'user_id' => '1',
                'request' => [
                    'holidays' => [1]
                ]
            ],
            [
                'user_id' => '2',
                'request' => [
                    'holidays' => [2]
                ]
            ],
            [
                'user_id' => '3',
                'request' => [
                    'holidays' => [3]
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
            "scheduleJSON"=>["required"],
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
