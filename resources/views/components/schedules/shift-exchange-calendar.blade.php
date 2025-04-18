@props(['id_shift_mine', 'id_shift_someone', 'schedule', 'days', 'showButtons'])

<div class="p-4 m-10 bg-white shadow rounded-xl w-full">
    @if(!isset($showButtons) or $showButtons)
        <div class="flex justify-end">
            <!--<h2 class="text-2xl font-bold mb-4">Calendario de equipo de :</h2>-->
            <div class="flex space-x-0">
                <a href="/horario/{{ $schedule->id }}" class="bg-sky-900 text-white text-s font-semibold py-2 px-4 rounded-l focus:outline-none">Horario de equipo</a>
                <a href="/horario/personal/{{ $schedule->id  }}" class="bg-gray-200 text-black text-s font-semibold py-2 px-4  focus:outline-none">Horario personal</a>
                <a href="/estadisticashorario/{{ $schedule->id }}" class="bg-gray-200 text-black text-s font-semibold py-2 px-4 rounded-r focus:outline-none">Estadísticas</a>
            </div>
        </div>
    @endif

    <!-- Contenedor del calendario -->
    <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-3 lg:grid-cols-7 gap-2 text-center mt-8 w-full">
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
            @endphp

            <div class="border {{ $isCurrentMonth ? 'bg-gray-100' : 'bg-gray-200 hidden lg:block' }} rounded-lg p-2 shadow">
                <h3 class="font-bold text-lg {{ $isCurrentMonth ? '' : 'text-gray-400' }}">
                    {{ $date->format('d') }}
                </h3>
                    <ul class="mt-2">
                        @foreach ($schedule->shifts as $shift)
                            @php
                                $shiftStart = Carbon\Carbon::parse($shift['start']);
                            @endphp
                            @if ($shiftStart->isSameDay($date))
                                @if($shift['id'] == $id_shift_someone)
                                    <li class=" text-white rounded p-1 mb-1 bg-sky-400" >
                                        <a  class="block w-full h-full hover:pointer">
                                            <strong>{{ $shiftStart->format('H:i') }} - {{ Carbon\Carbon::parse($shift['end'])->format('H:i') }}</strong>
                                        </a>
                                    </li>
                                @elseif($shift['id'] == $id_shift_mine)
                                    <li class=" text-white rounded p-1 mb-1 bg-sky-900" >
                                        <a  class="block w-full h-full hover:pointer">
                                            <strong>{{ $shiftStart->format('H:i') }} - {{ Carbon\Carbon::parse($shift['end'])->format('H:i') }}</strong>
                                        </a>
                                    </li>
                                <!--
                                    <li class=" text-white rounded p-1 mb-1 bg-sky-900" >
                                        <a  class="block w-full h-full hover:pointer">
                                            <strong>{{ $shiftStart->format('H:i') }} - {{ Carbon\Carbon::parse($shift['end'])->format('H:i') }}</strong>
                                        </a>
                                    </li>-->
                               @endif
                            @endif
                        @endforeach
                    </ul>
            </div>
        @endforeach
    </div>
</div>
