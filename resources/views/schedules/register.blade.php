<x-layout :title="'Registro'">
    <div class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
        <h1 class="text-4xl text-center font-bold text-gray-800 mt-10 mb-5">Crear Nuevo Horario</h1>
        <form method="POST" action="/horario-registrar" enctype="multipart/form-data"
              class="bg-white border border-gray-200 rounded-lg shadow-lg p-8 max-w-4xl w-full mx-auto mt-10 mb-20">
            @csrf

            <div class="space-y-1 row">

                <x-forms.field class="col-12">
                    <label for="name" class="block text-lg font-medium text-gray-700">Título del horario</label>
                    <input name="name" id="name" value="{{ old('name') }}" required
                           class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"/>
                    <x-forms.error name="name" />
                </x-forms.field>

                <x-forms.field class="col-12">
                    <label for="description" class="block text-lg font-medium text-gray-700">Descripción</label>
                    <textarea class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                              name="description" id="description" required></textarea>
                    <x-forms.error name="description" />
                </x-forms.field>

                <x-forms.field class="col-12">
                    <label for="section_id" class="block text-lg font-medium text-gray-700">Sección</label>
                    <select name="section_id" id="section_id"
                            class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            required>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                    <x-forms.error name="section_id" />
                </x-forms.field>

                <x-forms.field class="col-12">
                    <label for="start_date" class="block text-lg font-medium text-gray-700">Fecha de inicio</label>
                    <input type="text" name="start_date" id="start_date" value="{{ old('start_date') }}"
                           class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <x-forms.error name="start_date" />
                </x-forms.field>

                <x-forms.field class="col-12">
                    <label for="end_date" class="block text-lg font-medium text-gray-700">Fecha de fin</label>
                    <input type="text" name="end_date" id="end_date" value="{{ old('end_date') }}"
                           class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <x-forms.error name="end_date" />
                </x-forms.field>

            </div>

            <div class="mt-10 flex items-center justify-between">
                <a href="/menu"
                   class="rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Cancelar
                </a>
                <x-forms.button>Registrar horario</x-forms.button>
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
