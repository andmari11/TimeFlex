<x-layout :title="'Ver Formulario'">
    <script>
        function calendarComponent() {
            return {
                currentPage: 1,
                totalPages: {{$calendars->count()}},
                selectedDays: [],

                toggleSelection(dayId) {
                    const index = this.selectedDays.indexOf(dayId);
                    if (index > -1) {
                        this.selectedDays.splice(index, 1);
                    } else {
                        this.selectedDays.push(dayId);
                    }
                },

                isSelected(dayId) {
                    return this.selectedDays.includes(dayId);
                }
            }
        }
    </script>

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
            <form id="submit-form" action="{{ route('forms.submit', $formulario->id) }}" method="POST" enctype="multipart/form-data">
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
                                    <!-- Pregunta Rango de Fechas (Calendario básico) -->
                                    <div class="relative mt-2">
                                        <input type="text" name="questions[{{ $index }}][answer]" id="date-range-picker-{{ $index }}"
                                               class="date-range-picker mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required />
                                    </div>
                                    @break

                                @case(2)
                                    <!-- Pregunta de Selección (Radio buttons) -->
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
                                    <!-- Pregunta Tipo Deslizador (Slider) -->
                                    <div class="slider-container mt-4 flex items-center gap-4">
                                        <input type="range" name="questions[{{ $index }}][answer]" id="slider-{{ $index }}" min="0" max="100" step="1"
                                               value="50" class="slider w-full" />
                                        <span id="slider-value-{{ $index }}" class="slider-value text-blue-500 font-semibold">50%</span>
                                    </div>
                                    @break

                                @case(4)
                                    <!-- Pregunta de Turnos en un Desplegable -->
                                    <div class="mt-2">
                                        @php
                                            // Obtener el último schedule de la sección del usuario
                                            $userSectionId = auth()->user()->section_id; // Suponiendo que existe esta relación
                                            $latestSchedule = \App\Models\Schedule::where('section_id', $userSectionId)
                                                                ->latest('created_at') // Ordena por la fecha de creación más reciente
                                                                ->first();

                                            $shifts = $latestSchedule ? $latestSchedule->shiftTypes : []; // Relación con shift_types
                                        @endphp

                                        @if($latestSchedule && $shifts->isNotEmpty())
                                            <label for="shift-select-{{ $index }}" class="block text-sm font-medium text-gray-800">Selecciona un turno</label>
                                            <select name="questions[{{ $index }}][answer]" id="shift-select-{{ $index }}"
                                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                                @foreach($shifts as $shift)
                                                    <option value="{{ $shift->id }}">
                                                        @if($shift->period === 0)
                                                            ({{ \Carbon\Carbon::parse($shift->start)->format('d/m/Y H:i') }} - {{ \Carbon\Carbon::parse($shift->end)->format('d/m/Y H:i') }}) -
                                                        @else
                                                            ({{ \Carbon\Carbon::parse($shift->start)->format('H:i') }} - {{ \Carbon\Carbon::parse($shift->end)->format('H:i') }}) -
                                                        @endif
                                                        @switch($shift->period)
                                                                @case(0)
                                                                Una sola vez
                                                                @break
                                                                @case(1)
                                                                    Diaria
                                                                    @break
                                                                @case(2)
                                                                    Semanal
                                                                    @break
                                                                @case(3)
                                                                    Mensual
                                                                    @break
                                                                @case(4)
                                                                    Anual
                                                                    @break
                                                                @default
                                                                    Periodo no definido
                                                            @endswitch
                                                    </option>
                                                @endforeach
                                            </select>
                                        @else
                                            <div class="mt-2 text-red-600">
                                                No hay turnos disponibles para la sección del usuario.
                                            </div>
                                        @endif
                                    </div>
                                    @break

                                @case(5)

                                    <!-- Pregunta Calendario Múltiple-->
                                    <div x-data="calendarComponent()" class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
                                        @foreach($calendars as $i=>$month)
                                            <div x-show="currentPage == {{ $i+1 }}">
                                                <div class="flex items-center justify-between mb-4">

                                                <h2 class="text-lg font-semibold mb-4">{{$month['month']}}</h2>
                                                @if($calendars->count() > 1)
                                                    <div class="flex">
                                                        <!-- Botón de mes anterior -->
                                                        <button
                                                            type="button"
                                                            x-on:click="if (currentPage != 1) currentPage = currentPage - 1"
                                                            :disabled="currentPage == 1"
                                                            class="px-3 py-1 bg-sky-900 text-white cursor-pointer disabled:bg-gray-400 transition-all duration-200 ease-in-out rounded-l-md flex items-center justify-center w-10 h-10 border-r-2 border-white"
                                                            :class="{ 'opacity-50': currentPage == 1 }">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-7 h-7">
                                                                <path d="M15 19l-7-7 7-7"/>
                                                            </svg>
                                                        </button>

                                                        <!-- Botón de mes siguiente -->
                                                        <button
                                                            type="button"
                                                            x-on:click="if (currentPage != totalPages) currentPage = currentPage + 1"
                                                            :disabled="currentPage == totalPages"
                                                            class="px-3 py-1 bg-sky-900 text-white cursor-pointer disabled:bg-gray-400 transition-all duration-200 ease-in-out rounded-r-md flex items-center justify-center w-10 h-10"
                                                            :class="{ 'opacity-50': currentPage == totalPages }">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-7 h-7">
                                                                <path d="M9 5l7 7-7 7"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                @endif
                                                </div>
                                                <div class="grid grid-cols-7 gap-0">
                                                    @php
                                                        $dayOfWeekNames = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'];
                                                    @endphp
                                                    @foreach($dayOfWeekNames as $dayName)
                                                        <div class="text-center text-xs font-semibold text-gray-500 py-2">
                                                            {{ $dayName }}
                                                        </div>

                                                    @endforeach
                                                    @foreach($month['days'] as $dia)
                                                        @php
                                                             $heatMapColors = [
                                                                'bg-sky-200 text-sky-800', // 0
                                                                'bg-sky-200 text-sky-800', // 1
                                                                'bg-sky-200 text-sky-800', // 2
                                                                'bg-sky-200 text-sky-800', // 3
                                                                'bg-sky-500 text-white',    // 4
                                                                'bg-sky-500 text-white',    // 5
                                                                'bg-sky-500 text-white',    // 6
                                                                'bg-sky-500 text-white',    // 7
                                                                'bg-sky-800 text-white',    // 8
                                                                'bg-sky-800 text-white',    // 9
                                                                'bg-sky-800 text-white',   // 10
                                                            ];
                                                            $color = !$dia['is_current_month'] ? 'bg-gray-100 text-black' : $heatMapColors[$dia['value'] ?? 0];
                                                        @endphp

                                                        @if($dia['is_current_month'])

                                                            <div
                                                                class="p-0 border rounded text-center cursor-pointer"
                                                                :class="isSelected('{{ $dia['id'] }}') ? 'text-white bg-gray-500' : '{{ $color }}'"
                                                                @click="toggleSelection('{{ $dia['id'] }}')">
                                                                <div class="text-sm font-bold py-5">{{ \Carbon\Carbon::parse($dia['date'])->format('d') }}</div>
                                                            </div>
                                                        @else
                                                            <div
                                                                class="p-0 border rounded text-center cursor-pointer"
                                                                :class="'{{ $color }}'">

                                                            </div>
                                                        @endif

                                                    @endforeach

                                                </div>
                                                <p class="italic text-sm text-gray-500 mt-4">
                                                    Este gráfico tiene como objetivo reflejar los días en los que la demanda es más alta, lo cual puede influir en la probabilidad de que una solicitud sea aceptada, ya que una mayor cantidad de peticiones en esos días podría generar una mayor competencia.
                                                </p>

                                                <div class="mt-6">
                                                    <h3 class="text-lg font-semibold mb-4">Leyenda de colores</h3>
                                                    <div class="grid grid-cols-2 gap-4">

                                                        <div class="flex items-center">
                                                            <div class="w-6 h-6 bg-sky-200 border rounded"></div>
                                                            <span class="ml-2 text-sm text-gray-700">Baja demanda</span>
                                                        </div>

                                                        <div class="flex items-center">
                                                            <div class="w-6 h-6 bg-sky-500 border rounded"></div>
                                                            <span class="ml-2 text-sm text-gray-700">Demanda moderada</span>
                                                        </div>

                                                        <div class="flex items-center">
                                                            <div class="w-6 h-6 bg-sky-800 border rounded"></div>
                                                            <span class="ml-2 text-sm text-gray-700">Alta demanda</span>
                                                        </div>

                                                        <div class="flex items-center">
                                                            <div class="w-6 h-6 bg-gray-100 border rounded"></div>
                                                            <span class="ml-2 text-sm text-gray-700">Días fuera del mes actual</span>
                                                        </div>

                                                        <div class="flex items-center">
                                                            <div class="w-6 h-6 bg-gray-500 border rounded"></div>
                                                            <span class="ml-2 text-sm text-gray-700">Día seleccionado</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <input type="hidden" name="questions[{{ $index }}][answer]" :value="JSON.stringify(selectedDays)">
                                            </div>

                                        @endforeach
                                    </div>
                                    @break

                                @case(6)
                                    <!-- Pregunta Texto Libre -->
                                    <div class="mt-2">
                                        <textarea name="questions[{{ $index }}][answer]" id="text-{{ $index }}" rows="4"
                                                  class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                  placeholder="Escribe tu respuesta aquí..." required></textarea>
                                    </div>
                                    @break

                                @case(7)
                                    <!-- Pregunta de Opción Múltiple -->
                                    <div class="mt-2">
                                        @foreach($question->options as $optionIndex => $option)
                                            <div class="flex items-center mb-3">
                                                <input type="checkbox" name="questions[{{ $index }}][answer][]" id="checkbox-{{ $index }}-{{ $optionIndex }}"
                                                       value="{{ $option->value }}" class="form-checkbox text-indigo-600 focus:ring-indigo-500">
                                                <label for="checkbox-{{ $index }}-{{ $optionIndex }}" class="ml-3 text-gray-700">
                                                    {{ $option->value }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    @break

                                @case(8)
                                    <!-- Pregunta Numérica -->
                                    <div class="mt-2">
                                        <input type="number" name="questions[{{ $index }}][answer]" id="number-{{ $index }}"
                                               class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                               placeholder="Introduce un número" required />
                                    </div>
                                    @break

                                @case(9)
                                    <!-- Pregunta de Carga de Archivo -->
                                    <div class="mt-2">
                                        <label for="file-{{ $index }}" class="block text-sm font-medium text-gray-800">Subir Archivo</label>
                                        <input type="file" name="questions[{{ $index }}][answer]" id="file-{{ $index }}"
                                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    @break
                                @default
                                    <!-- Tipo de pregunta no definido -->
                                    <div class="mt-2 text-red-600">
                                        El tipo de pregunta no está configurado correctamente.
                                    </div>
                            @endswitch

                            <!-- Mostrar errores -->
                            <x-forms.error name="questions[{{ $index }}][answer]" />
                        </div>
                    @endforeach
                </div>

                <!-- Mostrar errores debajo del campo -->
                @error("questions.$index.answer")
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror

                <!-- Botones de Acción -->
                <div class="mt-8 flex justify-between">
                    <button type="button" id="open-cancel-modal" class="btn bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded shadow-md">
                        Cancelar
                    </button>
                    <button type="button" id="open-submit-modal" class="btn bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded shadow-md">
                        Enviar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de Confirmación para Enviar -->
    <div id="submit-modal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-md w-full">
            <h2 class="text-lg font-semibold text-gray-800">¿Estás seguro de enviar este formulario?</h2>
            <p class="text-gray-600 mt-2">No podrás modificar las respuestas después de enviarlas.</p>
            <div class="mt-6 flex justify-end space-x-4">
                <button id="close-submit-modal" class="btn bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-2 px-4 rounded">
                    Cancelar
                </button>
                <button id="confirm-submit" class="btn bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded">
                    Confirmar
                </button>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación para Cancelar -->
    <div id="cancel-modal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-md w-full">
            <h2 class="text-lg font-semibold text-gray-800">¿Estás seguro de que deseas cancelar?</h2>
            <p class="text-gray-600 mt-2">Se perderán todas las respuestas realizadas en el formulario.</p>
            <div class="mt-6 flex justify-end space-x-4">
                <button id="close-cancel-modal" class="btn bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-2 px-4 rounded">
                    Volver
                </button>
                <a href="/formularios" class="btn bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded">
                    Salir
                </a>
            </div>
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
                    dateFormat: "Y-m-d",
                    defaultDate: element.value.split(", "), // Carga valores anteriores
                    locale: {
                        firstDayOfWeek: 0, // Lunes
                        weekdays: {
                            shorthand: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
                            longhand: ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'],
                        },
                        months: {
                            shorthand: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                            longhand: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                        },
                    }
                });
            });

            // Inicializar Flatpickr para fechas múltiples
            document.querySelectorAll('.multi-date-picker').forEach(function (element) {
                flatpickr(element, {
                    mode: "multiple",
                    dateFormat: "Y-m-d",
                    defaultDate: element.value.split(", "), // Carga valores anteriores
                    locale: {
                        firstDayOfWeek: 0, // Lunes
                        weekdays: {
                            shorthand: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
                            longhand: ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'],
                        },
                        months: {
                            shorthand: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                            longhand: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                        },
                    }
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Modal de Confirmación para Enviar
            const submitModal = document.getElementById('submit-modal');
            const openSubmitModalButton = document.getElementById('open-submit-modal');
            const closeSubmitModalButton = document.getElementById('close-submit-modal');
            const confirmSubmitButton = document.getElementById('confirm-submit');
            const submitForm = document.getElementById('submit-form');

            openSubmitModalButton.addEventListener('click', () => {
                submitModal.classList.remove('hidden');
            });

            closeSubmitModalButton.addEventListener('click', () => {
                submitModal.classList.add('hidden');
            });

            confirmSubmitButton.addEventListener('click', () => {
                submitForm.submit();
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
