<x-layout :title="'Editar sección'">
    <x-page-heading>Menú de sección {{$section->name}}</x-page-heading>
    <div class=" flex items-center justify-center ">
        <form method="POST" action="/sections/{{$section->id}}/edit" enctype="multipart/form-data" class="w-full max-w-md bg-white p-8 rounded-lg shadow-md mt-10">
            @csrf
            @method('PATCH')
            <div class="space-y-1 row" >

                <!-- Preguntar el tipo de empresa (docencia, sanidad, etc)-->
                <x-forms.field class="col-12">
                    <x-forms.label for="name">Nombre</x-forms.label>
                    <x-forms.input name="name" placeholder="{{$section->name}}" id="name" value="{{old('name',$section->name)}}" required />
                    <x-forms.error name="name" />
                </x-forms.field>
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
