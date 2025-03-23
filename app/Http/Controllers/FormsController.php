<?php

namespace App\Http\Controllers;

use App\Models\Result;
use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\Form;
use App\Models\QuestionType;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class FormsController extends Controller
{
    // Mostrar todos los formularios disponibles
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            // Obtener formularios únicos de todas las secciones
            $formularios = Form::with('sections')->distinct()->get();
        } else {
            // Obtener formularios de la sección asignada al usuario, únicos
            $formularios = Form::whereHas('sections', function ($query) use ($user) {
                $query->where('sections.id', $user->section_id);
            })->with('sections')->distinct()->get();
        }

        return view('forms.index', compact('formularios'));
    }


    // Mostrar formulario específico junto con sus preguntas
    public function show($id)
    {
        $formulario = Form::with('questions')->findOrFail($id);
        $userId = auth()->user()->id;
        // Verificar si el usuario ya respondió el formulario
        $hasAnswered = Result::where('id_user', $userId)
            ->where('id_form', $id)
            ->exists();

        return view('forms.show', compact('formulario', 'hasAnswered'));
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
            'id_sections' => 'required|array', // Validar que es un array
            'id_sections.*' => 'exists:sections,id', // Validar que cada sección exista en la tabla sections
        ]);

        // Crear el formulario
        $formulario = Form::create([
            'id_user' => $request->user()->id,
            'title' => $request->title,
            'summary' => $request->summary,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        // Asociar las secciones seleccionadas con el formulario
        $formulario->sections()->sync($request->id_sections);

        // Crear las preguntas del formulario
        foreach ($request->questions as $questionData) {
            $question = new Question([
                'id_form' => $formulario->id,
                'title' => $questionData['title'],
                'id_question_type' => $questionData['id_question_type'],
            ]);
            $question->save();

            // Crear las opciones si existen
            if (isset($questionData['options'])) {
                foreach ($questionData['options'] as $option) {
                    $questionOption = new Option([
                        'id_question' => $question->id,
                        'value' => $option,
                    ]);
                    $questionOption->save();
                }
            }
        }

        return redirect()->route('forms.index')
            ->with('success', 'Formulario, preguntas y secciones asignados exitosamente.');
    }



    // Eliminar un formulario
    public function destroy($id)
    {
        // Encuentra el formulario por su ID
        $formulario = Form::with('results')->findOrFail($id); // Incluye los resultados relacionados

        // Eliminar los resultados relacionados
        foreach ($formulario->results as $result) {
            $result->delete();
        }

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

        // Validar los datos del formulario
        $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'id_sections' => 'required|array',
            'id_sections.*' => 'exists:sections,id',
            'questions' => 'required|array',
            'questions.*.id' => 'nullable|integer|exists:questions,id',
            'questions.*.title' => 'required|string|max:255',
            'questions.*.id_question_type' => 'required|integer|exists:question_type,id',
            'questions.*.file' => 'sometimes|file|max:2048', // Archivos con máximo 2MB
        ]);

        // Actualizar los datos generales del formulario
        $formulario->update([
            'title' => $request->title,
            'summary' => $request->summary,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        // Sincronizar las secciones seleccionadas
        $formulario->sections()->sync($request->id_sections);

        // Actualizar las preguntas del formulario
        foreach ($request->questions as $index => $questionData) {
            if (isset($questionData['id'])) {
                // Actualizar pregunta existente
                $question = Question::findOrFail($questionData['id']);
                $question->update([
                    'title' => $questionData['title'],
                    'id_question_type' => $questionData['id_question_type'],
                ]);

                // Actualizar o agregar opciones de la pregunta existente
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

                // Crear nuevas opciones para la pregunta
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

        // Validación de los datos que llegan del formulario.
        $validatedData = $request->validate([
            'questions' => 'required|array',
            'questions.*.id_question' => 'required|integer|exists:questions,id',
            'questions.*.id_question_type' => 'required|integer|exists:question_type,id', // nombre correcto de la tabla
            'id_user' => 'required|integer|exists:users,id',
            'id_form' => 'required|integer|exists:forms,id',
            // Para respuestas simples se utiliza "answer"
            'questions.*.answer' => 'sometimes|required',
        ], [
            'questions.required' => 'Debes responder al menos una pregunta.',
            'questions.*.answer.required' => 'Por favor, completa todas las preguntas antes de enviar el formulario.',
        ]);

        // Procesamiento y guardado de cada respuesta.
        foreach ($validatedData['questions'] as $index => $data) {
            $questionType = $data['id_question_type'];

            switch ($questionType) {
                case 2:
                    // Tipo Selector: espera una respuesta simple (string)
                    $answer = $data['answer'] ?? "";
                    break;

                case 4:
                    $shiftId = $data['answer'];
                    $shift = \App\Models\ShiftType::find($shiftId);
                    $scheduleId = $shift ? $shift->schedule_id : null;
                    $answer = $shiftId;
                    break;

                case 7:
                    // Tipo Opción múltiple: se espera que 'answer' sea un array.
                    if (isset($data['answer']) && is_array($data['answer']) && count($data['answer']) > 0) {
                        $answer = json_encode($data['answer']);
                    } else {
                        $answer = "";
                    }
                    break;

                case 9:
                    // Tipo Carga de Archivo: guardar el archivo en la nueva tabla "files"
                    if ($request->hasFile("questions.$index.answer")) {
                        $file = $request->file("questions.$index.answer");
                        // Obtiene el contenido binario del archivo
                        $fileData = file_get_contents($file->getRealPath());

                        // Crear registro en la tabla files usando el modelo File
                        $fileRecord = \App\Models\File::create([
                            'name' => $file->getClientOriginalName(),
                            'mime' => $file->getMimeType(),
                            'data' => $fileData,
                        ]);

                        // Guardamos el ID del archivo en la columna "respuesta"
                        // (se almacena como string, ya que la columna es de tipo string)
                        $answer = $fileRecord->id;
                    } else {
                        $answer = "";
                    }
                    break;

                default:
                    // Para el resto de tipos se utiliza el valor de "answer"
                    $answer = $data['answer'] ?? "";
                    break;
            }

            Result::create([
                'id_question'      => $data['id_question'],
                'respuesta'        => $answer, // Para archivos, este campo contendrá el ID del registro en "files"
                'id_question_type' => $questionType,
                'id_user'          => $validatedData['id_user'],
                'id_form'          => $validatedData['id_form'],
                'id_schedule'      => $scheduleId ?? null,
            ]);

            $scheduleId = null;
        }

        return redirect()->route('forms.index')->with('success', 'Formulario enviado correctamente.');
    }


    public function duplicate(Request $request, $id)
    {
        // Opcional: Validar los datos recibidos
        $validatedData = $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $originalForm = Form::with('questions.options', 'sections')->findOrFail($id);

        // Clonar el formulario
        $newForm = $originalForm->replicate(); // Clona los datos principales
        $newForm->title = $originalForm->title . ' (Copia)';

        // Asignar las nuevas fechas al formulario duplicado
        $newForm->start_date = $validatedData['start_date'];
        $newForm->end_date   = $validatedData['end_date'];

        $newForm->save();

        // Clonar las relaciones (secciones, preguntas y opciones)
        $newForm->sections()->sync($originalForm->sections->pluck('id')->toArray());

        foreach ($originalForm->questions as $question) {
            $newQuestion = $question->replicate();
            $newQuestion->id_form = $newForm->id;
            $newQuestion->save();

            foreach ($question->options as $option) {
                $newOption = $option->replicate();
                $newOption->id_question = $newQuestion->id;
                $newOption->save();
            }
        }

        return redirect()->route('forms.index')
            ->with('success', 'El formulario ha sido duplicado exitosamente.');
    }

    public function showAnswers(Request $request)
    {
        // Query base para los formularios
        $query = Form::query();

        // Filtro por título
        if ($request->has('title') && $request->title) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        // Filtro por rango de fechas
        if ($request->has('date_from') && $request->date_from) {
            $query->where('start_date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->where('end_date', '<=', $request->date_to);
        }

        // Filtro por usuario
        if ($request->has('user_id') && $request->user_id) {
            $query->whereHas('questions.results', function ($q) use ($request) {
                $q->where('id_user', $request->user_id);
            });
        }

        // Filtro por sección
        if ($request->has('section_id') && $request->section_id !== null) {

                // Formularios con una sección específica
            $query->whereHas('sections', function ($q) use ($request) {
                $q->where('sections.id', $request->section_id);
            });
        }

        // Filtro por formularios activos
        if ($request->has('active') && $request->active) {
            $query->whereDate('end_date', '>=', now());
        }

        // Obtener los formularios con preguntas, resultados y usuarios
        $formularios = $query->with(['questions.results.user'])->get();

        // Obtener todas las secciones para el filtro
        $sections = Section::all()->toArray();

        // Obtener todos los usuarios para el filtro
        $users = User::all();

        return view('forms.answers', compact('formularios', 'sections', 'users'));
    }

    public function showResults($formId)
    {
        $userId = auth()->user()->id;

        // Obtener el formulario
        $formulario = Form::with('questions')->findOrFail($formId);

        // Obtener las respuestas del usuario actual para ese formulario
        $answers = Result::where('id_form', $formId)
            ->where('id_user', $userId)
            ->with('question')
            ->get();

        return view('forms.showresult', compact('formulario', 'answers'));
    }

    public function editResults($formId)
    {
        $userId = auth()->user()->id;

        // Obtener el formulario con sus preguntas
        $formulario = Form::with('questions')->findOrFail($formId);

        // Obtener las respuestas del usuario actual
        $answers = Result::where('id_form', $formId)
            ->where('id_user', $userId)
            ->with('question')
            ->get();

        return view('forms.editresults', compact('formulario', 'answers'));
    }

    public function updateResults(Request $request, $formId)
    {
        $userId = auth()->user()->id;

        // Validar las respuestas recibidas
        $validatedData = $request->validate([
            'answers' => 'required|array',
            'answers.*.id_question' => 'required|integer|exists:questions,id',
            'answers.*.id_question_type' => 'required|integer|exists:question_type,id',
            'answers.*.respuesta' => 'sometimes|required', // Respuesta requerida para tipos simples
            'answers.*.file' => 'sometimes|file|max:2048', // Para preguntas tipo archivo, límite de tamaño de 2MB
        ], [
            'answers.*.respuesta.required' => 'Por favor, completa todas las respuestas.',
            'answers.*.file.file' => 'El archivo debe ser válido.',
            'answers.*.file.max' => 'El archivo no puede superar los 2MB.',
        ]);

        // Procesar y actualizar las respuestas en la base de datos
        foreach ($validatedData['answers'] as $answerId => $data) {
            $result = Result::where('id', $answerId)
                ->where('id_user', $userId)
                ->where('id_form', $formId)
                ->firstOrFail();

            switch ($data['id_question_type']) {
                case 7: // Tipo Opción Múltiple
                    if (isset($data['respuesta']) && is_array($data['respuesta'])) {
                        $result->update([
                            'respuesta' => json_encode($data['respuesta']), // Guardar como JSON
                        ]);
                    } else {
                        $result->update([
                            'respuesta' => "", // Si no hay opciones seleccionadas, vacío
                        ]);
                    }
                    break;

                case 9: // Tipo Archivo
                    if ($request->hasFile("answers.$answerId.file")) {
                        $file = $request->file("answers.$answerId.file");

                        // Si hay un archivo actual asociado, eliminarlo
                        if ($result->file) {
                            $result->file->delete();
                        }

                        // Guardar el nuevo archivo en la tabla files
                        $fileData = \App\Models\File::create([
                            'name' => $file->getClientOriginalName(),
                            'mime' => $file->getMimeType(),
                            'data' => file_get_contents($file->getRealPath()),
                        ]);

                        // Actualizar el resultado con el ID del nuevo archivo
                        $result->update([
                            'respuesta' => $fileData->id,
                        ]);
                    }
                    break;

                default: // Otros tipos de preguntas
                    $result->update([
                        'respuesta' => $data['respuesta'] ?? "",
                    ]);
                    break;
            }
        }

        return redirect()->route('forms.showresults', $formId)
            ->with('success', 'Tus respuestas se han actualizado correctamente.');
    }
}
