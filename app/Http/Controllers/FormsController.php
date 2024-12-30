<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Form;
use Carbon\Carbon;

class FormsController extends Controller
{
    // Mostrar todos los formularios disponibles
    public function index()
    {
        $fechaActual = Carbon::now();
        $formularios = Form::where('status', 1)
            ->where('start_date', '<=', $fechaActual)
            ->where('end_date', '>=', $fechaActual)
            ->get();

        return view('forms.index', compact('formularios'));
    }

    // Mostrar formulario específico
    public function show($id)
    {
        $formulario = Form::find($id);
        return view('forms.show', compact('formulario'));
    }

    // Mostrar formulario para crear un nuevo formulario
    public function create()
    {
        return view('forms.create');
    }

    // Guardar un nuevo formulario
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'required|string',
            'status' => 'required|boolean',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        Form::create($request->all());

        return redirect()->route('forms.index')
            ->with('success', 'Formulario creado exitosamente.');
    }

    // Mostrar formulario para editar un formulario existente
    public function edit($id)
    {
        $formulario = Form::find($id);
        return view('forms.edit', compact('formulario'));
    }

    // Actualizar un formulario existente
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'required|string',
            'status' => 'required|boolean',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $formulario = Form::find($id);
        $formulario->update($request->all());

        return redirect()->route('forms.index')
            ->with('success', 'Formulario actualizado exitosamente.');
    }

    // Eliminar un formulario existente
    public function destroy($id)
    {
        $formulario = Form::find($id);
        $formulario->delete();

        return redirect()->route('forms.index')
            ->with('success', 'Formulario eliminado exitosamente.');
    }
}
