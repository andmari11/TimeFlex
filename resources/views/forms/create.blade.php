<x-layout :title="'Crear Nuevo Formulario'">
    <div class="flex items-center justify-center">
        <form action="/register-form" method="POST" class="w-full max-w-md bg-white p-8 rounded-lg shadow-md mt-10">
            @csrf
            <div class="space-y-1 row">


                <input type="hidden" name="id_user" value="{{ auth()->user()->id }}">

                <x-forms.field class="col-12">
                    <x-forms.label for="title">Título</x-forms.label>
                    <x-forms.input name="title" id="title" :value="old('title')" required />
                    <x-forms.error name="title" />
                </x-forms.field>

                <x-forms.field class="col-12">
                    <x-forms.label for="summary">Resumen</x-forms.label>
                    <x-forms.input name="summary" id="summary" required>{{ old('summary') }}</x-forms.input>
                    <x-forms.error name="summary" />
                </x-forms.field>

                <x-forms.field class="col-12">
                    <x-forms.label for="start_date">Fecha de Inicio</x-forms.label>
                    <x-forms.input type="datetime-local" name="start_date" id="start_date" :value="old('start_date')" required />
                    <x-forms.error name="start_date" />
                </x-forms.field>

                <x-forms.field class="col-12">
                    <x-forms.label for="end_date">Fecha de Finalización</x-forms.label>
                    <x-forms.input type="datetime-local" name="end_date" id="end_date" :value="old('end_date')" required />
                    <x-forms.error name="end_date" />
                </x-forms.field>

                <x-forms.field class="col-12">
                    <x-forms.label for="id_section">Sección</x-forms.label>
                    <select name="id_section" id="id_section" required>
                        <option value="" disabled selected>Selecciona una sección</option>
                        @foreach(\App\Models\Section::all() as $section)
                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                    <x-forms.error name="id_section" />
                </x-forms.field>

            </div>

            <div class="mt-6">
                <h2 class="text-xl font-bold">Preguntas</h2>
                <div id="questions-container">
                    <div class="question-template">
                        <x-forms.field class="col-12">
                            <x-forms.label for="questions[0][title]">Título de la Pregunta</x-forms.label>
                            <x-forms.input name="questions[0][title]" id="questions[0][title]" required />
                            <x-forms.error name="questions[0][title]" />
                        </x-forms.field>

                        <x-forms.field class="col-12">
                            <x-forms.label for="questions[0][id_question_type]">Tipo de Pregunta</x-forms.label>
                            <select name="questions[0][id_question_type]" id="questions[0][id_question_type]" required onchange="showQuestionFields(this, 0)">
                                <option value="" disabled selected>Selecciona el tipo de pregunta</option>
                                @foreach(\App\Models\QuestionType::all() as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                            <x-forms.error name="questions[0][id_question_type]" />
                        </x-forms.field>

                        <div id="question-fields-0"></div>
                    </div>
                </div>

                <button type="button" id="add-question" class="btn bg-blue-500 p-2 rounded-lg text-white mt-4 inline-block">Agregar Pregunta</button>
            </div>

            <div class="mt-6 flex items-center justify-between">
                <a href="/formularios" class="text-sm font-semibold leading-6 text-gray-900">Cancelar</a>
                <x-forms.button>Crear</x-forms.button>
            </div>
        </form>
    </div>

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

            template.querySelector('select').setAttribute('onchange', `showQuestionFields(this, ${index})`);
            template.querySelector('#question-fields-0').id = `question-fields-${index}`;

            container.appendChild(template);
        });

        function showQuestionFields(select, index) {
            const fieldsContainer = document.getElementById(`question-fields-${index}`);
            fieldsContainer.innerHTML = '';

            switch (select.value) {
                case '2': // ID del tipo de pregunta 'Selector'
                    fieldsContainer.innerHTML = `
                        <input type="text" name="questions[${index}][options][]" placeholder="Opción 1" required />
                        <input type="text" name="questions[${index}][options][]" placeholder="Opción 2" required />
                        <input type="text" name="questions[${index}][options][]" placeholder="Opción 3" required />
                    `;
                    const addButton = document.createElement('button');
                    addButton.type = 'button';
                    addButton.innerText = 'Agregar Opción';
                    addButton.addEventListener('click', function() {
                        addOption(index);
                    });
                    fieldsContainer.appendChild(addButton);
                    break;
                default:
                    break;
            }
        }

        function addOption(index) {
            const fieldsContainer = document.getElementById(`question-fields-${index}`);
            const newOption = document.createElement('input');
            newOption.type = 'text';
            newOption.name = `questions[${index}][options][]`;
            newOption.placeholder = `Opción ${fieldsContainer.querySelectorAll('input').length + 1}`;
            newOption.required = true;
            fieldsContainer.insertBefore(newOption, fieldsContainer.querySelector('button'));
        }
    </script>
</x-layout>
