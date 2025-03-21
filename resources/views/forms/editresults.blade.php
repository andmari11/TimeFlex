<x-layout :title="'Editar Respuestas del Formulario'">
    <div class="container mx-auto py-10 px-6">
        <!-- Encabezado -->
        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">{{ $formulario->title }}</h1>
            <p class="text-gray-600 mt-2">{{ $formulario->summary }}</p>
            <div class="text-sm text-gray-500 mt-3">
                <p><strong>Desde:</strong> {{ \Carbon\Carbon::parse($formulario->start_date)->format('d/m/Y H:i') }}</p>
                <p><strong>Hasta:</strong> {{ \Carbon\Carbon::parse($formulario->end_date)->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <!-- Contenedor del Formulario -->
        <div class="bg-white shadow-lg rounded-lg p-8 max-w-4xl mx-auto">
            <form id="edit-form" action="{{ route('forms.updateresults', $formulario->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Preguntas -->
                <div class="space-y-6">
                    <h2 class="text-2xl font-bold text-gray-800">Preguntas</h2>
                    @foreach($answers as $answer)
                        <div class="mt-4 bg-gray-50 p-4 rounded-lg shadow-sm">
                            <label for="answer-{{ $answer->id }}" class="block text-lg font-medium text-gray-700">
                                {{ $answer->question->title }}
                            </label>

                            <!-- Campos Ocultos -->
                            <input type="hidden" name="answers[{{ $answer->id }}][id_question]" value="{{ $answer->question->id }}">
                            <input type="hidden" name="answers[{{ $answer->id }}][id_question_type]" value="{{ $answer->question->id_question_type }}">

                            <!-- Tipos de Pregunta -->
                            @switch($answer->question->id_question_type)
                                @case(1)
                                    <!-- Pregunta Rango de Fechas -->
                                    <div class="relative mt-2">
                                        <input type="text" name="answers[{{ $answer->id }}][respuesta]" id="date-range-picker-{{ $answer->id }}"
                                               class="date-range-picker mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                               value="{{ $answer->respuesta }}" required />
                                    </div>
                                    @break

                                @case(2)
                                    <!-- Pregunta de Selección -->
                                    <div class="mt-2">
                                        @foreach($answer->question->options as $option)
                                            <div class="flex items-center mb-2">
                                                <input type="radio" name="answers[{{ $answer->id }}][respuesta]" id="option-{{ $answer->id }}-{{ $option->id }}"
                                                       value="{{ $option->value }}" class="form-radio text-blue-500 focus:ring-blue-500"
                                                       @if($answer->respuesta == $option->value) checked @endif>
                                                <label for="option-{{ $answer->id }}-{{ $option->id }}" class="ml-2 text-gray-700">
                                                    {{ $option->value }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    @break

                                @case(3)
                                    <!-- Pregunta Tipo Deslizador -->
                                    <div class="slider-container mt-4 flex items-center gap-4">
                                        <input type="range" name="answers[{{ $answer->id }}][respuesta]" id="slider-{{ $answer->id }}" min="0" max="100" step="1"
                                               value="{{ $answer->respuesta }}" class="slider w-full" />
                                        <span id="slider-value-{{ $answer->id }}" class="slider-value text-blue-500 font-semibold">{{ $answer->respuesta }}%</span>
                                    </div>
                                    @break

                                @case(5)
                                    <!-- Pregunta Calendario Múltiple -->
                                    <div class="relative mt-2">
                                        <input type="text" name="answers[{{ $answer->id }}][respuesta]" id="multi-date-picker-{{ $answer->id }}"
                                               class="multi-date-picker mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                               value="{{ $answer->respuesta }}" required />
                                    </div>
                                    @break
                                @case(6)
                                    <!-- Pregunta Texto Libre -->
                                    <div class="mt-2">
                                        <textarea name="answers[{{ $answer->id }}][respuesta]" id="text-{{ $answer->id }}" rows="4"
                                                  class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>{{ $answer->respuesta }}</textarea>
                                    </div>
                                    @break

                                @case(7)
                                    <!-- Pregunta de Opción Múltiple -->
                                    <div class="mt-2">
                                        @foreach($answer->question->options as $option)
                                            <div class="flex items-center mb-2">
                                                <input type="checkbox" name="answers[{{ $answer->id }}][respuesta][]" id="checkbox-{{ $answer->id }}-{{ $option->id }}"
                                                       value="{{ $option->value }}" class="form-checkbox text-blue-500 focus:ring-blue-500"
                                                       @if(in_array($option->value, json_decode($answer->respuesta, true))) checked @endif>
                                                <label for="checkbox-{{ $answer->id }}-{{ $option->id }}" class="ml-2 text-gray-700">
                                                    {{ $option->value }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    @break

                                @case(8)
                                    <!-- Pregunta Numérica -->
                                    <div class="mt-2">
                                        <input type="number" name="answers[{{ $answer->id }}][respuesta]" id="number-{{ $answer->id }}"
                                               class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                               value="{{ $answer->respuesta }}" required />
                                    </div>
                                    @break

                                @case(9)
                                    <!-- Pregunta de Carga de Archivo -->
                                    <div class="mt-2">
                                        @if($answer->file)
                                            <p class="text-gray-600">
                                                Archivo Actual: <a href="{{ route('file.show', $answer->file->id) }}" class="text-blue-500 hover:underline">
                                                    {{ $answer->file->name }}
                                                </a>
                                            </p>
                                        @endif
                                        <label for="file-{{ $answer->id }}" class="block text-sm font-medium text-gray-800 mt-4">Actualizar Archivo</label>
                                        <input type="file" name="answers[{{ $answer->id }}][file]" id="file-{{ $answer->id }}"
                                               class="mt-1 block w-full">
                                    </div>
                                    @break

                                @default
                                    <!-- Tipo de pregunta no definido -->
                                    <p class="text-red-500 text-sm mt-2">Tipo de pregunta no definido.</p>
                                    @break
                            @endswitch

                            <!-- Mostrar errores debajo de cada pregunta -->
                            @error("answers.{$answer->id}.respuesta")
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    @endforeach
                </div>

                <!-- Botones de Acción -->
                <div class="mt-8 flex justify-between">
                    <button type="button" id="open-cancel-modal"
                            class="btn text-gray-600 bg-gray-200 hover:bg-gray-300 font-semibold py-2 px-4 rounded shadow-md">
                        Cancelar
                    </button>
                    <button type="button" id="open-save-modal"
                            class="btn bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded shadow-md">
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Modal de Confirmación para Guardar Cambios -->
    <div id="save-modal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-md w-full">
            <h2 class="text-lg font-semibold text-gray-800">¿Estás seguro de guardar estos cambios?</h2>
            <p class="text-gray-600 mt-2">Tus respuestas actualizadas se guardarán y se enviarán.</p>
            <div class="mt-6 flex justify-end space-x-4">
                <button id="close-save-modal"
                        class="btn bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-2 px-4 rounded">
                    Cancelar
                </button>
                <button id="confirm-save"
                        class="btn bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded">
                    Confirmar
                </button>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación para Cancelar -->
    <div id="cancel-modal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-md w-full">
            <h2 class="text-lg font-semibold text-gray-800">¿Estás seguro de que deseas cancelar?</h2>
            <p class="text-gray-600 mt-2">Todos los cambios no guardados se perderán.</p>
            <div class="mt-6 flex justify-end space-x-4">
                <button id="close-cancel-modal"
                        class="btn bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-2 px-4 rounded">
                    Volver
                </button>
                <a href="{{ route('forms.showresults', $formulario->id) }}"
                   class="btn bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded">
                    Salir
                </a>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Inicializar Flatpickr para rangos de fechas
            document.querySelectorAll('.date-range-picker').forEach(function (element) {
                flatpickr(element, {
                    mode: "range",
                    dateFormat: "Y-m-d",
                    defaultDate: element.value.split(" to ") // Carga valores anteriores
                });
            });

            // Inicializar Flatpickr para fechas múltiples
            document.querySelectorAll('.multi-date-picker').forEach(function (element) {
                flatpickr(element, {
                    mode: "multiple",
                    dateFormat: "Y-m-d",
                    defaultDate: element.value.split(", ") // Carga valores anteriores
                });
            });

            // Actualizar el valor del deslizador
            document.querySelectorAll('.slider').forEach(function (slider) {
                const valueDisplay = document.getElementById(`slider-value-${slider.id.split('-')[1]}`);
                valueDisplay.textContent = `${slider.value}%`;
                slider.addEventListener('input', function () {
                    valueDisplay.textContent = `${slider.value}%`;
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Modal de Confirmación para Guardar Cambios
            const saveModal = document.getElementById('save-modal');
            const openSaveModalButton = document.getElementById('open-save-modal');
            const closeSaveModalButton = document.getElementById('close-save-modal');
            const confirmSaveButton = document.getElementById('confirm-save');
            const editForm = document.getElementById('edit-form');

            openSaveModalButton.addEventListener('click', () => {
                saveModal.classList.remove('hidden');
            });

            closeSaveModalButton.addEventListener('click', () => {
                saveModal.classList.add('hidden');
            });

            confirmSaveButton.addEventListener('click', () => {
                editForm.submit();
            });

            // Modal de Confirmación para Cancelar
            const cancelModal = document.getElementById('cancel-modal');
            const openCancelModalButton = document.getElementById('open-cancel-modal');
            const closeCancelModalButton = document.getElementById('close-cancel-modal');

            openCancelModalButton.addEventListener('click', () => {
                cancelModal.classList.remove('hidden');
            });

            closeCancelModalButton.addEventListener('click', () => {
                cancelModal.classList.add('hidden');
            });
        });
    </script>
</x-layout>
