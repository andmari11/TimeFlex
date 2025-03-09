<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BrowserHistoryController extends Controller
{
    public static function add(string $titulo, string $link){
        $historial = session()->get('historial_accesos', []);

        if (empty($historial) || !collect($historial)->contains('titulo', $titulo)) {            
            array_unshift($historial, [
                'titulo' => $titulo,
                'link'   => $link
            ]);
        }

        $historial = array_slice($historial, 0, 4);

        session(['historial_accesos' => $historial]);
    }
}
