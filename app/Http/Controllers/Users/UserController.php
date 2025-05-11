<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Section;
use Illuminate\Validation\Rules\Password;
use App\Models\ExpectedHours;
use Illuminate\Http\Request;
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
            'weight'     => ['required'],
        ]);

        $attributesUser_defaults = [
            "company_id" => auth()->user()->company->id
        ];

        $attributesUser = array_merge($attributesUser, $attributesUser_defaults);
        $user = User::create($attributesUser);

        // creamos las horas esperadas por turno para el nuevo usuario
        $defaultMorning = 80;
        $defaultAfternoon = 60;
        $defaultNight = 50;

        $months = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];

        $year = now()->year;

        foreach ($months as $month) {
            ExpectedHours::create([
                'user_id' => $user->id,
                'section_id' => $user->section_id,
                'month' => $month,
                'year' => $year,
                'morning_hours' => $defaultMorning,
                'afternoon_hours' => $defaultAfternoon,
                'night_hours' => $defaultNight,
            ]);
        }

        return redirect('/menu');
    }

    public function edit(Request $request, int $id){
        $user = User::findOrFail($id);
        $redirectTo = $request->input('redirect_to', url('/menu'));

        return view('users.edit', compact('user', 'redirectTo'));
    }

    public function profileEdit()
    {
        $user = auth()->user();

        return view('users.profile', compact('user'));
    }

    public function profileUpdate()
    {
        request()->validate([
            'name'        => ['required'],
            'email'       => ['required', 'email', 'unique:users,email,' . auth()->id()],
            'password'   => ['nullable', Password::min(6), 'confirmed'],
        ]);

        $user = auth()->user();
        $user->update([
            'name'       => request('name'),
            'email'      => request('email'),
        ]);

        $user->save();

        return redirect('/menu');
    }

    public function update(Request $request, int $id)
    {
        request()->validate([
            'name'       => ['required'],
            'email'      => ['required', 'email'],
            'password'   => ['nullable', Password::min(6), 'confirmed'],
            'role'       => ['required'],
            'section_id' => ['required'],
            'user_weight' => ['required'],
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'name'       => request('name'),
            'email'      => request('email'),
            'section_id' => request('section_id'),
            'weight'     => request('user_weight'),
        ]);
        $redirect = $request->input('redirect_to', url('/menu'));

        return redirect($redirect)->with('success','Edición de datos del usuario realizada correctamente');
    }


    public function destroy(Request $request, int $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        $redirect = $request->input('redirect_to', url('/menu'));
        return redirect($redirect)->with('success', 'Usuario eliminado correctamente');
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
