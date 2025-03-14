<x-layout :title="'Ver Formulario'">
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
            <form action="{{ route('forms.submit', $formulario->id) }}" method="POST">
                @csrf

                <!-- Preguntas -->
                <div class="space-y-6">
                    <h2 class="text-2xl font-bold text-gray-800">Preguntas</h2>
                    @foreach($formulario->questions as $index => $question)
                        <div class="mt-4 bg-gray-50 p-4 rounded-lg shadow-sm">
                            <label for="questions[{{ $index }}][answer]" class="block text-lg font-medium text-gray-700">
                                {{ $question->title }}
                            </label>

                            <!-- Campos Ocultos -->
                            <input type="hidden" name="questions[{{ $index }}][id_question]" value="{{ $question->id }}">
                            <input type="hidden" name="questions[{{ $index }}][id_question_type]" value="{{ $question->id_question_type }}">
                            <input type="hidden" name="id_form" value="{{ $formulario->id }}">
                            <input type="hidden" name="id_user" value="{{ auth()->user()->id }}">

                            <!-- Tipos de Pregunta -->
                            @switch($question->id_question_type)
                                @case(1)
                                    <!-- Pregunta Rango de Fechas -->
                                    <div class="relative mt-2">
                                        <input type="text" name="questions[{{ $index }}][answer]" id="date-range-picker-{{ $index }}"
                                               class="date-range-picker mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required />
                                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                            <i class="fas fa-calendar text-blue-500"></i>
                                        </span>
                                    </div>
                                    @break

                                @case(2)
                                    <!-- Pregunta de Selección -->
                                    <div class="mt-2">
                                        @foreach($question->options as $optionIndex => $option)
                                            <div class="flex items-center mb-2">
                                                <input type="radio" name="questions[{{ $index }}][answer]" id="option-{{ $index }}-{{ $optionIndex }}"
                                                       value="{{ $option->value }}" required class="form-radio text-blue-500 focus:ring-blue-500">
                                                <label for="option-{{ $index }}-{{ $optionIndex }}" class="ml-2 text-gray-700">
                                                    {{ $option->value }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    @break

                                @case(3)
                                    <!-- Pregunta Tipo Deslizador -->
                                    <div class="slider-container mt-4 flex items-center gap-4">
                                        <input type="range" name="questions[{{ $index }}][answer]" id="slider-{{ $index }}" min="0" max="100" step="1"
                                               value="50" class="slider w-full" />
                                        <span id="slider-value-{{ $index }}" class="slider-value text-blue-500 font-semibold">50%</span>
                                    </div>
                                    @break

                                @case(5)
                                    <!-- Pregunta Calendario Múltiple -->
                                    <div class="relative mt-2">
                                        <input type="text" name="questions[{{ $index }}][answer]" id="multi-date-picker-{{ $index }}"
                                               class="multi-date-picker mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required />
                                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                            <i class="fas fa-calendar text-blue-500"></i>
                                        </span>
                                    </div>
                                    @break
                            @endswitch

                            <!-- Mostrar errores -->
                            <x-forms.error name="questions[{{ $index }}][answer]" />
                        </div>
                    @endforeach
                </div>

                <!-- Botones de Acción -->
                <div class="mt-8 flex justify-between">
                    <a href="/formularios" class="btn text-gray-600 bg-gray-200 hover:bg-gray-300 font-semibold py-2 px-4 rounded shadow-md">
                        Cancelar
                    </a>
                    <button type="submit" class="btn bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded shadow-md">
                        Enviar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Estilos y scripts necesarios -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Inicializar Flatpickr para rangos de fechas
            document.querySelectorAll('.date-range-picker').forEach(function (element) {
                flatpickr(element, {
                    mode: "range",
                    dateFormat: "Y-m-d"
                });
            });

            // Inicializar Flatpickr para fechas múltiples
            document.querySelectorAll('.multi-date-picker').forEach(function (element) {
                flatpickr(element, {
                    mode: "multiple",
                    dateFormat: "Y-m-d"
                });
            });

            // Actualizar el valor del deslizador
            document.querySelectorAll('.slider').forEach(function (slider) {
                slider.addEventListener('input', function () {
                    const valueDisplay = document.getElementById(`slider-value-${slider.id.split('-')[1]}`);
                    valueDisplay.textContent = `${slider.value}%`;
                });
            });
        });
    </script>
</x-layout>
