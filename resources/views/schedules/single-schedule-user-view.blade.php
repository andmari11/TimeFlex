<x-layout :title="'Calendario'">
    <x-page-heading> Horario de {{ $schedule->section->name }}</x-page-heading>
    <div class="flex justify-between">

        <x-schedules.single-schedule-calendar class="basis-[50%] flex-grow" :schedule="$schedule" :days="$days"></x-schedules.single-schedule-calendar>
        <div class="basis-[50%] flex-grow mb-10 flex-column align-items-center justify-content-center mt-8 p-4 mr-10 w-30 shadow rounded-lg bg-white">
            @if ($userToView)
            <x-users.employee-section :employee="$userToView"></x-users.employee-section>
                @if ($userToView->id !== auth()->user()->id)
                    <div class="m-6">
                        @foreach($usersShifts as $shift)
                            <div class="p-4 bg-blue-50 mb-6 shadow rounded-xl my-1 relative w-full max-w-lg mx-auto">
                                <div class="absolute top-2 right-2">
                                </div>

                                <h3 class="text-xl font-bold mb-3">
                                    Turno del {{ \Carbon\Carbon::parse($shift->start)->locale('es')->format('d \d\e F') }}
                                </h3>

                                <div class="flex flex-col gap-2">
                                    <p class="pb-2"><strong>Inicio:</strong> {{ \Carbon\Carbon::parse($shift->start)->format('H:i') }} - {{ \Carbon\Carbon::parse($shift->start)->format('d/m/Y') }}</p>
                                    <p class="pb-2"><strong>Fin:</strong> {{ \Carbon\Carbon::parse($shift->end)->format('H:i') }} - {{ \Carbon\Carbon::parse($shift->end)->format('d/m/Y') }}</p>
                                    <p class="pb-2"><strong>Notas:</strong> {{ $shift->notes ?? 'Sin notas' }}</p>
                                </div>

                                <div class="flex flex-col gap-2 mt-4">
                                    <a href="/" class="w-full bg-blue-600 hover:bg-blue-500 text-center px-4 py-2 rounded-xl text-white text-bold transition-all duration-300">
                                        Solicitar cambio de turno
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                @endif


            @endif

        </div>
    </div>
</x-layout>
