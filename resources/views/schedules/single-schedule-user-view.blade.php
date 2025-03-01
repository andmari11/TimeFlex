
<x-layout :title="'Calendario'">
    <x-page-heading> Horario de {{ $schedule->section->name }}</x-page-heading>
    <div class="flex justify-between">

        <x-schedules.single-schedule-calendar class="basis-[50%] flex-grow" :schedule="$schedule" :days="$days" :showButtons="false"></x-schedules.single-schedule-calendar>
        <div class="basis-[70%] flex-grow mb-10 flex-column align-items-center justify-content-center mt-10 p-4 mr-10 w-30 shadow rounded-lg bg-white">
            <div class="flex justify-end">
                <!--<h2 class="text-2xl font-bold mb-4">Calendario de equipo de :</h2>-->
                <div class="flex space-x-0 mb-8">
                    <a href="/horario/{{ $schedule->id }}" class="bg-sky-900 text-white text-s font-semibold py-2 px-4 rounded-l focus:outline-none">Horario de equipo</a>
                    <a href="/horario/personal/{{ $schedule->id  }}" class="bg-gray-200 text-black text-s font-semibold py-2 px-4  focus:outline-none">Horario personal</a>
                    <a href="/stats" class="bg-gray-200 text-black text-s font-semibold py-2 px-4 rounded-r focus:outline-none">Estadísticas</a>

                </div>
            </div>
            @if ($userToView)
                <div class="mx-auto w-full">
                    <x-users.employee-section :employee="$userToView"></x-users.employee-section>
                </div>
                @if ($userToView->id !== auth()->user()->id)
                    <div class="m-6">
                        @foreach($usersShifts as $shift)
                            <div class="p-4 bg-blue-50 mb-4 shadow rounded-xl my-1 relative max-w-lg mx-auto">
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
                                    <a href="/shift-exchange/{{$schedule->id}}/turno/{{$shift->id}}" class="w-full bg-blue-600 hover:bg-blue-500 text-center px-4 py-2 rounded-xl text-white text-bold transition-all duration-300">
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
