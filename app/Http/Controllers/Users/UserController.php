<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Section;
use Illuminate\Validation\Rules\Password;
class UserController extends Controller
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
            'section_id' => ['required'],
        ]);

        $attributesUser_defaults=[
            "company_id"=> auth()->user()->company->id
        ];

        $attributesUser=array_merge($attributesUser, $attributesUser_defaults);

        $user = User::create($attributesUser);

        return redirect('/menu');
    }
    public function edit(int $id){
        $user = User::findOrFail($id);

        return view('users.edit', compact('user'));
    }

    public function update(int $id)
    {
        request()->validate([
            'name'       => ['required'],
            'email'      => ['required', 'email'],
            'password'   => ['nullable', Password::min(6), 'confirmed'],
            'role'       => ['required'],
            'section_id' => ['required'],
        ]);
        $user = User::findOrFail($id);
        $user->update([
            'name' => request('name'),
            'email' => request('email'),
            'section_id' => request('section_id'),

        ]);
        return redirect('/menu');
    }

    public function destroy(int $id)
    {
        die($id);
        $user = User::findOrFail($id);
        $user->delete();
        return redirect('/menu');
    }

    public static function reassignSectionToUnassigned(int $section_id)
    {
        $sectionToDelete = Section::find($section_id);
        if (!$sectionToDelete) {
            return;
        }
        $sinSeccion = Section::where('name', 'Sin sección')->where('company_id', $sectionToDelete->company_id)->first();
        if($sinSeccion){
            User::where('section_id', $section_id)->update(['section_id' => $sinSeccion->id]);
        }
    }
}
