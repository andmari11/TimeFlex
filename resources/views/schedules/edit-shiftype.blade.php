<x-layout :title="'Editar Turno'">
    <div class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
        <form method="POST" action="/horario/{{$schedule->id}}/edit/shift-type/{{$shiftType->id}}/edit" enctype="multipart/form-data" class="bg-white border border-gray-200 rounded-lg shadow-lg p-8 max-w-4xl w-full mx-auto mt-10 mb-20">
            @csrf
            @method('PATCH')
            <div class="space-y-1 row" >

                <x-forms.field class="col-12">
                    <label for="start" class="block text-lg font-medium text-gray-700">Día y hora de inicio de turno</label>
                    <input type="text" name="start" id="start" value="{{ old('start') ?? $shiftType->start}}" class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <x-forms.error name="start" />
                </x-forms.field>
                <x-forms.field class="col-12">
                    <label for="end" class="block text-lg font-medium text-gray-700">Día y hora de fin de turno</label>
                    <input type="text" name="end" id="end" value="{{ old('end')?? $shiftType->end }}" class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <x-forms.error name="end" />
                </x-forms.field>
                <x-forms.field class="col-12">
                    <label for="notes" class="block text-lg font-medium text-gray-700">Descripción de turno</label>
                    <input class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" name="notes" id="notes" value="{{$shiftType->notes}}" required></input>
                    <x-forms.error name="notes" />
                </x-forms.field>
                <x-forms.field class="col-12">
                    <label for="users_needed" class="block text-lg font-medium text-gray-700">Trabajadores necesarios</label>
                    <input name="users_needed" id="users_needed" type="number" min="1" value="{{old('users_needed') ?? $shiftType->users_needed}}" class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required />
                    <x-forms.error name="users_needed" />
                </x-forms.field>
                <x-forms.field class="col-12">
                    <label for="period" class="block text-lg font-medium text-gray-700">Periodo</label>
                    <select class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            name="period"
                            id="period"
                            required>
                        @foreach([0 => 'Una sola vez', 1 => 'Diario', 2 => 'Semanal', 3 => 'Mensual', 4 => 'Anual'] as $key => $label)
                            <option value="{{ $key }}" {{ $key == (old('period') ?? $shiftType->period) ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    <x-forms.error name="period" />
                </x-forms.field>
                <x-forms.field class="col-12">
                    <div class="flex items-center space-x-3">
                        <input type="checkbox"
                               name="weekends_excepted"
                               id="weekends_excepted"
                               class="w-4 h-4 rounded border-gray-400 text-indigo-600 shadow-sm focus:ring-indigo-500"
                               value="1"
                            {{ (old('weekends_excepted') ?? $shiftType->weekends_excepted) ? 'checked' : '' }}>
                        <label for="weekends_excepted" class="block text-lg font-medium text-gray-700">Evitar fines de semana</label>
                    </div>

                    <x-forms.error name="weekends_excepted" />
                </x-forms.field>
                <x-forms.field class="col-12">
                    <label for="workers" class="block text-lg font-medium text-gray-700">Asignar trabajadores</label>
                    <select class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            name="workers[]"
                            id="workers"
                            multiple>
                        @foreach($schedule->section->users as $worker)
                            <option value="{{ $worker->id }}" {{ in_array($worker->id, old('workers', [])) ? 'selected' : '' }}>
                                {{ $worker->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-forms.error name="workers" />
                </x-forms.field>
            </div>

            <div class="mt-6 flex items-center justify-between">
                <a href="/horario" class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600'">Cancelar</a>
                <x-forms.button>Actualizar turno</x-forms.button>
            </div>
        </form>
    </div>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            flatpickr("#start", {
                enableTime: true, // Habilitar selección de tiempo
                dateFormat: "Y-m-d H:i", // Formato de fecha y hora (datetime)
                time_24hr: true, // Usar formato de 24 horas
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

            flatpickr("#end", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true,
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
    </script>
</x-layout>
