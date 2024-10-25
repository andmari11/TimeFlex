<?php

namespace App\Http\Controllers\Sections;

use App\Http\Controllers\Controller;
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
            'name' => ['required'],
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
        return view('sections.edit', compact('section'));
    }

    // Método para actualizar una sección en la base de datos
    public function update(int $id)
    {
        // Validar los atributos de la sección
        request()->validate([
            'name' => ['required'],
        ]);

        // Encontrar la sección y actualizarla
        $section = Section::findOrFail($id);
        $section->update([
            'name' => request('name'),
        ]);

        // Redirigir al menú principal
        return redirect('/menu');
    }

    // Método para eliminar una sección de la base de datos
    public function destroy(int $id)
    {
        $section = Section::findOrFail($id);
        $section->delete();

        // Redirigir al menú principal
        return redirect('/menu');
    }
}