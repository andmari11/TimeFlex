<x-layout :title="'Registro'">
    <div class=" flex items-center justify-center ">
        <form method="POST" action="/register-company" enctype="multipart/form-data" class="w-full max-w-md bg-white p-8 rounded-lg shadow-md mt-10">
            @csrf
            <div class="space-y-1 row" >
                <x-forms.field class="col-12">
                    <x-forms.label for="companyName">Nombre de empresa</x-forms.label>
                    <x-forms.input name="companyName" id="companyName" :value="old('companyName')" required />
                    <x-forms.error name="companyName" />
                </x-forms.field>

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
                    <x-forms.label for="password">Contraseña</x-forms.label>
                        <x-forms.input name="password" id="password" type="password" required />
                        <x-forms.error name="password" />
                </x-forms.field>

                <x-forms.field class="col-12">
                    <x-forms.label for="password_confirmation">Confirma contraseña</x-forms.label>
                        <x-forms.input name="password_confirmation" id="password_confirmation" type="password" required />
                        <x-forms.error name="password_confirmation" />
                </x-forms.field>


            </div>
            <div class="mt-6 flex items-center justify-between">
                <a href="/" class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600'">Cancelar</a>
                <x-forms.button>Registrar empresa</x-forms.button>
            </div>
        </form>
    </div>
</x-layout>
