<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Section;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class CompanyController extends Controller
{
    public function create()
    {
        return view('auth.register-company');
    }
    public function store()
    {
        //valdiamos datos de ususario y empresa
        $validatedData = request()->validate([
            "companyName" => ["required", "string", "max:255"]
        ]);
        $attributesUser = request()->validate([
            'name'       => ['required'],
            'email'      => ['required', 'email', 'unique:users,email'],
            'password'   => ['required', Password::min(6), 'confirmed']
        ]);


        //Company tiene atributo name no companyName
        $attributesCompany['name'] = $validatedData['companyName'];

        //creamos company y recibimos model company (necesitamos su id)
        $company=Company::create($attributesCompany);
        $adminSection= Section::create([
            'name'  => "Administradores",
            "company_id"=> $company->id,
        ]);
        $sinSeccion= Section::create([
            'name'  => "Sin sección",
            "company_id"=> $company->id,
            'default'=>true
        ]);
        $attributesUser_defaults=[
            "role"=> 'admin',
            "company_id"=> $company->id,
            "section_id"=> $adminSection->id,
            'default'=>true
        ];
        $attributesUser=array_merge($attributesUser, $attributesUser_defaults);

        $user = User::create($attributesUser);

        Auth::login($user);
        return redirect('/menu');
    }
}
