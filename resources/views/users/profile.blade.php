<x-layout :title="'Editar Perfil'">
    <x-page-heading>Menú de edición de {{ auth()->user()->name }}</x-page-heading>

    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="w-full max-w-lg bg-white p-8 rounded-lg shadow-md mt-10">
            <h2 class="text-2xl font-bold text-center text-gray-700 mb-6">Actualizar Información</h2>

            <form method="POST" action="{{ route('profileUpdate') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PATCH')

                <x-forms.field class="col-12">
                    <x-forms.label for="name">Nombre</x-forms.label>
                    <x-forms.input name="name" id="name" value="{{ old('name', auth()->user()->name) }}" required />
                    <x-forms.error name="name" />
                </x-forms.field>

                <x-forms.field class="col-12">
                    <x-forms.label for="email">Email</x-forms.label>
                    <x-forms.input name="email" id="email" type="email" value="{{ old('email', auth()->user()->email) }}" required />
                    <x-forms.error name="email" />
                </x-forms.field>

                <x-forms.field class="col-12">
                    <x-forms.label for="password">Nueva Contraseña</x-forms.label>
                    <x-forms.input name="password" id="password" type="password" />
                    <x-forms.error name="password" />
                </x-forms.field>

                <x-forms.field class="col-12">
                    <x-forms.label for="password_confirmation">Confirma Contraseña</x-forms.label>
                    <x-forms.input name="password_confirmation" id="password_confirmation" type="password" />
                    <x-forms.error name="password_confirmation" />
                </x-forms.field>

                <input type="hidden" name="role" value="{{ auth()->user()->role }}">
                <input type="hidden" name="company_id" value="{{ auth()->user()->company_id }}">

                <div class="flex justify-between items-center mt-6">
                    <a href="/menu" class="text-sm font-semibold text-gray-700 hover:text-gray-900">Cancelar</a>
                    <x-forms.button class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md shadow">Actualizar Perfil</x-forms.button>
                </div>
            </form>
        </div>
    </div>
</x-layout>
