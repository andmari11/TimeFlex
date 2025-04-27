<?php

namespace App\Http\Controllers\Sections;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Users\UserController;
use App\Models\Section;

class SectionController extends Controller
{
    // Método para mostrar la vista de registro de secciones
    public function create()
    {
        return view('sections.register');
    }

    // Método para almacenar una nueva sección en la base de datos
    public function store()
    {
        // TODO: comprobar que sea admin de la empresa

        // Validar los atributos de la sección
        $attributesSection = request()->validate([
            'name' => ['required', 'unique:sections,name'],
            'min_hours' => ['required', 'integer', 'min:0'],
            'max_hours' => ['required', 'integer', 'min:0', 'gte:min_hours'],
        ], [
            'name.required' => 'Es necesario introducir el nombre de la sección a registrar',
            'name.unique' => 'Ya existe una sección con este nombre',
            'min_hours.required' => 'Es necesario introducir el número mínimo de horas',
            'min_hours.integer' => 'El número mínimo de horas debe ser un valor entero',
            'min_hours.min' => 'El número mínimo de horas no puede ser menor que 0',
            'max_hours.required' => 'Es necesario introducir el número máximo de horas',
            'max_hours.integer' => 'El número máximo de horas debe ser un valor entero',
            'max_hours.min' => 'El número máximo de horas no puede ser menor que 0',
            'max_hours.gte' => 'El número máximo de horas debe ser mayor o igual al número mínimo de horas',
        ]);

        // Obtener el último ID de la base de datos y sumarle 1
        $lastId = Section::max('id');
        $newId = $lastId + 1;

        // Obtener el company_id del usuario autenticado
        $companyId = auth()->user()->company->id;

        // Combinar el nuevo ID, company_id y los atributos validados
        $attributesSection = array_merge($attributesSection, [
            'id' => $newId,
            'company_id' => $companyId,
        ]);
        
        // Crear una nueva sección con los atributos combinados
        $section = Section::create($attributesSection);

        // Redirigir al menú principal
        return redirect('/menu');
    }

    // Método para mostrar la vista de edición de una sección
    public function edit(int $id)
    {
        $section = Section::findOrFail($id);
        if($section->default){
            return redirect('/menu')->withErrors([
                'error'=>'No se puede eliminar la sección de administradores',
            ]);
        }
        return view('sections.edit', compact('section'));
    }

    // Método para actualizar una sección en la base de datos
    public function update(int $id)
    {

        // Validar los atributos de la sección
        request()->validate([
            'name' => ['required'],
            'min_hours' => 'required|integer|min:0',
            'max_hours' => 'required|integer|min:0|gte:min_hours',
        ]);

        // Encontrar la sección y actualizarla
        $section = Section::findOrFail($id);
        if($section->default){
            return redirect('/menu')->withErrors([
                'error'=>'No se puede eliminar la sección de administradores',
            ]);
        }
        $section->update([
            'name' => request('name'),
            'min_hours' => request('min_hours'),
            'max_hours' => request('max_hours'),
        ]);

        // Redirigir al menú principal
        return redirect('/menu');
    }

    // Método para eliminar una sección de la base de datos
    public function destroy(int $id)
    {
        $section = Section::findOrFail($id);
        if($section->default){
            return redirect('/menu')->withErrors([
                'error'=>'No se puede eliminar la sección de administradores',
            ]);
        }

        UserController::reassignSectionToUnassigned($id);
        $section->delete();

        // Redirigir al menú principal
        return redirect('/menu');
    }
}
