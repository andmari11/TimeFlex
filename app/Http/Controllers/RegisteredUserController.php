<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store()
    {
        $attributes = request()->validate([
            'name' => ['required'],
            'email'      => ['required', 'email'],
            'password'   => ['required', Password::min(6), 'confirmed']
        ]);

        $attributes_defaults=[
            "role"=> 'admin',
            "company"=>null
        ];
        $attributes=array_merge($attributes, $attributes_defaults);
        $user = User::create($attributes);

        Auth::login($user);

        return redirect('/');
    }
}
