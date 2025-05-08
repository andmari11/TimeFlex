<div class="w-full" x-data="{
        currentPage: {{ $currentPage ?? 1}},
        totalPages: {{ $calendars->count() }},
    }">
    @foreach($calendars as $index=>$month)

        <div x-show="currentPage == {{ $index + 1 }}" >
            @php
                $monthName = $month["month"];
                $days = $month['days'];
            @endphp
        <x-layout :title="'Calendario'">
            <x-page-heading>Calendario personal en {{$schedule->section->name}}</x-page-heading>

            <div class="p-4 mx-10  bg-white shadow rounded-xl w-7/10">
                <div class="flex justify-between">
                    @php
                        $monthName = $month["month"];
                        $days = $month['days'];
                    @endphp

                    <div class="ps-2 d-flex justify-between items-center">
                        <div class="flex items-center gap-2 text-2xl font-bold">
                            <div class="pe-2">
                                Calendario de {{$monthName}}
                            </div>

                        </div>
                    </div>
                    <div class="ps-2 d-flex flex items-center space-x-4">
                        @if($calendars->count() > 1)
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
                                    <a href="/horario/{{ $schedule->id }}" class="bg-gray-200 text-black text-s font-semibold py-2 px-4 rounded-l focus:outline-none">Horario de equipo</a>
                                    <a href="/horario/personal/{{ $schedule->id }}" class="bg-sky-900 text-white text-s font-semibold py-2 px-4  focus:outline-none">Horario personal</a>
                                    <a href="/estadisticashorario/{{ $schedule->section->id }}" class="bg-gray-200 text-black text-s font-semibold py-2 px-4 rounded-r focus:outline-none">Estadísticas</a>
                                </div>
                            @endif
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
                                $isWorkingDay = $day['shifts']->count() > 0;
                                $isCurrentMonth = $day['is_current_month'];
                                $shiftToView = isset($nextShift) && $date->isSameDay($nextShift->start);
                                $bgColor = !$isCurrentMonth
                                    ? 'bg-gray-200'
                                    : ($isPassed
                                        ? 'bg-sky-200'
                                        : ($isWorkingDay ? 'bg-sky-600' : 'bg-sky-400'));

                                $textColor = $isWorkingDay ? 'text-white' : 'text-gray-800';

                                if ($shiftToView) {
                                    $bgColor = 'bg-sky-800';
                                    $textColor = 'text-white';
                                }
                            @endphp

                            <div class="relative border {{ $bgColor }} {{ $textColor }} rounded-lg p-5 min-h-24 h-auto shadow text-left">
                                <span class="absolute top-0 left-0 m-2">{{ $date->format('d') }}</span>

                                @if ($isWorkingDay)
                                    <div class="inset-0 flex flex-col mt-5 items-center justify-start space-y-1 overflow-auto">
                                        @foreach($day['shifts'] as $shift)
                                            <div class="bg-white text-sky-400 font-bold py-1 px-2 rounded ">
                                                <a href="{{ url('/horario/personal/' . $schedule->id . '/turno/' . $shift['id']) }}" class="block w-full h-full hover:pointer">
                                                {{ \Carbon\Carbon::parse($shift['start'])->format('H:i') }} - {{ \Carbon\Carbon::parse($shift['end'])->format('H:i') }}
                                                </a>
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
                                <h3 class="text-xl font-bold mb-3">Turno del {{ \Carbon\Carbon::parse($nextShift->start)->locale('es')->translatedFormat('d \d\e F') }}</h3> <!-- Muestra el día de comienzo en español -->
                                    <p class="pb-2"><strong>Inicio:</strong> {{ \Carbon\Carbon::parse($nextShift->start)->format('H:i') }}  - {{ \Carbon\Carbon::parse($nextShift->start)->format('d/m/Y') }}</p>
                                    <p class="pb-2"><strong>Fin:</strong> {{ \Carbon\Carbon::parse($nextShift->end)->format('H:i') }} - {{ \Carbon\Carbon::parse($nextShift->end)->format('d/m/Y') }} </p>

                                    <p class="pb-2 text-center"><strong>Notas:</strong> {{ $nextShift->notes ?? 'Sin notas' }}</p>

                                    <p class="pb-2"><strong>Trabajadores ({{ $nextShift->users_needed }} usuarios necesarios):</strong></p>
                                <div class="flex flex-col gap-2 mt-4 w-full ">
                                    @foreach ($nextShift->users as $user)
                                        @if($user->id !== auth()->user()->id)
                                            <div class="w-full p-6 mb-6 bg-sky-50 rounded-2xl">
                                                <x-users.employee-item  :employee="$user"></x-users.employee-item>

                                            </div>
                                        @endif

                                    @endforeach
                                </div>


                            </div>
                        @endif
                    </div>
                </div>


            </div>


        </x-layout>
        </div>
    @endforeach
</div>
