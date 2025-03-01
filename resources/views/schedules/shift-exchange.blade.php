
@props([])

<x-layout :title="'Calendario'">
    <x-page-heading> Cambio de turno</x-page-heading>
    <div class="flex justify-between">

        <x-schedules.shift-exchange-calendar class="basis-[50%] flex-grow w-full" :id_shift_mine="$id_shift_mine??null"  :id_shift_someone="$id_shift_someone" :schedule="$schedule" :days="$days" :showButtons="false"></x-schedules.shift-exchange-calendar>
        <div class="basis-[50%] max-w-4xl flex-grow my-10  mr-10 w-30 shadow rounded-xl bg-white p-4 px-6">

            <div class="space-y-1 row my-auto max-w-lg bg-white py-8 mx-auto flex items-center justify-center">

            <form method="POST" action="/shift-exchange" enctype="multipart/form-data" class="w-full max-w-xl mx-auto">
                @csrf

                    <!-- Campo desplegable para seleccionar el turno -->
                    <x-forms.field class="col-12">
                        <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                        <input type="hidden" name="shift_id_someone" value="{{ $id_shift_someone }}">
                        <input type="hidden" name="shift_id_mine" value="{{ $id_shift_mine??null }}">
                        <div class="flex items-center mb-1">
                            <div class="w-6 h-6 bg-sky-900 rounded mr-2"></div>
                            <x-forms.label for="shift">Tu turno antiguo</x-forms.label>
                        </div>
                        <select onchange="window.location.href='/shift-exchange/{{$schedule->id}}/turno/' + {{$id_shift_someone}} + '/'+ this.value"
                                class="w-full flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 py-1.5 px-3" name="shift" id="shift" required>
                            <option value="0">
                                <p class="pb-2">Selecciona tu turno a cambiar</p>
                            </option>
                            @foreach($userShifts as $shift)
                                <option value="{{ $shift->id }}" {{ $shift->id == ($id_shift_mine??null) ? 'selected' : '' }}>
                                    <p class="pb-2"> <?php echo e(\Carbon\Carbon::parse($shift->start)->format('d/m/Y')); ?> (<?php echo e(\Carbon\Carbon::parse($shift->start)->format('H:i')); ?> - <?php echo e(\Carbon\Carbon::parse($shift->end)->format('H:i')); ?>)</p>
                                </option>
                            @endforeach
                        </select>
                        <x-forms.error name="shift1" />
                    </x-forms.field>
                <x-forms.field class="col-12">
                    <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                    <input type="hidden" name="shift_id_someone" value="{{ $id_shift_someone }}">
                    <input type="hidden" name="shift_id_mine" value="{{ $id_shift_mine??null }}">
                    <div class="flex items-center mb-1">
                        <div class="w-6 h-6 bg-sky-400 rounded mr-2"></div>
                        <x-forms.label for="shift">Tu turno nuevo</x-forms.label>
                    </div>
                    <select onchange="window.location.href='/shift-exchange/{{$schedule->id}}/turno/' + this.value + '/'+ {{$id_shift_mine??0}}"
                            class="w-full flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 py-1.5 px-3" name="shift" id="shift" required>
                        <option value="0">
                            <p class="pb-2">Selecciona el turno que deseas</p>
                        </option>
                        @foreach($availableShifts as $shift)
                            <option value="{{ $shift->id }}" {{ $shift->id == ($id_shift_someone??null) ? 'selected' : '' }}>
                                <p class="pb-2"> <?php echo e(\Carbon\Carbon::parse($shift->start)->format('d/m/Y')); ?> (<?php echo e(\Carbon\Carbon::parse($shift->start)->format('H:i')); ?> - <?php echo e(\Carbon\Carbon::parse($shift->end)->format('H:i')); ?>)</p>
                            </option>
                        @endforeach
                    </select>
                    <x-forms.error name="shift2" />
                </x-forms.field>
                    <x-forms.field class="col-12">
                        <x-forms.label for="reason">Motivo del cambio</x-forms.label>
                        <textarea class="w-full flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 py-1.5 px-3" name="reason" id="reason" required></textarea>
                        <x-forms.error name="reason" />
                    </x-forms.field>
                    <div class="mt-6 px-4 flex items-center justify-between">
                        <a href="/horario/{{$schedule->id}}/turno/' + {{$id_shift_someone}}" class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Cancelar</a>
                        @if(isset($id_shift_mine) && isset($id_shift_someone) && $id_shift_mine!=0 && $id_shift_someone!=0)
                            <x-forms.button formaction="/shift-exchange">Registrar cambio de turno</x-forms.button>
                        @endif
                    </div>
            </form>
        </div>
        </div>
    </div>
</x-layout>

