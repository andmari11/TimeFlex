<x-layout :title="'Crear Nuevo Formulario'">
    <div class="container mx-auto py-10 px-6">
        <div class="text-center mb-10">
            <h1 class="text-4xl font-bold text-gray-800">Crear Nuevo Formulario</h1>
            <p class="text-gray-600 mt-2">Configura los detalles del formulario y agrega preguntas dinámicamente.</p>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg shadow-lg p-8 max-w-4xl mx-auto">
            <form id="create-form" action="/register-form" method="POST">
            @csrf
                <input type="hidden" name="id_user" value="{{ auth()->user()->id }}">

                <!-- Título -->
                <div class="mb-6">
                    <label for="title" class="block text-lg font-medium text-gray-700">Título del Formulario</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                           class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <x-forms.error name="title" />
                </div>

                <!-- Resumen -->
                <div class="mb-6">
                    <label for="summary" class="block text-lg font-medium text-gray-700">Resumen del Formulario</label>
                    <textarea name="summary" id="summary" rows="3" required
                              class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('summary') }}</textarea>
                    <x-forms.error name="summary" />
                </div>

                <!-- Fechas -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="start_date" class="block text-lg font-medium text-gray-700">Comienzo del plazo de respuesta</label>
                        <input type="text" name="start_date" id="start_date" value="{{ old('start_date') }}" required
                               class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <x-forms.error name="start_date" />
                    </div>
                    <div>
                        <label for="end_date" class="block text-lg font-medium text-gray-700">Fin del plazo de respuesta</label>
                        <input type="text" name="end_date" id="end_date" value="{{ old('end_date') }}" required
                               class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <x-forms.error name="end_date" />
                    </div>
                </div>

                <!-- Secciones -->
                <div class="mb-6 mt-6">
                    <label for="id_sections" class="block text-lg font-medium text-gray-700">Secciones del Formulario</label>
                    <select name="id_sections[]" id="id_sections" multiple required
                            class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @foreach(\App\Models\Section::all() as $section)
                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                    <p class="text-sm text-gray-500 mt-2">* Mantén presionada la tecla Ctrl (o Cmd en Mac) para seleccionar múltiples secciones.</p>
                    <x-forms.error name="id_sections" />
                </div>

                <!-- Contenedor de Preguntas -->
                <div class="mt-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Preguntas</h2>
                    <div id="questions-container">
                        <div class="question-template bg-gray-50 border border-gray-200 rounded-lg p-6 mb-4">
                            <div class="mb-4">
                                <label for="questions[0][title]" class="block text-lg font-medium text-gray-700">Título de la Pregunta</label>
                                <input type="text" name="questions[0][title]" id="questions[0][title]" required
                                       class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <x-forms.error name="questions[0][title]" />
                            </div>

                            <div>
                                <label for="questions[0][id_question_type]" class="block text-lg font-medium text-gray-700">Tipo de Pregunta</label>
                                <select name="questions[0][id_question_type]" id="questions[0][id_question_type]" required
                                        class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        onchange="showQuestionFields(this, 0)">
                                    <option value="" disabled selected>Selecciona el tipo de pregunta</option>
                                    @foreach(\App\Models\QuestionType::all() as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                                <x-forms.error name="questions[0][id_question_type]" />
                            </div>

                            <!-- Contenedor dinámico para las opciones -->
                            <div id="question-fields-0" class="mt-4"></div>
                        </div>
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
                    <button type="button" id="open-create-modal"
                            class="btn bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded shadow-md">
                        Crear Formulario
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de Confirmación para Crear -->
    <div id="create-modal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-md w-full">
            <h2 class="text-lg font-semibold text-gray-800">¿Estás seguro de crear este formulario?</h2>
            <div class="mt-6 flex justify-end space-x-4">
                <button id="close-create-modal" class="btn bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-2 px-4 rounded">
                    Cancelar
                </button>
                <button id="confirm-create" class="btn bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded">
                    Confirmar
                </button>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación para Cancelar -->
    <div id="cancel-modal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-md w-full">
            <h2 class="text-lg font-semibold text-gray-800">¿Estás seguro de que deseas cancelar?</h2>
            <p class="text-gray-600 mt-2">Se perderán todos los cambios realizados en el formulario.</p>
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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- Script para manejo dinámico de preguntas -->
    <script>

        document.getElementById('add-question').addEventListener('click', function() {
            const container = document.getElementById('questions-container');
            const index = container.children.length;
            const template = document.querySelector('.question-template').cloneNode(true);

            template.querySelectorAll('input, select').forEach(function(input) {
                input.name = input.name.replace('[0]', `[${index}]`);
                input.id = input.id.replace('[0]', `[${index}]`);
                input.value = '';
            });

            template.querySelector('#question-fields-0').id = `question-fields-${index}`;
            template.querySelector('select').setAttribute('onchange', `showQuestionFields(this, ${index})`);

            container.appendChild(template);
        });

        function showQuestionFields(select, index) {
            const fieldsContainer = document.getElementById(`question-fields-${index}`);
            fieldsContainer.innerHTML = ''; // Limpiar cualquier contenido previo

            switch (select.value) {
                case '2': // Tipo "Selector"
                    fieldsContainer.innerHTML = `
                <div class="flex flex-col gap-4">
                    <div class="flex items-center gap-2 mb-2">
                        <input type="text" name="questions[${index}][options][]" placeholder="Opción 1"
                            class="block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <button type="button" onclick="addOption(${index})"
                            class="text-blue-500 hover:text-blue-700 font-semibold">
                            + Agregar
                        </button>
                    </div>
                </div>
            `;
                    break;
                case '7': //tipo opción múltiple
                    fieldsContainer.innerHTML = `
                <div class="flex flex-col gap-4">
                    <div class="flex items-center gap-2 mb-2">
                        <input type="text" name="questions[${index}][options][]" placeholder="Opción 1"
                            class="block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <button type="button" onclick="addOption(${index})"
                            class="text-blue-500 hover:text-blue-700 font-semibold">
                            + Agregar
                        </button>
                    </div>
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
            const optionCount = fieldsContainer.querySelectorAll('input').length + 1;
            const newOption = document.createElement('div');
            newOption.classList.add('flex', 'items-center', 'gap-2', 'mb-2');
            newOption.innerHTML = `
                <input type="text" name="questions[${index}][options][]" placeholder="Opción ${optionCount}"
                    class="block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                <button type="button" onclick="removeOption(this)" class="text-red-500 hover:text-red-700 font-semibold">
                    Eliminar
                </button>
            `;
            fieldsContainer.appendChild(newOption);
        }

        function removeOption(button) {
            button.parentElement.remove();
        }


        document.addEventListener('DOMContentLoaded', function () {
            flatpickr("#start_date", {
                enableTime: true, // Habilitar selección de tiempo
                dateFormat: "Y-m-d H:i", // Formato de fecha y hora (datetime)
                time_24hr: true // Usar formato de 24 horas
            });

            flatpickr("#end_date", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Modal para Crear
            const createModal = document.getElementById('create-modal');
            const openCreateModalButton = document.getElementById('open-create-modal');
            const closeCreateModalButton = document.getElementById('close-create-modal');
            const confirmCreateButton = document.getElementById('confirm-create');
            const createForm = document.getElementById('create-form');

            openCreateModalButton.addEventListener('click', () => {
                createModal.classList.remove('hidden');
            });

            closeCreateModalButton.addEventListener('click', () => {
                createModal.classList.add('hidden');
            });

            confirmCreateButton.addEventListener('click', () => {
                createForm.submit();
            });

            // Modal para Cancelar
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
