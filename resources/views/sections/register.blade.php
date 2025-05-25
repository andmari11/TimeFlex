<x-layout :title="'Registro'">
    <x-page-heading>Menú de creación de sección</x-page-heading>

    <div class=" flex items-center justify-center ">
        <form method="POST" action="/register-section" enctype="multipart/form-data" class="w-full max-w-md bg-white p-8 rounded-lg shadow-md mt-10">
            @csrf
            <div class="space-y-1 row" >

                <!-- Preguntar el tipo de empresa (docencia, sanidad, etc)-->
                <x-forms.field class="col-12">
                    <x-forms.label for="name">Nombre de la sección</x-forms.label>
                    <x-forms.input name="name" id="name" :value="old('name')" required />
                    <x-forms.error name="name" />
                </x-forms.field>

                <!-- Horas mínimas -->
                <x-forms.field class="col-12">
                    <x-forms.label for="min_hours">Horas mínimas</x-forms.label>
                    <x-forms.input type="number" name="min_hours" id="min_hours" :value="old('min_hours')" required min="0" />
                    <x-forms.error name="min_hours" />
                </x-forms.field>

                <!-- Horas máximas -->
                <x-forms.field class="col-12">
                    <x-forms.label for="max_shifts">Horas máximas</x-forms.label>
                    <x-forms.input type="number" name="max_hours" id="max_hours" :value="old('max_hours')" required min="0" />
                    <x-forms.error name="max_hours" />
                </x-forms.field>

                <!-- Turnos mínimas -->
                <x-forms.field class="col-12">
                    <x-forms.label for="min_hours">Turnos mínimos</x-forms.label>
                    <x-forms.input type="number" name="min_shifts" id="min_shifts" :value="old('min_shifts')" required min="0" />
                    <x-forms.error name="min_shifts" />
                </x-forms.field>

                <!-- Turnos máximas -->
                <x-forms.field class="col-12">
                    <x-forms.label for="max_shifts">Turnos máximos</x-forms.label>
                    <x-forms.input type="number" name="max_shifts" id="max_shifts" :value="old('max_shifts')" required min="0" />
                    <x-forms.error name="max_shifts" />
                </x-forms.field>
            </div>

            <div class="mt-6 flex items-center justify-between">
                <a href="/menu" class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600'">Cancelar</a>
                <x-forms.button>Registrar sección</x-forms.button>
            </div>
        </form>
    </div>
</x-layout>
