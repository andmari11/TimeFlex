<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index(){
        $section=null;
        return view('equipo', compact('section'));
    }

    public function indexAdminTeam($id){
        $section=auth()->user()->company->sections()->where('id', $id)->first();

        if(!$section){
            abort(404);
        }
        return view('equipo', compact('section'));
    }
    //
}
