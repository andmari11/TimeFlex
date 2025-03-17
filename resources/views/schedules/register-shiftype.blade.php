<x-layout :title="'Registro'">
    <div class=" flex items-center justify-center ">
        <form method="POST" action="/horario/{{$schedule->id}}/edit/shift-type/create" enctype="multipart/form-data" class="w-full max-w-md bg-white p-8 rounded-lg shadow-md mt-10">
            @csrf
            <div class="space-y-1 row" >



                <x-forms.field class="col-12">
                    <x-forms.label for="notes">Descripción de turno</x-forms.label>
                    <textarea class="w-full flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md py-1.5 px-3" name="notes" id="notes" required></textarea>
                    <x-forms.error name="notes" />
                </x-forms.field>
                <x-forms.field class="col-12">
                    <x-forms.label for="start">Día y hora de inicio de turno</x-forms.label>
                    <input type="text" name="start" id="start" value="{{ old('start') }}" class="w-full flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md py-1.5 px-3" required>
                    <x-forms.error name="start" />
                </x-forms.field>
                <x-forms.field class="col-12">
                    <x-forms.label for="end">Día y hora de fin de turno</x-forms.label>
                    <input type="text" name="end" id="end" value="{{ old('end') }}" class="w-full flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md py-1.5 px-3" required>
                    <x-forms.error name="end" />
                </x-forms.field>
                <x-forms.field class="col-12">
                    <x-forms.label for="users_needed">Trabajadores necesarios</x-forms.label>
                    <x-forms.input name="users_needed" id="users_needed" :value="old('users_needed')" required />
                    <x-forms.error name="users_needed" />
                </x-forms.field>
                <x-forms.field class="col-12">
                    <x-forms.label for="period">Periodo</x-forms.label>
                    <x-forms.input name="period" id="period" :value="old('period')" required />
                    <x-forms.error name="period" />
                </x-forms.field>

            </div>

            <div class="mt-6 flex items-center justify-between">
                <a href="/horario" class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600'">Cancelar</a>
                <x-forms.button>Registrar turno</x-forms.button>
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
                time_24hr: true // Usar formato de 24 horas
            });

            flatpickr("#end", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true
            });
        });
    </script>
</x-layout>
