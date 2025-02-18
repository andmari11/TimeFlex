<x-layout :title="'Calendario'">
    <x-page-heading> Horario de {{ $schedule->section->name }}</x-page-heading>
    <div class="flex justify-between">

    <x-schedules.single-schedule-calendar class="basis-[50%] flex-grow" :schedule="$schedule" :days="$days"></x-schedules.single-schedule-calendar>
    <div class="basis-[50%] flex-grow mt-8 p-4 mr-10 w-30 shadow rounded-lg bg-white">
        @if ($shiftToView)
            <div class="mx-auto mt-20 flex flex-col justify-center items-center">
                <h3 class="text-xl font-bold mb-3">Turno del {{ \Carbon\Carbon::parse($shiftToView->start)->locale('es')->format('d \d\e F') }}</h3> <!-- Muestra el día de comienzo en español -->
                <p class="pb-2"><strong>Inicio:</strong> {{ \Carbon\Carbon::parse($shiftToView->start)->format('H:i') }}  - {{ \Carbon\Carbon::parse($shiftToView->start)->format('d/m/Y') }}</p>
                <p class="pb-2"><strong>Fin:</strong> {{ \Carbon\Carbon::parse($shiftToView->end)->format('H:i') }} - {{ \Carbon\Carbon::parse($shiftToView->end)->format('d/m/Y') }} </p>

                <p class="pb-2 text-center"><strong>Notas:</strong> {{ $shiftToView->notes ?? 'Sin notas' }}</p>

                <p class="pb-2"><strong>Trabajadores ({{ $shiftToView->users_needed }} usuarios necesarios):</strong></p>
                <ul>
                    @foreach ($shiftToView->users as $user)
                        <li class="ps-4 pb-2">- {{ $user->name }}</li>
                    @endforeach
                </ul>

            </div>
        @endif
    </div>
    </div>
</x-layout>
