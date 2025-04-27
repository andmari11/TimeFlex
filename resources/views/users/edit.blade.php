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
                    <x-forms.label for="section_id">Sección</x-forms.label>
                    <select class="w-full flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md py-1.5 px-3" name="section_id" id="section_id" required>
                        <option value="{{ old('section_id', $user->section) }}">{{$user->section->name}}</option>
                        @foreach(auth()->user()->company->sections as $section)
                            @if($section->id!=$user->section->id)
                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                            @endif
                        @endforeach
                    </select>
                    <x-forms.error name="section_id" />
                </x-forms.field>

                <x-forms.field class="col-12">
                    <x-forms.label for="user_weight">Prioridad en la asignación de turnos y vacaciones</x-forms.label>
                    <input type="range" name="user_weight" id="user_weight" min="1" max="10" value="{{ old('user_weight', $user->weight) }}" class="w-full">
                    <span id="weight_value">{{ old('user_weight', $user->weight) }}</span>
                    <x-forms.error name="user_weight" />
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

                <a href="/menu" class="text-sm font-semibold leading-6 text-gray-900">Cancelar</a>
                <button form="delete-form"  class=" bg-red-600 hover:bg-red-200 rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm">Eliminar</button>
                <x-forms.button>Actualizar información</x-forms.button>
            </div>
        </form>
        <form method="POST" action="/users/{{$user->id}}/delete" id="delete-form" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>
</x-layout>

<script>
    document.getElementById('user_weight').addEventListener('input', function () {
        document.getElementById('weight_value').textContent = this.value;
    });
</script>
