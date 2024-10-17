<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Section;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(){
        $section=null;
        return view('menu', compact('section'));
    }

    public function indexAdmin($id){
        $section=auth()->user()->company->sections()->where('id', $id)->first();

        if(!$section){
            abort(404);
        }
        return view('menu', compact('section'));
    }


}
