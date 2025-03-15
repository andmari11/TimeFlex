<x-layout :title="'Registro'">
    <div class=" flex items-center justify-center ">
        <form method="POST" action="/horario-registrar" enctype="multipart/form-data" class="w-full max-w-md bg-white p-8 rounded-lg shadow-md mt-10">
            @csrf
            <div class="space-y-1 row" >


                <x-forms.field class="col-12">
                    <x-forms.label for="name">Título del horario</x-forms.label>
                    <x-forms.input name="name" id="name" :value="old('name')" required />
                    <x-forms.error name="name" />
                </x-forms.field>
                <x-forms.field class="col-12">
                    <x-forms.label for="description">Descripción</x-forms.label>
                    <textarea class="w-full flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md py-1.5 px-3" name="description" id="description" required></textarea>
                    <x-forms.error name="decription" />
                </x-forms.field>
                <x-forms.field class="col-12">
                    <x-forms.label for="section_id">Sección</x-forms.label>
                    <select name="section_id" id="section_id" class="w-full flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md py-1.5 px-3" required>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                    <x-forms.error name="section_id" />
                </x-forms.field>
            </div>

            <div class="mt-6 flex items-center justify-between">
                <a href="/menu" class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600'">Cancelar</a>
                <x-forms.button>Registrar horario</x-forms.button>
            </div>
        </form>
    </div>
</x-layout>
