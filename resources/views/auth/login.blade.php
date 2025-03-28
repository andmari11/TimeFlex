<x-layout :title="'Log in'">
    <div class=" flex items-center justify-center ">
        <form method="POST" action="/login" class="w-full max-w-md bg-white p-8 rounded-lg shadow-md mt-14">
            @csrf

            <div class="space-y-2 row" >
                <div class="border-b border-gray-900/10 pb-12">
                    <x-forms.field class="col-12">
                        <x-forms.label for="email">Email</x-forms.label>

                        <div class="mt-2">
                            <x-forms.input name="email" id="email" type="email" :value="old('email')" required />
                            <x-forms.error name="email" />
                        </div>
                    </x-forms.field>

                    <x-forms.field class="col-12">
                        <x-forms.label for="password">Contraseña</x-forms.label>

                        <div class="mt-2">
                            <x-forms.input name="password" id="password" type="password" required />
                            <x-forms.error name="password" />
                        </div>
                    </x-forms.field>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-between">
                <a href="/" class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600'">Cancelar</a>
                <x-forms.button>Iniciar sesión</x-forms.button>
            </div>
        </form>
    </div>
</x-layout>
