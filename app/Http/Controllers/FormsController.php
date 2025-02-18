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





    // Mostrar formulario para agregar una nueva pregunta
    public function createQuestion($formId) {
        $formulario = Form::find($formId);
        $tiposDePregunta = QuestionType::all();
        return view('questions.create', compact('formulario', 'tiposDePregunta'));
    }

    // Guardar una nueva pregunta
    public function storeQuestion(Request $request, $formId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'id_question_type' => 'required|integer|exists:question_types,id',
            'options' => 'array',
            'options.*' => 'string' // Cada opción debe ser una cadena de texto
        ]);

        $question = new Question($request->all());
        $question->id_form = $formId;
        $question->save();

        // Si el tipo de pregunta es "Opciones", agregar las opciones
        if ($request->id_question_type == 1) { // Suponiendo que el tipo de pregunta "Opciones" tiene el ID 1
            foreach ($request->options as $optionText) {
                $option = new Option();
                $option->id_question = $question->id;
                $option->value = $optionText;
                $option->save();
            }
        }

        return redirect()->route('forms.show', $formId)
            ->with('success', 'Pregunta creada exitosamente.');
    }
}
