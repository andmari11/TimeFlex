<x-layout :title="'Calendario'">
    <x-page-heading> Horario de {{ $schedule->section->name }}</x-page-heading>
    <div class="flex justify-between">

        <x-schedules.single-schedule-calendar class="basis-[50%] flex-grow" :schedule="$schedule" :days="$days"></x-schedules.single-schedule-calendar>
        <div class="basis-[50%] flex-grow mt-8 p-4 mr-10 w-30 shadow rounded-lg bg-white">
            @if ($userToView)
            <x-users.employee-section :employee="$userToView"></x-users.employee-section>
            @if ($userToView->id !== auth()->user()->id)
                <a href="/" class="mt-4 block w-full bg-blue-600 text-white text-center py-3 rounded-lg shadow-md hover:bg-blue-700 transition-all font-bold">
                    Solicitar cambio de turno
                </a>
            @endif


            @endif
        </div>
    </div>
</x-layout>
