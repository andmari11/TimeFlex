<x-layout :title="'Editar Horario'">
    <div class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
        <h1 class="text-4xl text-center font-bold text-gray-800 mt-10 mb-5">Editar Horario</h1>
        <form method="POST" action="/horario/{{ $schedule->id }}/edit" enctype="multipart/form-data"
              class="bg-white border border-gray-200 rounded-lg shadow-lg p-8 max-w-4xl w-full mx-auto mt-10 mb-20">
            @csrf
            @method('PATCH')
            <div class="space-y-1 row">

                <x-forms.field class="col-12">
                    <label for="name" class="block text-lg font-medium text-gray-700">Título del horario</label>
                    <input name="name" id="name" value="{{ old('name', $schedule->name) }}" required
                           class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"/>
                    <x-forms.error name="name" />
                </x-forms.field>

                <x-forms.field class="col-12">
                    <label for="description" class="block text-lg font-medium text-gray-700">Descripción</label>
                    <input name="description" id="description" value="{{ old('description', $schedule->description) }}" required
                           class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"/>
                    <x-forms.error name="description" />
                </x-forms.field>

                <x-forms.field class="col-12">
                    <label for="section_id" class="block text-lg font-medium text-gray-700">Sección</label>
                    <select name="section_id" id="section_id"
                            class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            required>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" {{ $section->id == old('section_id', $schedule->section_id) ? 'selected' : '' }}>
                                {{ $section->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-forms.error name="section_id" />
                </x-forms.field>

                <x-forms.field class="col-12">
                    <label for="start_date" class="block text-lg font-medium text-gray-700">Fecha de inicio</label>
                    <input type="text" name="start_date" id="start_date" value="{{ $schedule->start_date }}"
                           class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <x-forms.error name="start_date" />
                </x-forms.field>

                <x-forms.field class="col-12">
                    <label for="end_date" class="block text-lg font-medium text-gray-700">Fecha de fin</label>
                    <input type="text" name="end_date" id="end_date" value="{{ $schedule->end_date }}"
                           class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <x-forms.error name="end_date" />
                </x-forms.field>

                <x-forms.field class="col-12">
                    <a href="/horario/{{$schedule->id}}/edit/shift-type/create"
                       class="rounded-md bg-green-600 px-3 py-2 text-lg font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">
                        Añadir turnos
                    </a>
                </x-forms.field>

            </div>

            <div class="mt-10 flex items-center justify-between">
                <a href="/horario"
                   class="rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Cancelar
                </a>
                <x-forms.button>Actualizar horario</x-forms.button>
            </div>
        </form>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('#start_date, #end_date').forEach(function (element) {
            flatpickr(element, {
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
    });
</script>
</x-layout>
