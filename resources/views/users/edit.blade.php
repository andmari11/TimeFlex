<x-layout :title="'Editar usuario'">
    <x-page-heading>Menú de edición del usuario {{$user->name}}</x-page-heading>
    <div class=" flex items-center justify-center ">
        <form method="POST" action="/users/{{$user->id}}/edit" enctype="multipart/form-data" class="w-full max-w-md bg-white p-8 rounded-lg shadow-md mt-10">
            @csrf
            @method('PATCH')
            <div class="space-y-1 row" >

                <!-- Preguntar el tipo de empresa (docencia, sanidad, etc)-->
                <x-forms.field class="col-12">
                    <x-forms.label for="name">Nombre</x-forms.label>
                    <x-forms.input name="name" placeholder="{{$user->name}}" id="name" value="{{old('name',$user->name)}}" required />
                    <x-forms.error name="name" />
                </x-forms.field>

                <x-forms.field class="col-12">
                    <x-forms.label for="email">Email</x-forms.label>
                    <x-forms.input name="email" placeholder="{{$user->email}}" id="email" type="email" value="{{ old('email', $user->email) }}" required />
                    <x-forms.error name="email" />
                </x-forms.field>

                <x-forms.field class="col-12">
                    <x-forms.label for="password">Contraseña</x-forms.label>
                    <x-forms.input name="password" id="password" type="password" />
                    <x-forms.error name="password" />
                </x-forms.field>

                <x-forms.field class="col-12">
                    <x-forms.label for="password_confirmation">Confirma contraseña</x-forms.label>
                    <x-forms.input name="password_confirmation" id="password_confirmation" type="password" />
                    <x-forms.error name="password_confirmation" />
                </x-forms.field>
                <input type="hidden" name="role" value="{{$user->role}}">
                <input type="hidden" name="company_id" value="{{$user->company_id}}">

            </div>
            <div class="mt-6 flex items-center justify-between">
                <a href="/shifts" class="text-sm font-semibold leading-6 text-gray-900">Cancelar</a>
                <x-forms.button>Actualizar información</x-forms.button>
            </div>
        </form>
    </div>
</x-layout>
