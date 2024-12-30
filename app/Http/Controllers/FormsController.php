<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Form;
use App\Models\Question;

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

        $formulario = Form::create($request->all());

        return redirect()->route('forms.show', $formulario->id)
            ->with('success', 'Formulario creado exitosamente.');
    }

    // Mostrar formulario para agregar una nueva pregunta
    public function createQuestion($formId)
    {
        $formulario = Form::find($formId);
        return view('questions.create', compact('formulario'));
    }

    // Guardar una nueva pregunta
    public function storeQuestion(Request $request, $formId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'id_question_type' => 'required|integer|exists:question_types,id'
        ]);

        $question = new Question($request->all());
        $question->id_form = $formId;
        $question->save();

        return redirect()->route('forms.show', $formId)
            ->with('success', 'Pregunta creada exitosamente.');
    }
}
