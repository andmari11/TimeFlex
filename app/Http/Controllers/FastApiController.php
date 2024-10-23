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
            "parametro1" => "nombreEmpresa",
        ];
        $response = Http::post('http://0.0.0.0:8000/api/', $data);


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
