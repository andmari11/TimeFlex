<x-layout :title="'Respuestas de Formularios'">
    <div class="container mx-auto py-10 px-6">
        <div class="text-center mb-10">
            <h1 class="text-4xl font-bold text-gray-800">Respuestas de Formularios</h1>
            <p class="text-gray-600 mt-2">Explora las respuestas de los usuarios organizadas por formulario.</p>
        </div>

        @if($formularios->isEmpty())
            <p class="text-center text-gray-500 text-lg">No hay respuestas disponibles en este momento.</p>
        @else
            <div class="space-y-8">
                @foreach($formularios as $formulario)
                    <div class="bg-white border border-gray-200 rounded-lg shadow-lg p-6">
                        <h2 class="text-2xl font-semibold text-gray-800">{{ $formulario->title }}</h2>
                        <p class="text-gray-600 mt-2">{{ $formulario->summary }}</p>

                        <div class="mt-4">
                            <h3 class="text-xl font-semibold text-gray-800">Preguntas y Respuestas</h3>
                            @foreach($formulario->questions as $question)
                                @if($question->results->isNotEmpty())
                                    <div class="mt-4">
                                        <p class="font-medium text-gray-700">{{ $question->title }}</p>
                                        <ul class="mt-2 space-y-2">
                                            @foreach($question->results as $result)
                                                <li class="bg-gray-50 p-3 rounded-md shadow-sm">
                                                    <p class="text-gray-800">{{ $result->respuesta }}</p>
                                                    <p class="text-sm text-gray-500 mt-1">
                                                        Respondido por: {{ $result->user->name }}
                                                    </p>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Botón Volver -->
        <div class="mt-10 text-center">
            <a href="{{ route('forms.index') }}"
               class="btn bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded shadow-md">
                Volver
            </a>
        </div>
    </div>
</x-layout>
