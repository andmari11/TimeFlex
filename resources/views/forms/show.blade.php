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
                        <x-forms.label for="questions[{{ $index }}][answer]">{{ $question->title }} (ID Tipo Pregunta: {{ $question->id_question_type }})</x-forms.label>

                        <!-- Campos ocultos para id_question y id_question_type -->
                        <input type="hidden" name="questions[{{ $index }}][id_question]" value="{{ $question->id }}">
                        <input type="hidden" name="questions[{{ $index }}][id_question_type]" value="{{ $question->id_question_type }}">

                        @switch($question->id_question_type)
                            @case(1)
                                <x-forms.input type="date" name="questions[{{ $index }}][answer]" id="questions[{{ $index }}][answer]" required />
                                @break

                            @case(2)
                                <select name="questions[{{ $index }}][answer]" id="questions[{{ $index }}][answer]" required>
                                    @foreach($question->options as $option)
                                        <option value="{{ $option->value }}">{{ $option->value }}</option>
                                    @endforeach
                                </select>
                                @break

                            @case(3)
                                <input type="range" name="questions[{{ $index }}][answer]" id="questions[{{ $index }}][answer]" min="0" max="100" step="1" required>
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
</x-layout>
