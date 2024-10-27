<x-layout :title="'Registro'">
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
            </div>

            <div class="mt-6 flex items-center justify-between">
                <a href="/menu" class="text-sm font-semibold leading-6 text-gray-900">Cancelar</a>
                <x-forms.button>Registrar sección</x-forms.button>
            </div>
        </form>
    </div>
</x-layout>
