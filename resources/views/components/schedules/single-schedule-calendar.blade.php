
<div class="px-10  w-full" x-data="{
        currentPage: {{ $currentPage ?? 1}},
        totalPages: {{ $months->count() }},
    }">
    @foreach($months as $index=>$month)

        <div x-show="currentPage == {{ $index + 1 }}">
    <div class="p-4  bg-white shadow rounded-xl ">
            <div class="flex justify-between">
                @php
                    $monthName = $month["month"];
                    $days = $month['days'];
                @endphp

                <div class="ps-2 d-flex justify-between items-center">
                    <div class="flex justify-between gap-2 text-2xl font-bold">
                        <div class="pe-4">
                            Calendario de {{$monthName}}
                        </div>

                    </div>
                </div>
                <div class="ps-2 d-flex flex items-center space-x-4">
                    @if($months->count() > 1)
                        <div class="flex">
                            <!-- Botón de mes anterior -->
                            <button
                                x-on:click="if (currentPage != 1) currentPage = currentPage - 1"
                                :disabled="currentPage == 1"
                                class="px-3 py-1 bg-sky-900 text-white cursor-pointer disabled:bg-gray-400 transition-all duration-200 ease-in-out rounded-l-md flex items-center justify-center w-10 h-10 border-r-2 border-white"
                                :class="{ 'opacity-50': currentPage == 1 }">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-7 h-7">
                                    <path d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>

                            <!-- Botón de mes siguiente -->
                            <button
                                x-on:click="if (currentPage != totalPages) currentPage = currentPage + 1"
                                :disabled="currentPage == totalPages"
                                class="px-3 py-1 bg-sky-900 text-white cursor-pointer disabled:bg-gray-400 transition-all duration-200 ease-in-out rounded-r-md flex items-center justify-center w-10 h-10"
                                :class="{ 'opacity-50': currentPage == totalPages }">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-7 h-7">
                                    <path d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    @endif

                    @if(!isset($showButtons) or $showButtons)
                        <div class="flex space-x-0">
                            <a href="/horario/{{ $schedule->id }}" class="bg-sky-900 text-white text-s font-semibold py-2 px-4 rounded-l focus:outline-none">Horario de equipo</a>
                            <a href="/horario/personal/{{ $schedule->id  }}" class="bg-gray-200 text-black text-s font-semibold py-2 px-4  focus:outline-none">Horario personal</a>
                            <a href="/estadisticashorario" class="bg-gray-200 text-black text-s font-semibold py-2 px-4 rounded-r focus:outline-none">Estadísticas</a>
                        </div>
                    @endif
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
                    $isCurrentMonth = $day['is_current_month'] && (!$date->isPast() or $date->isToday());
                    $isToday = $date->isToday();
                    $bgColor = $isCurrentMonth ? match ($dayOfWeek) {
                        1 => 'bg-sky-400', // Lunes
                        2 => 'bg-sky-500', // Martes
                        3 => 'bg-sky-600', // Miércoles
                        4 => 'bg-sky-700', // Jueves
                        5 => 'bg-sky-800', // Viernes
                        6 => 'bg-sky-900', // Sábado
                        0 => 'bg-sky-950', // Domingo
                        default => 'bg-gray-100',
                    } : 'bg-gray-200'; // Color apagado para días fuera del mes


                @endphp

                <div class="border {{ $isCurrentMonth ? 'bg-gray-100' : 'bg-gray-200 hidden lg:block' }} rounded-lg p-2 shadow">
                    <h3 class="font-bold text-lg {{ $isCurrentMonth ? '' : 'text-gray-400' }} {{$isToday? 'underline': ''}}" >
                        {{ $date->format('d') }}
                    </h3>
                    @if ($isCurrentMonth)
                        <ul class="mt-2">
                            @foreach ($schedule->shifts as $shift)
                                @php
                                    $shiftToView = isset($nextShift) && $nextShift->id == $shift['id'] && $nextShift->start== $shift['start'];
                                    $shiftStart = Carbon\Carbon::parse($shift['start']);
                                @endphp
                                @if ($shiftStart->isSameDay($date))
                                    <li class="{{ $shiftToView?'border border-4 border-white':''}} {{$bgColor }} text-white rounded p-1 mb-1" >
                                        <a href="{{ url('/horario/' . $schedule->id . '/turno/' . $shift['id']) }}" class="block w-full h-full hover:pointer">

                                            <strong class="mb-3">{{ $shiftStart->format('H:i') }} - {{ Carbon\Carbon::parse($shift['end'])->format('H:i') }}</strong>

                                            <!-- Mostrar etiquetas para los trabajadores -->
                                            @if (isset($shift['users']) && count($shift['users']) > 0)
                                                <div class="mt-2 flex flex-wrap gap-1">
                                                    @foreach ($shift['users'] as $user)
                                                        <a href="{{url('/horario/' . $schedule->id . '/user/' . $user->id)}}" class="bg-sky-200 text-sky-800 text-xs font-semibold py-1 px-2 rounded">
                                                            {{ $user['name'] }}
                                                            @if(auth()->user()->id == $user->id)
                                                                {{"(Tú)"}}
                                                            @endif
                                                        </a>


                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="flex justify-center">
                                                    <span class="bg-sky-50 text-sky-800 text-xs font-semibold py-1 mt-4 mb-2 px-2 rounded">Ver trabajadores</span>
                                                </div>
                                            @endif


                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endforeach

        </div>


    </div>
        </div>

    @endforeach
</div>

