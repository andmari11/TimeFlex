<x-layout :title="'Respuestas de Formularios'">
    <div class="container mx-auto py-10 px-6">
        <div class="text-center mb-10">
            <h1 class="text-4xl font-bold text-gray-800">Respuestas de Formularios</h1>
            <p class="text-gray-600 mt-2">Explora las respuestas de los usuarios organizadas por formulario.</p>
        </div>

        <div class="mb-6">
            <form action="{{ route('forms.answers') }}" method="GET" class="flex flex-wrap items-center gap-4">
                <!-- Filtro por título del formulario -->
                <div>
                    <input type="text" name="title" placeholder="Buscar por título"
                           value="{{ request('title') }}"
                           class="p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Filtro por fecha -->
                <div>
                    <label for="date-from" class="text-sm text-gray-600">Desde:</label>
                    <input type="date" name="date_from" id="date-from" value="{{ request('date_from') }}"
                           class="p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="date-to" class="text-sm text-gray-600">Hasta:</label>
                    <input type="date" name="date_to" id="date-to" value="{{ request('date_to') }}"
                           class="p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <select name="user_id" class="p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos los usuarios</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>

                <label>
                    <input type="checkbox" name="active" {{ request('active') ? 'checked' : '' }}>
                    Solo formularios activos
                </label>

                <div>
                    <select name="section_id" class="p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="" {{ request('section_id') === null ? 'selected' : '' }}>Todas las secciones</option>
                        @foreach($sections as $section)
                            <option value="{{ $section['id'] }}" {{ request('section_id') == $section['id'] ? 'selected' : '' }}>
                                {{ $section['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>



                <!-- Botón de búsqueda -->
                <div>
                    <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded shadow-md">
                        Buscar
                    </button>
                </div>
            </form>
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
