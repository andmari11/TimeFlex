<?php

//BORRAR PORQUE NO SE UTILIZA
namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Validation\Rules\Password;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('users.register');
    }

    public function store()
    {
        //TODO comprobar que sea admin de la empresa

        $attributesUser = request()->validate([
            'name'       => ['required'],
            'email'      => ['required', 'email', 'unique:users,email'],
            'password'   => ['required', Password::min(6), 'confirmed'],
            'role'       => ['required'],

        ]);

        $attributesUser_defaults=[
            "company_id"=> auth()->user()->company->id
        ];

        $attributesUser=array_merge($attributesUser, $attributesUser_defaults);

        $user = User::create($attributesUser);

        Auth::login($user);

        return redirect('/menu');
    }


    public function destroy()
    {
        return view('users.register');
    }

}
