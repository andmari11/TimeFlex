<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Form;
use App\Models\QuestionType;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Support\Facades\DB;

class FormsController extends Controller
{
    // Mostrar todos los formularios disponibles
    public function index()
    {
        $formularios = Form::all();
        return view('forms.index', compact('formularios'));
    }

    // Mostrar formulario específico junto con sus preguntas
    public function show($id)
    {
        $formulario = Form::with('questions')->find($id);
        return view('forms.show', compact('formulario'));
    }

    // Mostrar formulario para crear un nuevo formulario
    public function create()
    {
        return view('forms.create');
    }

    // Guardar un nuevo formulario y sus preguntas
    public function store(Request $request)
    {

        // Crear el formulario
        $formulario = Form::create([
            'id_user' => $request->user()->id,
            'title' => $request->title,
            'summary' => $request->summary,
            'status' => $request->status,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        // Crear las preguntas del formulario
        foreach ($request->questions as $questionData) {
            $question = new Question($questionData);
            $question->id_form = $formulario->id;
            $question->save();

            if (isset($questionData['options'])) {
                foreach ($questionData['options'] as $option) {
                    $questionOption = new Option([
                        'id_question' => $question->id,
                        'value' => $option
                    ]);
                    $questionOption->save();
                }
            }
        }

        return redirect()->route('forms.index')
            ->with('success', 'Formulario y preguntas creados exitosamente.');
    }

    // Eliminar un formulario
    public function destroy($id)
    {
        // Encuentra el formulario por su ID
        $formulario = Form::findOrFail($id);

        // Elimina el formulario
        $formulario->delete();

        // Redirige a la lista de formularios con un mensaje de éxito
        return redirect()->route('forms.index')
            ->with('success', 'Formulario eliminado exitosamente.');
    }
}
