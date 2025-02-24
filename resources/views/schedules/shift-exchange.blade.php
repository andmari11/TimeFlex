<x-layout :title="'Cambio de turno'">
    <div class="flex items-center justify-center">
        <form method="POST" action="/register-shift-exchange" enctype="multipart/form-data" class="w-full max-w-md bg-white p-8 rounded-lg shadow-md mt-10">
            @csrf
            <div class="space-y-1 row">
                <!-- Campo desplegable para seleccionar el turno -->
                <x-forms.field class="col-12">
                    <x-forms.label for="shift">Turno que deseas cambiar</x-forms.label>
                    <select class="w-full flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md py-1.5 px-3" name="shift" id="shift" required>
                        @foreach($userShifts as $shift)
                            <option value="{{ $shift->id }}">
                                <p class="pb-2"> <?php echo e(\Carbon\Carbon::parse($shift->start)->format('d/m/Y')); ?> (<?php echo e(\Carbon\Carbon::parse($shift->start)->format('H:i')); ?> - <?php echo e(\Carbon\Carbon::parse($shift->end)->format('H:i')); ?>)</p>
                            </option>
                        @endforeach
                    </select>
                    <x-forms.error name="shift" />
                </x-forms.field>
            </div>

            <div class="mt-6 flex items-center justify-between">
                <a href="/menu" class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Cancelar</a>
                <x-forms.button>Registrar cambio de turno</x-forms.button>
            </div>
        </form>
    </div>
</x-layout>
