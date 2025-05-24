<x-layout :title="'Formularios disponibles'">
    <div class="container mx-auto py-10 px-6">
        <div class="text-center mb-10">
            <h1 class="text-4xl font-bold text-gray-800">Formularios Disponibles</h1>
            <p class="text-gray-600 mt-2">Explora y gestiona los formularios disponibles según tu rol.</p>
        </div>

        @if(auth()->user()->role === 'admin')
            <div class="flex justify-end space-x-4 mb-6">
                <!-- Botón Crear Formulario -->
                <a href="/formularios/create" class="btn bg-red-500 hover:bg-red-600 text-white font-semibold py-3 px-6 rounded-md shadow-md">
                    + Crear nuevo formulario
                </a>

                <!-- Botón Ver Respuestas -->
                <a href="{{ route('forms.answers') }}" class="btn bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-6 rounded-md shadow-md">
                    Ver respuestas de formularios
                </a>
            </div>
        @endif

        @if($formularios->isEmpty())
            <p class="text-center text-gray-500 text-lg">No hay formularios disponibles en este momento.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($formularios as $formulario)
                    <div class="bg-white border border-gray-200 rounded-lg shadow hover:shadow-lg transition-shadow duration-300">
                        <div class="p-6 flex flex-col justify-between h-full">
                            <h2 class="text-2xl font-semibold text-gray-800">{{ $formulario->title }}</h2>
                            <p class="text-gray-600 mt-3">{{ $formulario->summary }}</p>
                            <p class="text-sm text-gray-500 mt-4">
                                <strong>Desde:</strong> {{ \Carbon\Carbon::parse($formulario->start_date)->format('d/m/Y H:i') }}<br>
                                <strong>Hasta:</strong> {{ \Carbon\Carbon::parse($formulario->end_date)->format('d/m/Y H:i') }}
                            </p>

                            @if(auth()->user()->role === 'admin')
                                <!-- Mostrar secciones asignadas -->
                                <p class="text-sm text-gray-500 mt-4">
                                    <strong>Secciones asignadas:</strong>
                                <ul class="list-disc list-inside text-gray-600 mt-1">
                                    @foreach($formulario->sections as $section)
                                        <li>{{ $section->name }}</li>
                                    @endforeach
                                </ul>
                                </p>
                            @endif

                            <!-- Botones de acción -->
                            <div class="mt-6">
                                @php
                                    $currentDate = now();
                                    $startDate = \Carbon\Carbon::parse($formulario->start_date);
                                    $endDate = \Carbon\Carbon::parse($formulario->end_date);
                                    $userId = auth()->user()->id;
                                    $hasAnswered = \App\Models\Result::where('id_user', $userId)->where('id_form', $formulario->id)->exists();
                                @endphp

                                @if(!$hasAnswered && $currentDate->between($startDate, $endDate))
                                    <!-- Botón Contestar -->
                                    <a href="{{ route('forms.show', $formulario->id) }}" class="btn bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded shadow-md">
                                        Contestar
                                    </a>
                                @elseif($hasAnswered)
                                    <!-- Botón Ver Respuestas -->
                                    <a href="{{ route('forms.showresults', $formulario->id) }}" class="btn bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded shadow-md">
                                        Ver respuestas
                                    </a>
                                @else
                                    <p class="text-red-500 font-semibold">No disponible actualmente</p>
                                @endif
                            </div>

                            <!-- Opciones de administración -->
                            @if(auth()->user()->role === 'admin')
                                <div class="mt-4 flex justify-between items-center border-t border-gray-300 pt-4 space-x-4">
                                    <!-- Botón Editar -->
                                    <a href="{{ route('forms.edit', $formulario->id) }}" class="text-blue-500 hover:text-blue-700 font-semibold text-sm">
                                        Editar
                                    </a>

                                    <!-- Botón Duplicar -->
                                    <button type="button" onclick="showDuplicatePopup({{ $formulario->id }})"
                                            class="text-purple-500 hover:text-purple-700 font-semibold text-sm">
                                        Duplicar
                                    </button>

                                    <!-- Botón Eliminar -->
                                    <button type="button" onclick="showDeletePopup({{ $formulario->id }})"
                                            class="text-red-500 hover:text-red-700 font-semibold text-sm">
                                        Eliminar
                                    </button>
                                </div>

                                <!-- Modal para Duplicar -->
                                <div id="duplicate-modal-{{ $formulario->id }}" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
                                    <div class="bg-white w-96 rounded-lg shadow-lg p-6">
                                        <h2 class="text-lg font-semibold text-gray-800">Duplicar Formulario</h2>
                                        <p class="text-gray-600 mt-2">
                                            ¿Estás seguro de que deseas duplicar este formulario? Se creará una copia con un nuevo ID.
                                        </p>

                                        <!-- Formulario para duplicar, se incluyen los campos para las fechas -->
                                        <form action="{{ route('forms.duplicate', $formulario->id) }}" method="POST">
                                            @csrf

                                            <!-- Campo para Fecha de Inicio -->
                                            <div class="mt-4">
                                                <label for="start_date" class="block text-sm font-medium text-gray-800">Fecha de inicio</label>
                                                <input id="start_date" name="start_date" type="date"
                                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                            </div>

                                            <!-- Campo para Fecha de Fin -->
                                            <div class="mt-4">
                                                <label for="end_date" class="block text-sm font-medium text-gray-800">Fecha de fin</label>
                                                <input id="end_date" name="end_date" type="date"
                                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                            </div>

                                            <div class="mt-4 flex justify-between">
                                                <!-- Botón Cancelar -->
                                                <button onclick="closeDuplicatePopup({{ $formulario->id }})" type="button"
                                                        class="btn bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded">
                                                    Cancelar
                                                </button>

                                                <!-- Botón Confirmar Duplicación -->
                                                <button type="submit"
                                                        class="btn bg-purple-500 hover:bg-purple-600 text-white font-semibold py-2 px-4 rounded shadow-md">
                                                    Duplicar
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <!-- Modal para Eliminar -->
                                <div id="delete-modal-{{ $formulario->id }}" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
                                    <div class="bg-white w-96 rounded-lg shadow-lg p-6">
                                        <h2 class="text-lg font-semibold text-gray-800">Eliminar Formulario</h2>
                                        <p class="text-gray-600 mt-2">¿Estás seguro de que deseas eliminar este formulario? Esta acción no se puede deshacer.</p>

                                        <div class="mt-4 flex justify-between">
                                            <!-- Botón Cancelar -->
                                            <button onclick="closeDeletePopup({{ $formulario->id }})"
                                                    class="btn bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded">
                                                Cancelar
                                            </button>

                                            <!-- Botón Confirmar Eliminación -->
                                            <form action="{{ route('forms.destroy', $formulario->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded shadow-md">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    @if(!$formularios->isEmpty())
        <div class="py-8">
            {{ $formularios->links() }}
        </div>
    @endif
</x-layout>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        flatpickr("#start_date", {
            dateFormat: "Y-m-d H:i",
            enableTime: true,
            time_24hr: true
        });

        flatpickr("#end_date", {
            dateFormat: "Y-m-d H:i",
            enableTime: true,
            time_24hr: true
        });
    });
</script>


<script>

    function disableScroll() {
        document.body.style.overflow = 'hidden';
    }

    function enableScroll() {
        document.body.style.overflow = '';
    }

    function showDuplicatePopup(formId) {
        document.getElementById(`duplicate-modal-${formId}`).classList.remove('hidden');
        disableScroll();
    }

    function closeDuplicatePopup(formId) {
        document.getElementById(`duplicate-modal-${formId}`).classList.add('hidden');
        enableScroll();
    }

    function showDeletePopup(formId) {
        document.getElementById(`delete-modal-${formId}`).classList.remove('hidden');
        disableScroll();
    }

    function closeDeletePopup(formId) {
        document.getElementById(`delete-modal-${formId}`).classList.add('hidden');
        enableScroll();
    }

</script>
