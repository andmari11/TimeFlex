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
            <form action="{{ route('forms.updateresults', $formulario->id) }}" method="POST">
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
                            @endswitch
                        </div>
                    @endforeach
                </div>

                <!-- Botones de Acción -->
                <div class="mt-8 flex justify-between">
                    <a href="{{ route('forms.showresults', $formulario->id) }}" class="btn text-gray-600 bg-gray-200 hover:bg-gray-300 font-semibold py-2 px-4 rounded shadow-md">
                        Cancelar
                    </a>
                    <button type="submit" class="btn bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded shadow-md">
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Estilos y scripts necesarios -->
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
</x-layout>
