
<x-layout :title="'Calendario'">
    <x-page-heading>Calendario de {{$user->name}} en {{$schedule->section->name}}</x-page-heading>

    <div class="p-4 m-10 bg-white shadow rounded-xl w-7/10">
        <div class="flex justify-end">
            <div class="flex space-x-0">
                <a href="/horario/{{ $schedule->id }}" class="bg-gray-200 text-black text-s font-semibold py-2 px-4 rounded-l focus:outline-none">Horario de equipo</a>
                <a href="/horario/personal/{{ $schedule->id }}" class="bg-sky-900 text-white text-s font-semibold py-2 px-4  focus:outline-none">Horario personal</a>
                <a href="/stats" class="bg-gray-200 text-black text-s font-semibold py-2 px-4 rounded-r focus:outline-none">Estadísticas</a>

            </div>
        </div>

        <div class="flex justify-between">
            <!-- Contenedor del calendario -->
            <div class="flex-3 grid grid-cols-1 sm:grid-cols-1 md:grid-cols-3 lg:grid-cols-7 gap-4 text-center mt-8 w-2/3 mx-auto ml-0">
                <!-- Cabecera con nombres de los días -->
                <div class="font-bold hidden lg:block">Lunes</div>
                <div class="font-bold hidden lg:block">Martes</div>
                <div class="font-bold hidden lg:block">Miércoles</div>
                <div class="font-bold hidden lg:block">Jueves</div>
                <div class="font-bold hidden lg:block">Viernes</div>
                <div class="font-bold hidden lg:block">Sábado</div>
                <div class="font-bold hidden lg:block">Domingo</div>

                <!-- Días del calendario -->
                @foreach ($days as $day)
                    @php
                        $date = $day['date'];
                        $dayOfWeek = $day['day_of_week'];
                        $isPassed = $day['is_passed'];
                        $isWorkingDay = $day['is_working_day'];
                        $isCurrentMonth = $day['is_current_month'];

                        $bgColor = !$isCurrentMonth
                            ?  'bg-gray-200'
                            : ($isPassed
                                ? 'bg-sky-200'
                                : ($isWorkingDay ? 'bg-sky-600' : 'bg-sky-400'));

                        $textColor = ($isWorkingDay) ? 'text-white' : 'text-gray-800';

                    @endphp
                    <div class="relative border {{ $bgColor }} {{ $textColor }} rounded-lg p-5 h-24 shadow text-left">
                        <span class="absolute top-0 left-0 m-2">{{ $date->format('d') }}</span>

                        @if ($isWorkingDay)
                            <div class="absolute inset-0 flex items-center justify-center">

                                @foreach($day['shifts'] as $shift)
                                    <div class="bg-white text-sky-400 font-bold py-1 px-2 rounded mr-2">
                                        {{ $shift['start'] }} - {{ $shift['end'] }}
                                    </div>
                                @endforeach

                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
            <div class="flex-1 mt-8 ml-10 p-4 shadow rounded-lg">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-4">Leyenda</h3>
                    <div class="flex items-center mb-2">
                        <div class="w-6 h-6 bg-sky-600 rounded mr-2"></div>
                        <span>Día laboral</span>
                    </div>
                    <div class="flex items-center mb-2">
                        <div class="w-6 h-6 bg-sky-400 rounded mr-2"></div>
                        <span>Día libre</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-6 h-6 bg-sky-200 rounded mr-2"></div>
                        <span>Día ya pasado</span>
                    </div>
                </div>

                @if ($nextShift)
                    <div class="mx-auto mt-20 flex flex-col justify-center items-center">
                        <h3 class="text-xl font-bold mb-3">Turno del {{ \Carbon\Carbon::parse($nextShift->start)->locale('es')->format('d \d\e F') }}</h3> <!-- Muestra el día de comienzo en español -->
                            <p class="pb-2"><strong>Inicio:</strong> {{ \Carbon\Carbon::parse($nextShift->start)->format('H:i') }}  - {{ \Carbon\Carbon::parse($nextShift->start)->format('d/m/Y') }}</p>
                            <p class="pb-2"><strong>Fin:</strong> {{ \Carbon\Carbon::parse($nextShift->end)->format('H:i') }} - {{ \Carbon\Carbon::parse($nextShift->end)->format('d/m/Y') }} </p>

                            <p class="pb-2"><strong>Notas:</strong> {{ $nextShift->notes ?? 'Sin notas' }}</p>

                            <p class="pb-2"><strong>Trabajadores ({{ $nextShift->users_needed }} usuarios necesarios):</strong></p>
                            <ul>
                                @foreach ($nextShift->users as $user)
                                    <li class="ps-4 pb-2">- {{ $user->name }}</li>
                                @endforeach
                            </ul>

                    </div>
                @endif
            </div>
        </div>


    </div>


</x-layout>
