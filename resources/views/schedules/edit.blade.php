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

                <x-forms.field class=" w-100 d-flex justify-content-between">
                    <a href="/horario/{{$schedule->id}}/edit/shift-type/create"
                       class="rounded-md bg-blue-900 px-3 py-2 text-lg font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">
                        Añadir turnos
                    </a>
                    <a href="/horario/{{ $schedule->id }}/regenerate-shifts"
                       class="ms-8 rounded-md bg-blue-600 px-3 py-2 text-lg font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                        Regenerar turnos
                    </a>
                </x-forms.field>
                @if($shifttypes->isNotEmpty())
                    <x-forms.field class="col-12">
                        <label class="block text-lg font-medium text-gray-700 mb-3">Turnos</label>

                        <div class="space-y-4">
                            @foreach($shifttypes as $shifttype)
                                <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm flex justify-between items-start">
                                    <div class="space-y-1">
                                        <div class="flex items-start space-x-4">
                                            <p><span class="font-semibold text-gray-800">Inicio:</span> {{ $shifttype->start }}</p>
                                            <p><span class="font-semibold text-gray-800">Fin:</span> {{ $shifttype->end }}</p>
                                        </div>
                                        <p><span class="font-semibold text-gray-800">Personas necesarias:</span> {{ $shifttype->users_needed }}</p>
                                        <p><span class="font-semibold text-gray-800">Periodo:</span> {{ ['Una sola vez', 'Diario', 'Semanal', 'Mensual', 'Anual'][$shifttype->period] }}</p>
                                    </div>
                                    <div class="flex flex-row items-end space-x-2 ml-4">
                                        <a href="/horario/{{$schedule->id}}/edit/shift-type/{{$shifttype->id}}/edit" class="bg-blue-500 px-2 py-1 rounded-xl text-xs text-white">Editar</a>

                                        <button type="button" onclick="openDeleteModal({{ $shifttype->id }})" class="bg-red-600 px-2 py-1 rounded-xl text-xs text-white">Eliminar</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </x-forms.field>

                @endif


                <div class="mt-10 mx-4 flex items-center justify-between">
                    <a href="/horario"
                       class="rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Cancelar
                    </a>
                    <x-forms.button>Actualizar horario</x-forms.button>
                </div>
            </div>
        </form>
        @stack('delete-forms')
    </div>

    <!-- Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 w-96">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Confirmar eliminación</h2>
            <p class="text-gray-600 mb-6">¿Estás seguro de que deseas eliminar este turno? Esta acción no se puede deshacer.</p>
            <div class="flex justify-end space-x-4">
                <button onclick="closeDeleteModal()" class="bg-gray-300 px-4 py-2 rounded-md text-gray-800">Cancelar</button>
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 px-4 py-2 rounded-md text-white">Eliminar</button>
                </form>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        function openDeleteModal(shiftTypeId) {
            const modal = document.getElementById('deleteModal');
            const form = document.getElementById('deleteForm');
            form.action = `/horario/{{ $schedule->id }}/edit/shift-type/${shiftTypeId}/delete`;
            modal.classList.remove('hidden');
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
        }

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
