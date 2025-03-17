<x-layout :title="'Editar Horario'">
    <div class="flex items-center justify-center">
        <form method="POST" action="/horario/{{ $schedule->id }}/edit" enctype="multipart/form-data" class="w-full max-w-md bg-white p-8 rounded-lg shadow-md mt-10">
            @csrf
            @method('PATCH')
            <div class="space-y-1 row">
                <x-forms.field class="col-12">
                    <x-forms.label for="name">Título del horario</x-forms.label>
                    <x-forms.input name="name" id="name" :value="old('name', $schedule->name)" required />
                    <x-forms.error name="name" />
                </x-forms.field>
                <x-forms.field class="col-12">
                    <x-forms.label for="description">Descripción</x-forms.label>
                    <x-forms.input name="description" id="description" :value="old('description', $schedule->description)" required />
                    <x-forms.error name="description" />
                </x-forms.field>
                <x-forms.field class="col-12">
                    <x-forms.label for="section_id">Sección</x-forms.label>
                    <select name="section_id" id="section_id" class="w-full flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md py-1.5 px-3" required>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" {{ $section->id == old('section_id', $schedule->section_id) ? 'selected' : '' }}>{{ $section->name }}</option>
                        @endforeach
                    </select>
                    <x-forms.error name="section_id" />
                </x-forms.field>
                <x-forms.field class="col-12">
                    <x-forms.label for="start_date">Fecha de inicio</x-forms.label>
                    <input type="text" name="start_date" id="start_date" value="{{ old('start_date') }}" class="w-full flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md py-1.5 px-3" required>
                    <x-forms.error name="start_date" />
                </x-forms.field>
                <x-forms.field class="col-12">
                    <x-forms.label for="end_date">Fecha de fin</x-forms.label>
                    <input type="text" name="end_date" id="end_date" value="{{ old('end_date') }}" class="w-full flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md py-1.5 px-3" required>
                    <x-forms.error name="end_date" />
                </x-forms.field>
                <x-forms.field class="col-12">
                    <a href="/shifts-create" class="rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">Añadir turnos</a>
                </x-forms.field>
            </div>
            <div class="mt-6 flex items-center justify-between">
                <a href="/horario" class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Cancelar</a>
                <x-forms.button>Actualizar horario</x-forms.button>
            </div>
        </form>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            flatpickr("#start_date");

            flatpickr("#end_date");
        });
    </script>
</x-layout>
