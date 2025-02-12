<x-layout :title="'Calendario'">
    <x-page-heading> Horario de {{ $schedule->section->name }}</x-page-heading>

    <div class="p-4 m-10 bg-white shadow rounded-xl">
        <div class="flex justify-end">
            <!--<h2 class="text-2xl font-bold mb-4">Calendario de equipo de :</h2>-->
            <div class="flex space-x-0">
                <a href="/horario/{{ $user->section->id }}" class="bg-sky-900 text-white text-s font-semibold py-2 px-4 rounded-l focus:outline-none">Horario de equipo</a>
                <a href="/horario/personal/{{ $user->id  }}" class="bg-gray-200 text-black text-s font-semibold py-2 px-4  focus:outline-none">Horario personal</a>
                <a href="/stats" class="bg-gray-200 text-black text-s font-semibold py-2 px-4 rounded-r focus:outline-none">Estadísticas</a>

            </div>

        </div>


        <!-- Contenedor del calendario -->
        <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-3 lg:grid-cols-7 gap-2 text-center mt-8">
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
                    $isCurrentMonth = $day['is_current_month'];
                    $bgColor = $isCurrentMonth ? match ($dayOfWeek) {
                        1 => 'bg-sky-400', // Lunes
                        2 => 'bg-sky-500', // Martes
                        3 => 'bg-sky-600', // Miércoles
                        4 => 'bg-sky-500', // Jueves
                        5 => 'bg-sky-500', // Viernes
                        6 => 'bg-sky-800', // Sábado
                        0 => 'bg-sky-900', // Domingo
                        default => 'bg-gray-100',
                    } : 'bg-gray-200'; // Color apagado para días fuera del mes
                @endphp

                <div class="border {{ $isCurrentMonth ? 'bg-gray-100' : 'bg-gray-200 hidden lg:block' }} rounded-lg p-2 shadow">
                    <h3 class="font-bold text-lg {{ $isCurrentMonth ? '' : 'text-gray-400' }}">
                        {{ $date->format('d') }}
                    </h3>
                    @if ($isCurrentMonth)
                        <ul class="mt-2">
                            @foreach ($schedule->shifts as $shift)
                                @php
                                    $shiftStart = Carbon\Carbon::parse($shift['start']);
                                @endphp
                                @if ($shiftStart->isSameDay($date))
                                    <li class="{{ $bgColor }} text-white rounded p-1 mb-1">
                                        <strong>{{ $shiftStart->format('H:i') }} - {{ Carbon\Carbon::parse($shift['end'])->format('H:i') }}</strong>
                                        <span>({{ $shift['users_needed'] }} trabajadores)</span>

                                        <!-- Mostrar etiquetas para los trabajadores -->
                                        @if (isset($shift['users']) && count($shift['users']) > 0)
                                            <div class="mt-2 flex flex-wrap gap-1">
                                                @foreach ($shift['users'] as $user)
                                                    <span class="bg-blue-200 text-blue-800 text-xs font-semibold py-1 px-2 rounded">
                                                        {{ $user['name'] }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="flex justify-end m-3">
                                                <span class="bg-blue-50 text-sky-800 text-xs font-semibold py-1 px-2 rounded">Ver trabajadores</span>
                                            </div>


                                        @endif
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</x-layout>
