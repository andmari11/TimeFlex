<x-layout :title="'Editar Formulario'">
    <div class="container mx-auto py-10 px-6">
        <div class="text-center mb-10">
            <h1 class="text-4xl font-bold text-gray-800">Editar Formulario</h1>
            <p class="text-gray-600 mt-2">Actualiza los detalles del formulario, edita las preguntas y la sección.</p>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg shadow-lg p-8 max-w-4xl mx-auto">
            <form id="edit-form" action="{{ route('forms.update', $formulario->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Título -->
                <div class="mb-6">
                    <label for="title" class="block text-lg font-medium text-gray-700">Título</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $formulario->title) }}" required
                           class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <x-forms.error name="title" />
                </div>

                <!-- Resumen -->
                <div class="mb-6">
                    <label for="summary" class="block text-lg font-medium text-gray-700">Resumen</label>
                    <textarea name="summary" id="summary" rows="3" required
                              class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('summary', $formulario->summary) }}</textarea>
                    <x-forms.error name="summary" />
                </div>

                <!-- Fechas -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="start_date" class="block text-lg font-medium text-gray-700">Fecha de Inicio</label>
                        <input type="text" name="start_date" id="start_date" value="{{ old('start_date', $formulario->start_date) }}" required
                               class="flatpickr mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <x-forms.error name="start_date" />
                    </div>
                    <div>
                        <label for="end_date" class="block text-lg font-medium text-gray-700">Fecha de Finalización</label>
                        <input type="text" name="end_date" id="end_date" value="{{ old('end_date', $formulario->end_date) }}" required
                               class="flatpickr mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <x-forms.error name="end_date" />
                    </div>
                </div>

                <!-- Secciones -->
                <div class="mb-6 mt-6">
                    <label for="id_sections" class="block text-lg font-medium text-gray-700">Secciones del Formulario</label>
                    <select name="id_sections[]" id="id_sections" multiple required
                            class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @foreach(\App\Models\Section::all() as $section)
                            <option value="{{ $section->id }}" @if(in_array($section->id, $formulario->sections->pluck('id')->toArray())) selected @endif>
                                {{ $section->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-sm text-gray-500 mt-2">* Mantén presionada la tecla Ctrl (o Cmd en Mac) para seleccionar múltiples secciones.</p>
                    <x-forms.error name="id_sections" />
                </div>

                <!-- Preguntas -->
                <div class="mt-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Preguntas</h2>
                    <div id="questions-container">
                        @foreach($formulario->questions as $index => $question)
                            <div class="question-template bg-gray-50 border border-gray-200 rounded-lg p-6 mb-4">
                                <input type="hidden" name="questions[{{ $index }}][id]" value="{{ $question->id }}">

                                <div class="mb-4">
                                    <label for="questions[{{ $index }}][title]" class="block text-lg font-medium text-gray-700">Título de la Pregunta</label>
                                    <input type="text" name="questions[{{ $index }}][title]" id="questions[{{ $index }}][title]" value="{{ old('questions.'.$index.'.title', $question->title) }}" required
                                           class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <x-forms.error name="questions[{{ $index }}][title]" />
                                </div>

                                <div class="mb-4">
                                    <label for="questions[{{ $index }}][id_question_type]" class="block text-lg font-medium text-gray-700">Tipo de Pregunta</label>
                                    <select name="questions[{{ $index }}][id_question_type]" id="questions[{{ $index }}][id_question_type]" required
                                            class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            onchange="showQuestionFields(this, {{ $index }})">
                                        <option value="" disabled>Selecciona el tipo de pregunta</option>
                                        @foreach(\App\Models\QuestionType::all() as $type)
                                            <option value="{{ $type->id }}" @if($type->id == $question->id_question_type) selected @endif>{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                    <x-forms.error name="questions[{{ $index }}][id_question_type]" />
                                </div>
                                <div id="question-fields-{{ $index }}">
                                    @if($question->id_question_type == 2)
                                        @foreach($question->options as $optionIndex => $option)
                                            <input type="text" name="questions[{{ $index }}][options][{{ $optionIndex }}][id]" value="{{ $option->id }}" hidden>
                                            <input type="text" name="questions[{{ $index }}][options][{{ $optionIndex }}][value]" value="{{ $option->value }}" placeholder="Opción {{ $optionIndex + 1 }}" required
                                                   class="mt-2 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" id="add-question"
                            class="btn bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded shadow-md mt-4">
                        + Agregar Pregunta
                    </button>
                </div>

                <!-- Botones -->
                <div class="mt-10 flex justify-between">
                    <button type="button" id="open-cancel-modal"
                            class="btn bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-2 px-4 rounded shadow-md">
                        Cancelar
                    </button>
                    <button type="button" id="open-update-modal"
                            class="btn bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded shadow-md">
                        Actualizar Formulario
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de Confirmación para Actualizar -->
    <div id="update-modal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-md w-full">
            <h2 class="text-lg font-semibold text-gray-800">¿Estás seguro de actualizar este formulario?</h2>
            <p class="text-gray-600 mt-2">Los cambios realizados se guardarán permanentemente.</p>
            <div class="mt-6 flex justify-end space-x-4">
                <button id="close-update-modal"
                        class="btn bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-2 px-4 rounded">
                    Cancelar
                </button>
                <button id="confirm-update"
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
                <a href="/formularios"
                   class="btn bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded">
                    Salir
                </a>
            </div>
        </div>
    </div>

    <!-- Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            flatpickr(".flatpickr", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true
            });
        });

        // Funciones para manejo dinámico de preguntas
        document.getElementById('add-question').addEventListener('click', function () {
            const container = document.getElementById('questions-container');
            const index = container.children.length;
            const template = document.querySelector('.question-template').cloneNode(true);

            // Limpiar valores y actualizar nombres dinámicos
            template.querySelectorAll('input, select').forEach(function (input) {
                input.name = input.name.replace(/\[\d+\]/, `[${index}]`);
                input.id = input.id.replace(/\[\d+\]/, `[${index}]`);
                input.value = '';
            });

            template.querySelector('select').setAttribute('onchange', `showQuestionFields(this, ${index})`);
            template.querySelector('[id^=question-fields-]').id = `question-fields-${index}`;

            // Agregar nueva pregunta al contenedor
            container.appendChild(template);
        });


        function showQuestionFields(select, index) {
            // Obtener el contenedor dinámico para los campos adicionales
            const fieldsContainer = document.getElementById(`question-fields-${index}`);
            fieldsContainer.innerHTML = ''; // Limpiar cualquier contenido previo

            switch (select.value) {
                case '2': // Si el tipo de pregunta es "Selector" (ID = 2)
                    // Crear campos dinámicos iniciales para las opciones
                    fieldsContainer.innerHTML = `
                <div class="flex items-center gap-2 mb-2">
                    <input type="text" name="questions[${index}][options][]" placeholder="Opción 1"
                        class="block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <button type="button" onclick="addOption(${index})"
                        class="text-blue-500 hover:text-blue-700 font-semibold">
                        + Agregar Opción
                    </button>
                </div>
            `;
                    break;

                default:
                    // Si no se requiere campo dinámico, no se genera contenido
                    fieldsContainer.innerHTML = `<p class="text-gray-500 italic mt-2">Este tipo de pregunta no requiere campos adicionales.</p>`;
                    break;
            }
        }

        function addOption(index) {
            const fieldsContainer = document.getElementById(`question-fields-${index}`);
            const optionCount = fieldsContainer.querySelectorAll('input').length + 1; // Contar opciones existentes

            // Crear un nuevo contenedor para la opción
            const newOption = document.createElement('div');
            newOption.classList.add('flex', 'items-center', 'gap-2', 'mb-2');
            newOption.innerHTML = `
        <input type="text" name="questions[${index}][options][]" placeholder="Opción ${optionCount}"
            class="block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
        <button type="button" onclick="removeOption(this)"
            class="text-red-500 hover:text-red-700 font-semibold">
            Eliminar
        </button>
    `;

            // Añadir la nueva opción antes del botón principal
            fieldsContainer.appendChild(newOption);
        }

        function removeOption(button) {
            button.parentElement.remove(); // Eliminar el contenedor de la opción
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Variables del DOM
            const updateModal = document.getElementById('update-modal');
            const openUpdateModalButton = document.getElementById('open-update-modal');
            const closeUpdateModalButton = document.getElementById('close-update-modal');
            const confirmUpdateButton = document.getElementById('confirm-update');
            const editForm = document.getElementById('edit-form');

            const cancelModal = document.getElementById('cancel-modal');
            const openCancelModalButton = document.getElementById('open-cancel-modal');
            const closeCancelModalButton = document.getElementById('close-cancel-modal');

            // Abrir el modal para Actualizar
            openUpdateModalButton.addEventListener('click', () => {
                updateModal.classList.remove('hidden'); // Mostrar el modal
            });

            // Cerrar el modal para Actualizar
            closeUpdateModalButton.addEventListener('click', () => {
                updateModal.classList.add('hidden'); // Ocultar el modal
            });

            // Confirmar la acción de Actualizar
            confirmUpdateButton.addEventListener('click', () => {
                editForm.submit(); // Enviar el formulario
            });

            // Abrir el modal para Cancelar
            openCancelModalButton.addEventListener('click', () => {
                cancelModal.classList.remove('hidden'); // Mostrar el modal
            });

            // Cerrar el modal para Cancelar
            closeCancelModalButton.addEventListener('click', () => {
                cancelModal.classList.add('hidden'); // Ocultar el modal
            });
        });
    </script>
</x-layout>
