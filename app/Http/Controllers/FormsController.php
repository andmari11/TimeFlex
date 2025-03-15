<?php

namespace App\Http\Controllers;

use App\Models\Result;
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
        $user = auth()->user();

        // Si el usuario es administrador, mostramos todos los formularios
        if ($user->role === 'admin') {
            $formularios = Form::all();
        } else {
            // Si el usuario es empleado, mostrar formularios de su sección
            $formularios = Form::where('id_section', $user->section_id)->get();
        }

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
        // Validar los datos del formulario
        $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'id_section' => 'required|exists:sections,id', // Validar sección
        ]);

        // Crear el formulario
        $formulario = Form::create([
            'id_user' => $request->user()->id,
            'title' => $request->title,
            'summary' => $request->summary,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'id_section' => $request->id_section, // Asignar la sección
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
            ->with('success', 'Formulario, preguntas y sección asignados exitosamente.');
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

    // Función para mostrar el formulario de edición
    public function edit($id)
    {
        $formulario = Form::with('questions.options')->findOrFail($id);
        return view('forms.edit', compact('formulario'));
    }

    // Función para actualizar un formulario existente
    public function update(Request $request, $id)
    {
        $formulario = Form::findOrFail($id);

        // Actualizar los datos del formulario
        $formulario->update([
            'title' => $request->title,
            'summary' => $request->summary,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            // Otros campos si es necesario
        ]);

        // Actualizar las preguntas del formulario
        foreach ($request->questions as $index => $questionData) {

            if (isset($questionData['id'])) {
                // Actualizar pregunta existente
                $question = Question::findOrFail($questionData['id']);
                $question->update([
                    'title' => $questionData['title'],
                    'id_question_type' => $questionData['id_question_type'],
                ]);

                if (isset($questionData['options'])) {
                    foreach ($questionData['options'] as $optionIndex => $optionValue) {
                        if (isset($optionValue['id'])) {
                            // Actualizar opción existente
                            $option = Option::findOrFail($optionValue['id']);
                            $option->update(['value' => $optionValue['value']]);
                        } else {
                            // Crear nueva opción
                            $questionOption = new Option([
                                'id_question' => $question->id,
                                'value' => $optionValue['value'],
                            ]);
                            $questionOption->save();
                        }
                    }
                }
            } else {
                // Crear nueva pregunta
                $question = new Question([
                    'id_form' => $formulario->id,
                    'title' => $questionData['title'],
                    'id_question_type' => $questionData['id_question_type'],
                ]);
                $question->save();

                if (isset($questionData['options'])) {
                    foreach ($questionData['options'] as $option) {
                        $questionOption = new Option([
                            'id_question' => $question->id,
                            'value' => $option['value'],
                        ]);
                        $questionOption->save();
                    }
                }
            }
        }

        return redirect()->route('forms.index')
            ->with('success', 'Formulario actualizado exitosamente.');
    }


    public function submit(Request $request, $id)
    {
        $formulario = Form::with('questions')->findOrFail($id);

        // Validar los datos enviados
        $validatedData = $request->validate([
            'questions' => 'required|array',
            'questions.*.id_question' => 'required|integer|exists:questions,id',
            'questions.*.id_question_type' => 'required|integer|exists:question_type,id',
            'questions.*.answer' => 'required',
        ]);

        // Procesar y guardar las respuestas
        foreach ($validatedData['questions'] as $data) {
            $id_question = $data['id_question'];
            $answer = $data['answer'];
            $id_question_type = $data['id_question_type'];


            // Guardar la respuesta en la tabla results
            Result::create([
                'id_question' => $id_question,
                'respuesta' => $answer,
                'id_question_type' => $id_question_type,
            ]);
        }

        return redirect()->route('forms.index')->with('success', 'Formulario enviado correctamente.');
    }




}
