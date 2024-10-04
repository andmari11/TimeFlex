<?php

namespace App\Http\Controllers;

use App\Models\Company;
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

    public function storeCompany()
    {
        $validatedData = request()->validate([
            "companyName" => ["required", "string", "max:255"]
        ]);
        //Company tiene atributo name no companyName
        $attributesCompany['name'] = $validatedData['companyName'];
        $company=Company::create($attributesCompany);

        $attributesUser = request()->validate([
            'name'       => ['required'],
            'email'      => ['required', 'email', 'unique:users,email'],
            'password'   => ['required', Password::min(6), 'confirmed']
        ]);

        $attributesUser_defaults=[
            "role"=> 'admin',
            "company"=> $company->get('id')
        ];
        $attributesUser=array_merge($attributesUser, $attributesUser_defaults);
        $user = User::create($attributesUser);

        Auth::login($user);

        return redirect('/');
    }
}
