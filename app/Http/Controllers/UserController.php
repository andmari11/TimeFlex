<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function create()
    {
        return view('auth.register');
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


        return redirect('/shifts');
    }

    public function update(User $user, Request $request)
    {
        $attributesUser = $request->validate([
            'name'       => ['required'],
            'email'      => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password'   => ['nullable', Password::min(6), 'confirmed'], // Permitir que la contraseña sea opcional
            'role'       => ['required'],
            'company_id' => ['required'], // Asegúrate de que esto sea necesario
            'id' => ['required'],
        ]);

        $user->name = $attributesUser['name'];
        $user->email = $attributesUser['email'];

        if (!empty($attributesUser['password'])) {
            $user->password = bcrypt($attributesUser['password']);
        }
        $user->role = $attributesUser['role'];
        $user->company_id = $attributesUser['company_id']; // Si necesitas esto
        $user->save();

        return redirect('/shifts')->with('success', 'Información actualizada con éxito.');
    }

    public function destroy()
    {
        return view('auth.register');
    }

}
