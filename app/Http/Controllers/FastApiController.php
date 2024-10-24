<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FastApiController extends Controller
{
    public function sendData(){
        $data=[
            "parametro1" => "sanford coño",
        ];
        $response = Http::post(config('services.fastApi.url') . 'api/', $data);

        return $response->json();
    }
    public function receiveData(){

        $data=request()->validate([
            "name" => ["required", "string", "max:255"]
        ]);
        $company=Company::create($data);
        return response()->json(['message' => 'Datos recibidos y guardados correctamente'], 200);

    }
}
