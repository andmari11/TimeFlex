<x-layout :title="'Editar sección'">
    <x-page-heading>Menú de sección {{$section->name}}</x-page-heading>
    <div class=" flex items-center justify-center ">
        <form method="POST" action="/sections/{{$section->id}}/edit" enctype="multipart/form-data" class="w-full max-w-md bg-white p-8 rounded-lg shadow-md mt-10">
            @csrf
            @method('PATCH')
            <div class="space-y-1 row" >

                @if(!$section->default)
                    <x-forms.field class="col-12">
                        <x-forms.label for="name">Nombre</x-forms.label>
                        <x-forms.input name="name" placeholder="{{$section->name}}" id="name" value="{{old('name',$section->name)}}" required />
                        <x-forms.error name="name" />
                    </x-forms.field>

                    <x-forms.field class="col-12">
                        <x-forms.label for="min_hours">Horas mínimas</x-forms.label>
                        <x-forms.input type="number" name="min_hours" id="min_hours" value="{{old('min_hours', $section->min_hours)}}" required min="0" />
                        <x-forms.error name="min_hours" />
                    </x-forms.field>

                    <x-forms.field class="col-12">
                        <x-forms.label for="max_hours">Horas máximas</x-forms.label>
                        <x-forms.input type="number" name="max_hours" id="max_hours" value="{{old('max_hours', $section->max_hours)}}" required min="0" />
                        <x-forms.error name="max_hours" />
                    </x-forms.field>

                    <x-forms.field class="col-12">
                        <x-forms.label for="min_shifts">Turnos mínimos</x-forms.label>
                        <x-forms.input type="number" name="min_shifts" id="min_shifts" value="{{old('min_shifts', $section->min_shifts)}}" required min="0" />
                        <x-forms.error name="min_shifts" />
                    </x-forms.field>

                    <x-forms.field class="col-12">
                        <x-forms.label for="max_shifts">Turnos máximos</x-forms.label>
                        <x-forms.input type="number" name="max_shifts" id="max_shifts" value="{{old('max_shifts', $section->max_shifts)}}" required min="0" />
                        <x-forms.error name="max_shifts" />
                    </x-forms.field>
                @endif
            </div>
            <div class="mt-6 flex items-center justify-between">

                <a href="/menu" class="text-sm font-semibold leading-6 text-gray-900">Cancelar</a>
                <button form="delete-form"  class=" bg-red-600 hover:bg-red-200 rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm">Eliminar</button>
                <x-forms.button>Actualizar información</x-forms.button>
            </div>
        </form>
        <form method="POST" action="/sections/{{$section->id}}/delete" id="delete-form" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>
</x-layout>
