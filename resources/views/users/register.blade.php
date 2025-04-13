<x-layout :title="'Registro'">
    <div class=" flex items-center justify-center ">
        <form method="POST" action="/register-user" enctype="multipart/form-data" class="w-full max-w-md bg-white p-8 rounded-lg shadow-md mt-10">
            @csrf
            <div class="space-y-1 row" >

                <!-- Preguntar el tipo de empresa (docencia, sanidad, etc)-->
                <x-forms.field class="col-12">
                    <x-forms.label for="name">Nombre</x-forms.label>
                    <x-forms.input name="name" id="name" :value="old('name')" required />
                    <x-forms.error name="name" />
                </x-forms.field>

                <x-forms.field class="col-12">
                    <x-forms.label for="email">Email</x-forms.label>
                    <x-forms.input name="email" id="email" type="email" :value="old('email')" required />
                    <x-forms.error name="email" />
                </x-forms.field>

                <x-forms.field class="col-12">
                    <x-forms.label for="section_id">Sección</x-forms.label>
                    <select class="w-full flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md py-1.5 px-3" name="section_id" id="section_id">
                        <option value="section_id" disabled selected>Selecciona una sección</option>
                        @foreach(auth()->user()->company->sections as $section)
                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                    <x-forms.error name="section_id" />
                </x-forms.field>

                <x-forms.field class="col-12">
                    <x-forms.label for="weight">Peso del usuario</x-forms.label>
                    <input type="range" name="weight" id="weight" min="1" max="10" value="{{ old('weight', 5) }}" class="w-full">
                    <span id="weight_value">{{ old('weight', 5) }}</span>
                    <x-forms.error name="weight" />
                </x-forms.field>

                <x-forms.field class="col-12">
                    <x-forms.label for="password">Contraseña</x-forms.label>
                    <x-forms.input name="password" id="password" type="password" required />
                    <x-forms.error name="password" />
                </x-forms.field>

                <x-forms.field class="col-12">
                    <x-forms.label for="password_confirmation">Confirma contraseña</x-forms.label>
                    <x-forms.input name="password_confirmation" id="password_confirmation" type="password" required />
                    <x-forms.error name="password_confirmation" />
                </x-forms.field>

                <input type="hidden" name="role" value="employee" id="role">

            </div>
            <div class="mt-6 flex items-center justify-between">
                <a href="/menu" class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600'">Cancelar</a>
                <x-forms.button>Registrar empleado</x-forms.button>
            </div>
        </form>
    </div>
</x-layout>

<script>
    document.getElementById('weight').addEventListener('input', function () {
        document.getElementById('weight_value').textContent = this.value;
    });
</script>
