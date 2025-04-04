<x-layout :title="'Calendario'">
    <x-page-heading> Horario de {{ $schedule->section->name }}</x-page-heading>
    <div class="flex justify-between">

    <x-schedules.single-schedule-calendar class="basis-[40%] flex-grow " :schedule="$schedule"  :currentPage="$currentPage??null" :nextShift="$shiftToView" :months="$months" :showButtons="false"></x-schedules.single-schedule-calendar>
    <div class="flex-grow p-4 max-w-xl mr-10 w-30 shadow rounded-lg bg-white w-full">
        <div class="flex justify-end">
            <!--<h2 class="text-2xl font-bold mb-4">Calendario de equipo de :</h2>-->
            <div class="flex space-x-0 mb-8">
                <a href="/horario/{{ $schedule->id }}" class="bg-sky-900 text-white text-s font-semibold py-2 px-4 rounded-l focus:outline-none">Horario de equipo</a>
                <a href="/horario/personal/{{ $schedule->id  }}" class="bg-gray-200 text-black text-s font-semibold py-2 px-4  focus:outline-none">Horario personal</a>
                <a href="/estadisticashorario" class="bg-gray-200 text-black text-s font-semibold py-2 px-4 rounded-r focus:outline-none">Estadísticas</a>

            </div>
        </div>
        @if ($shiftToView)
            <div class="mx-auto mt-y0 flex flex-col justify-center items-center" x-data="{ open_options_menu: false }" >

            <div class="flex items-center justify-center relative w-full mb-3 px-8">
                <h3 class="text-xl font-bold pe-4">Turno del {{ \Carbon\Carbon::parse($shiftToView->start)->locale('es')->format('d \d\e F') }}</h3>
                <div class="absolute top-0 right-2 py-0">
                    <button
                        @click="open_options_menu = !open_options_menu"
                        type="button"
                        class="px-3 py-0 text-black rounded-full text-2xl  font-bold">
                        &#x22EE;
                    </button>
                    <!-- Menú desplegable -->

                </div>
                <div  x-show="open_options_menu" @click.away="open_options_menu = false"
                      class="absolute right-0 z-10 w-48 bg-white shadow-lg rounded-md ring-1 ring-black ring-opacity-5">

                    @if(!$shiftToView->hasUser(Auth::user()->id))

                        <a href="/shift-exchange/{{$schedule->id}}/turno/{{$shiftToView->id}}"
                           class="block px-4 py-2 text-gray-800 hover:bg-gray-100 text-sm transition-all">
                            Cambio de turno
                        </a>
                    @else
                        <a href="/shift-exchange/{{$schedule->id}}/turno/0/{{$shiftToView->id}}"
                           class="block px-4 py-2 text-gray-800 hover:bg-gray-100 text-sm transition-all">
                            Cambio de turno
                        </a>
                    @endif
                </div>


            </div>



                <p class="pb-2"><strong>Inicio:</strong> {{ \Carbon\Carbon::parse($shiftToView->start)->format('H:i') }}  - {{ \Carbon\Carbon::parse($shiftToView->start)->format('d/m/Y') }}</p>
                <p class="pb-2"><strong>Fin:</strong> {{ \Carbon\Carbon::parse($shiftToView->end)->format('H:i') }} - {{ \Carbon\Carbon::parse($shiftToView->end)->format('d/m/Y') }} </p>

                <p class="pb-2 text-center"><strong>Notas:</strong> {{ $shiftToView->notes ?? 'Sin notas' }}</p>

                <p class="pb-2"><strong>Trabajadores ({{ $shiftToView->users_needed }} usuarios necesarios):</strong></p>
                <div class="flex flex-col gap-2 mt-4 w-full">
                    @foreach ($shiftToView->users as $user)
                        <div class="w-full p-6 mb-4 shadow bg-sky-50 rounded-2xl">
                            <x-users.employee-item  :employee="$user"></x-users.employee-item>
                        </div>
                    @endforeach
                </div>

            </div>
        @endif
    </div>
    </div>
</x-layout>
