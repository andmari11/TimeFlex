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
            "scheduleJSON" => [
                ["process" => "initiated"]
            ]
        ];
        $data['scheduleJSON'] = json_encode($data['scheduleJSON']);
        $schedule=Schedule::create($data);
        $data['id']=$schedule->id;
        $response = Http::post(config('services.fastApi.url') . 'api/', $data);
        if ($response->failed()) {
            return redirect('/horario')->withErrors(['message' => 'Error sending data.']);
        }
        return redirect('/horario');

    }
    public function receiveData(){

        $data=request()->validate([
            "id"=>"required",
            "scheduleJSON"=>["required","array"]
        ]);

        $schedule = Schedule::findOrFail($data['id']);
        if($schedule){
            $schedule->update([
                'scheduleJSON' => json_encode($data['scheduleJSON'])
            ]);
            return response()->json(['message' => 'Datos recibidos y guardados correctamente'], 200);
        }

        return response()->json(['message' => 'Se ha producido un error'], 404);
    }
}
