<x-layout :title="'Respuestas de Formularios'">
    <div class="container mx-auto py-10 px-6">
        <!-- Encabezado -->
        <div class="text-center mb-10">
            <h1 class="text-4xl font-extrabold text-gray-900 drop-shadow-lg tracking-tight">Respuestas de Formularios</h1>
            <p class="text-lg text-gray-700 mt-2">Explora las respuestas de los usuarios organizadas por formulario.</p>
        </div>

        <!-- Filtros -->
        <div class="bg-gray-50 border border-gray-200 rounded-lg shadow-md p-6 mb-6">
            <form action="{{ route('forms.answers') }}" method="GET" class="flex flex-wrap items-center gap-4">
                <!-- Filtro por título del formulario -->
                <div class="w-full md:w-1/4">
                    <label for="date-from" class="block text-sm font-medium text-gray-600">Título</label>
                    <input type="text" name="title" placeholder="Buscar por título"
                           value="{{ request('title') }}"
                           class="w-full p-3 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400">
                </div>

                <!-- Filtro por fechas con Flatpickr -->
                <div class="w-full md:w-1/4">
                    <label for="date-from" class="block text-sm font-medium text-gray-600">Desde</label>
                    <input type="text" name="date_from" id="date-from" value="{{ request('date_from') }}"
                           class="flatpickr w-full p-3 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400">
                </div>
                <div class="w-full md:w-1/4">
                    <label for="date-to" class="block text-sm font-medium text-gray-600">Hasta</label>
                    <input type="text" name="date_to" id="date-to" value="{{ request('date_to') }}"
                           class="flatpickr w-full p-3 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400">
                </div>

                <!-- Filtro por usuario -->
                <div class="w-full md:w-1/4">
                    <label for="user_id" class="block text-sm font-medium text-gray-600">Usuario</label>
                    <select name="user_id" id="user_id"
                            class="w-full p-3 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Todos los usuarios</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtro por sección -->
                <div class="w-full md:w-1/4">
                    <label for="section_id" class="block text-sm font-medium text-gray-600">Sección</label>
                    <select name="section_id" id="section_id"
                            class="w-full p-3 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="" {{ request('section_id') === null ? 'selected' : '' }}>Todas las secciones</option>
                        @foreach($sections as $section)
                            @if($section['id'] != 0)
                                <option value="{{ $section['id'] }}" {{ request('section_id') == $section['id'] ? 'selected' : '' }}>
                                    {{ $section['name'] }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <!-- Filtro por formularios activos -->
                <div class="flex items-center mt-4">
                    <label class="text-sm text-gray-600 font-medium flex items-center">
                        <input type="checkbox" name="active" {{ request('active') ? 'checked' : '' }}
                        class="mr-2 focus:ring-blue-500 focus:border-blue-500">
                        Solo formularios activos
                    </label>
                </div>

                <!-- Botón de búsqueda -->
                <div class="w-full md:w-auto mt-4">
                    <button type="submit"
                            class="w-full md:w-auto bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded shadow-lg">
                        Buscar
                    </button>
                </div>
            </form>
        </div>

        <!-- Resultados -->
        @if($formularios->isEmpty())
            <p class="text-center text-gray-500 text-lg">No hay respuestas disponibles en este momento.</p>
        @else
            <div class="space-y-8">
                @foreach($formularios as $formulario)
                    @php
                        $hasResponses = $formulario->questions->some(function ($question) {
                            return $question->results->isNotEmpty();
                        });
                    @endphp

                    @if(!$hasResponses)
                        @continue
                    @endif

                    <div class="bg-white border border-gray-200 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $formulario->title }}</h2>
                        <p class="text-gray-600 text-sm mb-4">{{ $formulario->summary }}</p>

                        <!-- Preguntas y Respuestas -->
                        <div class="bg-gray-50 rounded-md p-4">
                            <h3 class="text-xl font-semibold text-gray-800">Preguntas y Respuestas</h3>
                            @foreach($formulario->questions as $question)
                                @if($question->results->isNotEmpty())
                                    <div class="mt-4">
                                        <p class="font-medium text-gray-700">{{ $question->title }}</p>
                                        <ul class="mt-2 space-y-2">
                                            @foreach($question->results as $result)
                                                <li class="bg-white border border-gray-200 p-3 rounded-md shadow">
                                                    @if($question->id_question_type == 9)
                                                        <a href="{{ route('file.download', $result->file->id) }}" class="text-blue-500 hover:underline">
                                                            Descargar archivo: {{ $result->file->name }}
                                                        </a>
                                                        <img src="{{ route('file.show', $result->file->id) }}" alt="{{ $result->file->name }}" class="mt-4 max-w-full h-auto rounded-lg shadow-md">
                                                    @else
                                                        <p class="text-gray-900 font-medium">{{ $result->respuesta }}</p>
                                                    @endif
                                                    <p class="text-sm text-gray-500 mt-1">
                                                        Respondido por: <span class="font-semibold">{{ $result->user->name }}</span>
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
               class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-6 rounded-md shadow-md">
                Volver
            </a>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            flatpickr('.flatpickr', {
                dateFormat: "Y-m-d",
            });
        });
    </script>
</x-layout>
