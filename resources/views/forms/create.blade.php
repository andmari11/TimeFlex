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
                    <x-forms.error name="questions" />
                    <div id="questions-container">
                        <div class="question-template bg-gray-50 border border-gray-200 rounded-lg p-6 mb-4">
                            <div class="mb-4">
                                <label for="questions[0][title]" class="block text-lg font-medium text-gray-700">Título de la Pregunta</label>
                                <input type="text" name="questions[0][title]" id="questions[0][title]" required
                                       class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <x-forms.error name="questions.0.title" />
                            </div>

                            <div>
                                <label for="questions[0][id_question_type]" class="block text-lg font-medium text-gray-700">Tipo de Pregunta</label>
                                <select name="questions[0][id_question_type]" id="questions[0][id_question_type]" required
                                        class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        onchange="showQuestionFields(this, 0); showQuestionSlider(this, 0);">
                                    <option value="" disabled selected>Selecciona el tipo de pregunta</option>
                                    @foreach(\App\Models\QuestionType::all() as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                                <x-forms.error name="questions.0.id_question_type" />
                                <x-forms.error :name="'questions.0.options'" />
                            </div>

                            <!-- Contenedor dinámico para las opciones -->
                            <div id="question-fields-0" class="mt-4"></div>
                            <!-- Contenedor dinámico para el slider -->
                            <div id="question-slider-0" class="mt-4"></div>
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

            // Actualizar los nombres e IDs de los inputs y selects
            template.querySelectorAll('input, select').forEach(function(input) {
                input.name = input.name.replace('[0]', `[${index}]`);
                input.id = input.id.replace('[0]', `[${index}]`);
                input.value = '';
            });

            // Actualizar los IDs de los contenedores dinámicos
            template.querySelector('#question-fields-0').id = `question-fields-${index}`;
            template.querySelector('#question-slider-0').id = `question-slider-${index}`;
            const fieldsContainer = template.querySelector(`#question-fields-${index}`);
            const sliderContainer = template.querySelector(`#question-slider-${index}`);

            fieldsContainer.id = `question-fields-${index}`;
            fieldsContainer.innerHTML = ''; // Limpia contenido heredado
            sliderContainer.id = `question-slider-${index}`;
            sliderContainer.innerHTML = ''; // Limpia contenido heredado

            // Configurar el evento onchange para manejar tanto opciones como sliders
            const select = template.querySelector('select');
            select.setAttribute('onchange', `showQuestionFields(this, ${index}); showQuestionSlider(this, ${index});`);

            container.appendChild(template);
        });

        function showQuestionFields(select, index) {
            // Identificar los contenedores dinámicos
            const fieldsContainer = document.getElementById(`question-fields-${index}`);
            const sliderContainer = document.getElementById(`question-slider-${index}`);

            // Limpiar cualquier contenido previo en los contenedores
            if (fieldsContainer) fieldsContainer.innerHTML = '';
            if (sliderContainer) sliderContainer.innerHTML = '';

            switch (select.value) {
                case '1': // Tipo sin campos adicionales
                case '3':
                case '6':
                case '8':
                case '9':
                    fieldsContainer.innerHTML = `<p class="text-gray-500 italic mt-2">Este tipo de pregunta no requiere campos adicionales.</p>`;
                    break;

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

                case '4': // Tipo "Turnos"
                case '5': // Tipo "Vacaciones"
                    sliderContainer.innerHTML = `
                <label class="block text-lg font-medium text-gray-700">Selecciona un valor (1 a 10)</label>
                <input type="range" name="questions[${index}][value]" min="1" max="10" value="5"
                    class="mt-1 block w-full">
                <span class="block text-center text-lg font-semibold text-blue-700 mt-2">5</span>
            `;

                    // Actualizar dinámicamente el valor del slider
                    const slider = sliderContainer.querySelector('input[type="range"]');
                    const output = sliderContainer.querySelector('span');
                    slider.oninput = function () {
                        output.innerHTML = this.value;
                    };
                    break;

                case '7': // Tipo "Opción Múltiple"
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
                    fieldsContainer.innerHTML = `<p class="text-gray-500 italic mt-2">Este tipo de pregunta no requiere campos adicionales.</p>`;
                    break;
            }
        }


        function showQuestionSlider(select, index) {
            const sliderContainer = document.getElementById(`question-slider-${index}`);
            sliderContainer.innerHTML = ''; // Limpiar cualquier contenido previo

            // Mostrar el slider solo para los tipos "Turnos" (id = 4) o "Vacaciones" (id = 5)
            if (select.value == 4 || select.value == 5) {
                const label = document.createElement('label');
                label.innerHTML = "Selecciona un valor (1 a 10)";
                label.className = "block text-lg font-medium text-gray-700";

                const slider = document.createElement('input');
                slider.type = "range";
                slider.name = `questions[${index}][value]`;
                slider.min = "1";
                slider.max = "10";
                slider.value = "5";
                slider.className = "mt-1 block w-full";

                const output = document.createElement('span');
                output.className = "block text-center text-lg font-semibold text-blue-700 mt-2";
                output.innerHTML = slider.value;

                slider.oninput = function () {
                    output.innerHTML = this.value;
                };

                sliderContainer.appendChild(label);
                sliderContainer.appendChild(slider);
                sliderContainer.appendChild(output);
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

    </script>


    <script>
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
    <script>
        function showQuestionSlider(select, index) {
            // Obtener el contenedor del slider dinámico
            const sliderContainer = document.getElementById(`question-slider-${index}`);
            sliderContainer.innerHTML = ''; // Limpiar cualquier contenido previo

            // Mostrar el slider solo para los tipos "Turnos" (id = 4) o "Vacaciones" (id = 5)
            if (select.value == 4 || select.value == 5) {
                // Crear el label descriptivo
                const label = document.createElement('label');
                label.innerHTML = "Selecciona un valor (1 a 10)";
                label.className = "block text-lg font-medium text-gray-700";

                // Crear el slider (input tipo range)
                const slider = document.createElement('input');
                slider.type = "range";
                slider.name = `questions[${index}][value]`;
                slider.min = "1";
                slider.max = "10";
                slider.value = "5";
                slider.className = "mt-1 block w-full";

                // Crear el output para mostrar el valor seleccionado
                const output = document.createElement('span');
                output.className = "block text-center text-lg font-semibold text-blue-700 mt-2";
                output.innerHTML = slider.value;

                // Actualizar el valor dinámicamente al mover el slider
                slider.oninput = function () {
                    output.innerHTML = this.value;
                };

                // Agregar los elementos al contenedor del slider
                sliderContainer.appendChild(label);
                sliderContainer.appendChild(slider);
                sliderContainer.appendChild(output);
            }
        }
    </script>

</x-layout>
