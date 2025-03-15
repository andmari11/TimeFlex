<x-layout :title="'Ver Formulario'">
    <div class="flex items-center justify-center">
        <form action="{{ route('forms.submit', $formulario->id) }}" method="POST" class="w-full max-w-md bg-white p-8 rounded-lg shadow-md mt-10">
            @csrf

            <!-- Información del formulario -->
            <div class="space-y-1 row">
                <h1 class="text-2xl font-bold mb-4">{{ $formulario->title }}</h1>
                <p class="mb-4">{{ $formulario->summary }}</p>
                <p class="mb-4">Disponible desde: {{ $formulario->start_date }}</p>
                <p class="mb-4">Hasta: {{ $formulario->end_date }}</p>
            </div>

            <!-- Preguntas del formulario -->
            <div class="mt-6">
                <h2 class="text-xl font-bold">Preguntas</h2>
                @foreach($formulario->questions as $index => $question)
                    <div class="mt-4">
                        <x-forms.label for="questions[{{ $index }}][answer]">{{ $question->title }}</x-forms.label>

                        <!-- Campos ocultos para id_question, id_question_type, id_form -->
                        <input type="hidden" name="questions[{{ $index }}][id_question]" value="{{ $question->id }}">
                        <input type="hidden" name="questions[{{ $index }}][id_question_type]" value="{{ $question->id_question_type }}">
                        <input type="hidden" name="id_form" value="{{ $formulario->id }}">
                        <input type="hidden" name="id_user" value="{{ auth()->user()->id }}">

                        @switch($question->id_question_type)
                            @case(1) <!-- Pregunta para seleccionar un rango de fechas -->
                            <div class="relative">
                                <input type="text" name="questions[{{ $index }}][answer]" id="date-range-picker-{{ $index }}" class="date-range-picker form-input pl-10" required />
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                        <i class="fas fa-calendar text-gray-500"></i> <!-- Icono de calendario -->
                                    </span>
                            </div>
                            @break
                            @case(2) <!-- Pregunta de selección -->
                            <div>
                                @foreach($question->options as $optionIndex => $option)
                                    <div class="flex items-center mb-2">
                                        <input type="radio" name="questions[{{ $index }}][answer]" id="option-{{ $index }}-{{ $optionIndex }}" value="{{ $option->value }}" required />
                                        <label for="option-{{ $index }}-{{ $optionIndex }}" class="ml-2">{{ $option->value }}</label>
                                    </div>
                                @endforeach
                            </div>
                            @break
                            @case(3) <!-- Pregunta tipo deslizador -->
                            <div class="slider-container">
                                <input type="range" name="questions[{{ $index }}][answer]" id="slider-{{ $index }}" min="0" max="100" step="1" value="50" class="slider" />
                                <span id="slider-value-{{ $index }}" class="slider-value">50%</span>
                            </div>
                            @break
                            @case(5) <!-- Pregunta tipo calendario múltiple -->
                            <div class="relative">
                                <input type="text" name="questions[{{ $index }}][answer]" id="multi-date-picker-{{ $index }}" class="multi-date-picker form-input pl-10" required />
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                        <i class="fas fa-calendar text-gray-500"></i> <!-- Icono de calendario -->
                                    </span>
                            </div>
                            @break
                        @endswitch

                        <x-forms.error name="questions[{{ $index }}][answer]" />
                    </div>
                @endforeach
            </div>

            <!-- Botones de acción -->
            <div class="mt-6 flex items-center justify-between">
                <a href="/formularios" class="text-sm font-semibold leading-6 text-gray-900">Cancelar</a>
                <x-forms.button>Enviar</x-forms.button>
            </div>
        </form>
    </div>

    <!-- Estilos y scripts necesarios para Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Inicializar Flatpickr para rango de fechas (id_question_type = 1)
            document.querySelectorAll('.date-range-picker').forEach(function (element) {
                flatpickr(element, {
                    mode: "range", // Permitir rango de fechas
                    dateFormat: "Y-m-d", // Formato de fecha
                });
            });

            // Inicializar Flatpickr para selección múltiple (id_question_type = 5)
            document.querySelectorAll('.multi-date-picker').forEach(function (element) {
                flatpickr(element, {
                    mode: "multiple",
                    dateFormat: "Y-m-d",
                });
            });
        });
    </script>

    <!-- Mover style a css cuando sepa donde está -->
    <style>
        .slider-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .slider {
            width: 80%; /* Ajusta el ancho de la barra deslizante */
            height: 8px;
            appearance: none;
            background: #ddd;
            border-radius: 5px;
            outline: none;
            transition: background 0.3s;
        }
        .slider::-webkit-slider-thumb {
            appearance: none;
            width: 20px;
            height: 20px;
            background: #4A90E2;
            border-radius: 50%;
            cursor: pointer;
        }
        .slider-value {
            font-weight: bold;
            color: #4A90E2;
        }
    </style>

    <!-- Script para actualizar el valor del porcentaje -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.slider').forEach(function (slider) {
                slider.addEventListener('input', function () {
                    const valueDisplay = document.getElementById(`slider-value-${slider.id.split('-')[1]}`);
                    valueDisplay.textContent = `${slider.value}%`;
                });
            });
        });
    </script>
</x-layout>
