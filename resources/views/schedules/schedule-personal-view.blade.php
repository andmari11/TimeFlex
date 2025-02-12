
<x-layout :title="'Calendario'">
    <x-page-heading>Calendario de {{$user->name}}</x-page-heading>

    <div class="p-4 m-10 bg-white shadow rounded-xl w-7/10">
        <div class="flex justify-end">
            <div class="flex space-x-0">
                <a href="/horario/{{ $user->section->id }}" class="bg-gray-200 text-black text-s font-semibold py-2 px-4 rounded-l focus:outline-none">Horario de equipo</a>
                <a href="/horario/personal/{{ $user->id }}" class="bg-sky-900 text-white text-s font-semibold py-2 px-4  focus:outline-none">Horario personal</a>
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
                <div class="relative border bg-sky-800 text-white rounded-lg p-5 h-24 shadow text-left">
                    <span class="absolute top-0 left-0 m-2">1</span>
                </div>
                <div class="relative border bg-sky-800 text-white rounded-lg p-5 h-24 shadow text-left">
                    <span class="absolute top-0 left-0 m-2">2</span>
                </div>
                <div class="relative border bg-sky-800 text-white rounded-lg p-5 h-24 shadow text-left">
                    <span class="absolute top-0 left-0 m-2">3</span>
                </div>
                <div class="relative border bg-sky-800 text-white rounded-lg p-5 h-24 shadow text-left">
                    <span class="absolute top-0 left-0 m-2">4</span> <!-- Día pasado -->
                </div>
                <div class="relative border bg-sky-800 text-white rounded-lg p-5 h-24 shadow text-left">
                    <span class="absolute top-0 left-0 m-2">5</span> <!-- Día libre -->
                </div>
                <div class="relative border bg-sky-800 text-white rounded-lg p-5 h-24 shadow text-left">
                    <span class="absolute top-0 left-0 m-2">6</span> <!-- Día de trabajo -->
                </div>
                <div class="relative border bg-sky-800 text-white rounded-lg p-5 h-24 shadow text-left">
                    <span class="absolute top-0 left-0 m-2">7</span>
                </div>
                <div class="relative border bg-sky-400 rounded-lg p-5 h-24 shadow text-left">
                    <span class="absolute top-0 left-0 m-2">8</span>
                    <div class="absolute inset-0 flex items-center justify-center"> <div class="bg-white text-sky-400 font-bold py-1 px-2 rounded">08:00 - 16:00</div> </div>
                </div>
                <div class="relative border bg-sky-400 rounded-lg p-5 h-24 shadow text-left">
                    <span class="absolute top-0 left-0 m-2">9</span> <!-- Día pasado -->
                    <div class="absolute inset-0 flex items-center justify-center"> <div class="bg-white text-sky-400 font-bold py-1 px-2 rounded">08:00 - 16:00</div> </div>
                </div>
                <div class="relative border bg-sky-400 rounded-lg p-5 h-24 shadow text-left">
                    <span class="absolute top-0 left-0 m-2">10</span> <!-- Día libre -->
                    <div class="absolute inset-0 flex items-center justify-center"> <div class="bg-white text-sky-400 font-bold py-1 px-2 rounded">08:00 - 16:00</div> </div>
                </div>
                <div class="relative border bg-sky-400 rounded-lg p-5 h-24 shadow text-left">
                    <span class="absolute top-0 left-0 m-2">11</span> <!-- Día de trabajo -->
                    <div class="absolute inset-0 flex items-center justify-center"> <div class="bg-white text-sky-400 font-bold py-1 px-2 rounded">08:00 - 16:00</div> </div>
                </div>
                <div class="relative border bg-gray-200 rounded-lg p-5 h-24 shadow text-left">
                    <span class="absolute top-0 left-0 m-2">12</span>
                </div>
                <div class="relative border bg-gray-200 rounded-lg p-5 h-24 shadow text-left">
                    <span class="absolute top-0 left-0 m-2">13</span>
                </div>
                <div class="relative border bg-gray-200 rounded-lg p-5 h-24 shadow text-left">
                    <span class="absolute top-0 left-0 m-2">14</span> <!-- Día pasado -->
                </div>
                <div class="relative border bg-sky-400 rounded-lg p-5 h-24 shadow text-left">
                    <span class="absolute top-0 left-0 m-2">15</span> <!-- Día libre -->
                    <div class="absolute inset-0 flex items-center justify-center"> <div class="bg-white text-sky-400 font-bold py-1 px-2 rounded">08:00 - 16:00</div> </div>
                </div>
                <div class="relative border bg-sky-400 rounded-lg p-5 h-24 shadow text-left">
                    <span class="absolute top-0 left-0 m-2">16</span> <!-- Día de trabajo -->
                    <div class="absolute inset-0 flex items-center justify-center"> <div class="bg-white text-sky-400 font-bold py-1 px-2 rounded">08:00 - 16:00</div> </div>
                </div>
                <div class="relative border bg-sky-400 rounded-lg p-5 h-24 shadow text-left">
                    <span class="absolute top-0 left-0 m-2">17</span>
                    <div class="absolute inset-0 flex items-center justify-center"> <div class="bg-white text-sky-400 font-bold py-1 px-2 rounded">08:00 - 16:00</div> </div>
                </div>
                <div class="relative border bg-sky-400 rounded-lg p-5 h-24 shadow text-left">
                    <span class="absolute top-0 left-0 m-2">18</span>
                    <div class="absolute inset-0 flex items-center justify-center"> <div class="bg-white text-sky-400 font-bold py-1 px-2 rounded">08:00 - 16:00</div> </div>
                </div>
                <div class="relative border bg-sky-400 rounded-lg p-5 h-24 shadow text-left">
                    <span class="absolute top-0 left-0 m-2">19</span> <!-- Día pasado -->
                    <div class="absolute inset-0 flex items-center justify-center"> <div class="bg-white text-sky-400 font-bold py-1 px-2 rounded">08:00 - 16:00</div> </div>
                </div>
                <div class="relative border bg-gray-200 rounded-lg p-5 h-24 shadow text-left">
                    <span class="absolute top-0 left-0 m-2">20</span> <!-- Día libre -->
                </div>
                <div class="relative border bg-gray-200 rounded-lg p-5 h-24 shadow text-left">
                    <span class="absolute top-0 left-0 m-2">21</span> <!-- Día de trabajo -->

                </div>
                <div class="relative border bg-sky-400 rounded-lg p-5 h-24 shadow text-left">
                    <span class="absolute top-0 left-0 m-2">22</span>
                    <div class="absolute inset-0 flex items-center justify-center"> <div class="bg-white text-sky-400 font-bold py-1 px-2 rounded">08:00 - 16:00</div> </div>
                </div>
                <div class="relative border bg-sky-400 rounded-lg p-5 h-24 shadow text-left">
                    <span class="absolute top-0 left-0 m-2">23</span>
                    <div class="absolute inset-0 flex items-center justify-center"> <div class="bg-white text-sky-400 font-bold py-1 px-2 rounded">08:00 - 16:00</div> </div>
                </div>
                <div class="relative border bg-sky-400 rounded-lg p-5 h-24 shadow text-left">
                    <span class="absolute top-0 left-0 m-2">24</span> <!-- Día pasado -->
                    <div class="absolute inset-0 flex items-center justify-center"> <div class="bg-white text-sky-400 font-bold py-1 px-2 rounded">08:00 - 16:00</div> </div>
                </div>
                <div class="relative border bg-sky-400 rounded-lg p-5 h-24 shadow text-left">
                    <span class="absolute top-0 left-0 m-2">25</span> <!-- Día libre -->
                    <div class="absolute inset-0 flex items-center justify-center"> <div class="bg-white text-sky-400 font-bold py-1 px-2 rounded">08:00 - 16:00</div> </div>
                </div>
                <div class="relative border bg-sky-400 rounded-lg p-5 h-24 shadow text-left">
                    <span class="absolute top-0 left-0 m-2">26</span> <!-- Día de trabajo -->
                    <div class="absolute inset-0 flex items-center justify-center"> <div class="bg-white text-sky-400 font-bold py-1 px-2 rounded">08:00 - 16:00</div> </div>
                </div>
                <div class="relative border bg-gray-200 rounded-lg p-5 h-24 shadow text-left">
                    <span class="absolute top-0 left-0 m-2">27</span>
                </div>
                <div class="relative border bg-gray-200 rounded-lg p-5 h-24 shadow text-left">
                    <span class="absolute top-0 left-0 m-2">28</span>
                </div>
                <div class="relative border bg-sky-400 rounded-lg p-5 h-24 shadow text-left">
                    <span class="absolute top-0 left-0 m-2">29</span> <!-- Día pasado -->
                    <div class="absolute inset-0 flex items-center justify-center"> <div class="bg-white text-sky-400 font-bold py-1 px-2 rounded">08:00 - 16:00</div> </div>
                </div>
                <div class="relative border bg-sky-400 rounded-lg p-5 h-24 shadow text-left">
                    <span class="absolute top-0 left-0 m-2">30</span> <!-- Día libre -->
                    <div class="absolute inset-0 flex items-center justify-center"> <div class="bg-white text-sky-400 font-bold py-1 px-2 rounded">08:00 - 16:00</div> </div>
                </div>
                <div class="relative border bg-sky-400 rounded-lg p-5 h-24 shadow text-left">
                    <span class="absolute top-0 left-0 m-2">31</span> <!-- Día de trabajo -->
                    <div class="absolute inset-0 flex items-center justify-center"> <div class="bg-white text-sky-400 font-bold py-1 px-2 rounded">08:00 - 16:00</div> </div>
                </div>
            </div>

            <div class="flex-1 mt-8 ml-10 p-4 shadow rounded-lg">
                <div>
                    <h3 class="text-lg font-semibold mb-4">Leyenda</h3>
                    <div class="flex items-center mb-2">
                        <div class="w-6 h-6 bg-sky-400 rounded mr-2"></div>
                        <span>Día laboral</span>
                    </div>
                    <div class="flex items-center mb-2">
                        <div class="w-6 h-6 bg-sky-800 rounded mr-2"></div>
                        <span>Día ya pasado</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-6 h-6 bg-gray-200 rounded mr-2"></div>
                        <span>Día libre</span>
                    </div>
                </div>
                <div class="mt-6 text-center">
                    <label for="day-select" class="block text-lg font-semibold mb-2">Selecciona un día:</label>
                    <select id="day-select" class="block w-2/3 mx-auto p-3 border rounded shadow text-lg">
                        <option value="1">01/01/2024</option>
                        <option value="2">01/01/2024</option>
                        <option value="3">01/01/2024</option>
                        <option value="4">01/01/2024</option>
                        <option value="5">01/01/2024</option>
                        <option value="6">01/01/2024</option>
                        <option value="7">01/01/2024</option>
                    </select>
                    <div class="mt-6">
                        <h4 class="text-lg font-semibold">Horario para el día seleccionado:</h4>
                        <p class="text-md mt-2 text-gray-600">08:00 - 16:00</p>
                    </div>
                </div>

            </div>
        </div>


    </div>


</x-layout>
