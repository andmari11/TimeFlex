
<x-layout :title="'Calendario'">
    <x-page-heading> Horario de {{ $schedule->section->name }}</x-page-heading>
    <div class="flex justify-between">

        <x-schedules.single-schedule-calendar class="basis-[50%] flex-grow" :schedule="$schedule"  :months="$months" :showButtons="false"></x-schedules.single-schedule-calendar>
        <div class="w-full max-w-xl flex-grow mb-10 flex-column align-items-center justify-content-center p-4 mr-10 w-30 shadow rounded-lg bg-white">
            <div class="flex justify-end">
                <!--<h2 class="text-2xl font-bold mb-4">Calendario de equipo de :</h2>-->
                <div class="flex space-x-0 mb-8">
                    <a href="/horario/{{ $schedule->id }}" class="bg-sky-900 text-white text-s font-semibold py-2 px-4 rounded-l focus:outline-none">Horario de equipo</a>
                    <a href="/horario/personal/{{ $schedule->id  }}" class="bg-gray-200 text-black text-s font-semibold py-2 px-4  focus:outline-none">Horario personal</a>
                    <a href="/estadisticashorario" class="bg-gray-200 text-black text-s font-semibold py-2 px-4 rounded-r focus:outline-none">Estadísticas</a>

                </div>
            </div>
            @if ($userToView)
                <div class="px-4 w-full">
                    <x-users.employee-section  :employee="$userToView" :showGraphs="false"></x-users.employee-section>
                </div>
                    <div class="m-6">
                        @foreach($usersShifts as $shift)
                            <div class="p-4 bg-blue-50 mb-4 shadow rounded-xl my-1 relative max-w-lg mx-auto" x-data="{ open_options_menu: false }">

                                <div class="absolute top-2 right-2 py-2">
                                    <button
                                        @click="open_options_menu = !open_options_menu"
                                        type="button"
                                        class="px-3 py-1 text-black rounded-full text-lg  font-bold">
                                        &#x22EE;
                                    </button>

                                </div>


                                <!-- Menú desplegable -->
                                <div x-show="open_options_menu" @click.away="open_options_menu = false"
                                     class="absolute right-0 z-10 mt-2 w-48 bg-white shadow-lg rounded-md ring-1 ring-black ring-opacity-5">

                                    @if($userToView->id!=auth()->user()->id)

                                        <a href="/shift-exchange/{{$schedule->id}}/turno/{{$shift->id}}"
                                           class="block px-4 py-2 text-gray-800 hover:bg-gray-100 text-sm transition-all">
                                        Cambio de turno
                                        </a>
                                    @else
                                        <a href="/shift-exchange/{{$schedule->id}}/turno/0/{{$shift->id}}"
                                           class="block px-4 py-2 text-gray-800 hover:bg-gray-100 text-sm transition-all">
                                        Cambio de turno
                                        </a>
                                    @endif
                                </div>

                                <h3 class="text-xl font-bold mb-3 ">
                                    <a  class="hover:cursor-pointer hover:text-sky-900 "  href="/horario/{{ $shift->schedule->id }}/turno/{{ $shift->id }}">Turno del {{ \Carbon\Carbon::parse($shift->start)->locale('es')->format('d \d\e F') }}</a>
                                </h3>

                                <div class="flex flex-col gap-2">
                                    <p class="pb-2"><strong>Inicio:</strong> {{ \Carbon\Carbon::parse($shift->start)->format('H:i') }} - {{ \Carbon\Carbon::parse($shift->start)->format('d/m/Y') }}</p>
                                    <p class="pb-2"><strong>Fin:</strong> {{ \Carbon\Carbon::parse($shift->end)->format('H:i') }} - {{ \Carbon\Carbon::parse($shift->end)->format('d/m/Y') }}</p>
                                    <p class="pb-2"><strong>Notas:</strong> {{ $shift->notes ?? 'Sin notas' }}</p>
                                </div>

                            </div>
                        @endforeach
                    </div>

                @endif


        </div>
    </div>
</x-layout>
