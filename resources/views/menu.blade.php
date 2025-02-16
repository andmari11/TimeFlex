@php

    if (auth()->user()->role === 'employee') {
        // Si es un empleado, obtenemos su sección
        $section = auth()->user()->section;
    }
@endphp


<x-layout :title="'Mi área'">
    @if(auth()->user()->role === 'admin')
        <x-sidebar >
            <div class="flex flex-wrap">
                <div class="flex flex-col w-1/3 px-8">
                    <div class="ms-3 mt-5 text-2xl p-2 font-bold">
                        Bienvenido, Admin
                    </div>
                    <section class="relative w-full bg-white px-8 rounded-lg shadow-md mt-5 ml-4">
                        <div class="absolute top-4 right-4">
                            <svg class="w-9 h-9 text-blue-500 transition duration-75 dark:text-blue-900 group-hover:text-blue-900 dark:group-hover:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                            </svg>
                        </div>
                        <nav class="py-5 border-b border-blue/10">
                            <h3 class="font-bold text-xl hover:underline">Siguiente turno:</h3>
                            <div class="py-2">
                                <p class="text-xl ps-4">- Jueves 18:</p>
                                <p class="text-lg ps-6">10:00 -> 14:00</p>
                            </div>
                        </nav>
                    </section>

                    <section class="relative w-full bg-white mt-9 px-5 pb-8 rounded-lg shadow-md mt-2 ml-4">
                        <!-- Título con iconos -->
                        <div class="flex items-center justify-between py-4 border-b border-blue/10">
                            <div class="flex items-center space-x-2">
                                <!-- Icono de campana -->
                                <svg class="w-7 h-7 text-blue-900 pt-1" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2a7 7 0 00-7 7v3.29l-1.29 1.29a1 1 0 00-.21 1.09A1 1 0 005 15h14a1 1 0 00.91-1.41l-1.29-1.29V9a7 7 0 00-7-7zm0 18a3 3 0 01-2.82-2h5.64A3 3 0 0112 20z"/>
                                </svg>
                                <h2 class="text-xl font-bold">Notificaciones</h2>
                            </div>
                            <!-- Icono de ajustes -->
                            <svg class="w-8 h-8 text-blue-900 cursor-pointer" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 7a2 2 0 100-4 2 2 0 000 4zm0 5a2 2 0 100-4 2 2 0 000 4zm0 5a2 2 0 100-4 2 2 0 000 4z"/>
                            </svg>
                        </div>

                        <!-- Lista de notificaciones -->
                        <ul class="mt-4 space-y-2">

                            <li class="flex flex-col items-center px-4 py-4 bg-blue-50 rounded-lg">
                                <div class="flex justify-between w-full mb-4">
                                    <div>
                                        <h3><strong>Cambio de turno</strong></h3>
                                    </div>
                                    <div class="flex-grow"></div> <!-- Espaciador flexible para empujar los botones a la derecha -->
                                    <div class="flex space-x-2"> <!-- Añadir espacio entre los botones -->
                                        <a href="/" class="bg-blue-500 px-2 py-1 rounded-xl text-xs text-white">Aceptar</a>
                                        <a href="/" class="bg-red-500 px-2 py-1 rounded-xl text-xs text-white">Rechazar</a>
                                    </div>
                                </div>
                                <div class="w-full"> <!-- Contenedor de la tabla -->
                                    <table class="w-full border-collapse border-0">
                                        <thead>
                                        <tr>
                                            <th class="px-2 py-1"></th>
                                            <th class="px-2 py-1 text-center">Actual</th>
                                            <th class="px-2 py-1 text-center">Cambio</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="px-1 py-1 bg-blue-50 text-sky-900 text-s text-center font-semibold rounded">Juan Pérez</td>
                                                <td class="px-1 py-1 bg-blue-50 text-sky-900 text-sm text-center font-semibold rounded">13/2/2024 <br> (8:00-12:00)</td>
                                                <td class="px-1 py-1 bg-blue-50 text-sky-900 text-sm text-center font-semibold rounded">14/2/2024 <br> (12:00-16:00)</td>
                                            </tr>
                                            <tr>
                                                <td class="px-1 py-1 bg-blue-50 text-sky-900 text-s text-center font-semibold rounded">María López</td>
                                                <td class="px-1 py-1 bg-blue-50 text-sky-900 text-sm text-center font-semibold rounded">14/2/2024 <br> (12:00-16:00)</td>
                                                <td class="px-1 py-1 bg-blue-50 text-sky-900 text-sm text-center font-semibold rounded">13/2/2024 <br> (8:00-12:00)</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </li>
                            @if(auth()->user()->notifications->isEmpty())
                                <li class="px-4 py-4 text-center text-gray-500">
                                    No hay nuevas notificaciones.
                                </li>
                            @else
                                @foreach(auth()->user()->notifications as $notification)
                                    @php
                                        $isUnread = !$notification->read;
                                        $bgColor = $isUnread ? 'bg-blue-100' : 'bg-blue-50';
                                    @endphp

                                    <li class="flex items-center px-4 py-4 rounded-lg {{ $bgColor }}">
                                        @if($notification->url)
                                            <a href="{{ $notification->url }}" class="text-md text-blue-700 font-semibold hover:underline">
                                                {{ $notification->message }}
                                            </a>
                                        @else
                                            <span class="text-md text-gray-700">{{ $notification->message }}</span>
                                        @endif
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </section>
                    <x-sections.all-sections></x-sections.all-sections>

                </div>
                <div class="flex flex-col w-2/3 mt-20 pe-12">
                    <section class="relative w-full bg-white px-8 pb-8 rounded-lg shadow-md mt-2 ml-4">
                        <div class="absolute top-4 right-4 pe-4">
                            <!-- Icono con distintas personas -->
                            <svg class="w-9 h-9 text-blue-500 transition duration-75 dark:text-blue-900 group-hover:text-blue-900 dark:group-hover:text-white" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9 11a4 4 0 100-8 4 4 0 000 8zm6 0a4 4 0 100-8 4 4 0 000 8zm-6 2c-4 0-7 2-7 5v1h14v-1c0-3-3-5-7-5zm6 0h2c3 0 6 2 6 5v1h-4v-1c0-2-1-3-3-4z"/>
                            </svg>
                        </div>
                        <nav class="py-5 border-b border-blue/10">
                            <div class="flex items-center space-x-2">
                                <!-- Icono de calendario -->
                                <svg class="w-6 h-6 text-blue-900" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M3 3h18a1 1 0 011 1v16a1 1 0 01-1 1H3a1 1 0 01-1-1V4a1 1 0 011-1zm0 2v4h18V5H3zm0 6v8h18v-8H3zM8 7h2v2H8V7zm6 0h2v2h-2V7z"/>
                                </svg>
                                <h3 class="font-bold text-xl hover:underline">Horario semanal:</h3>
                            </div>
                        </nav>
                        <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-2 text-center pt-4">
                            <div class="font-bold hidden lg:block text-black">Lunes</div>
                            <div class="font-bold hidden lg:block text-black">Martes</div>
                            <div class="font-bold hidden lg:block text-black">Miércoles</div>
                            <div class="font-bold hidden lg:block text-black">Jueves</div>
                            <div class="font-bold hidden lg:block text-black">Viernes</div>

                            <div class="border bg-sky-400 text-white rounded-lg p-2 shadow">
                                <h3 class="font-bold text-lg">01</h3>
                                <ul class="mt-2">
                                    <li class="text-white rounded p-1 mb-1">
                                        <strong>08:00 - 12:00</strong>
                                        <div class="mt-2 flex flex-wrap gap-1">
                                            <span class="bg-blue-50 text-sky-800 text-xs font-semibold py-1 px-2 rounded">Juan Pérez</span>
                                            <span class="bg-blue-50 text-sky-800 text-xs font-semibold py-1 px-2 rounded">María López</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <div class="border bg-sky-500 text-white rounded-lg p-2 shadow">
                                <h3 class="font-bold text-lg">02</h3>
                                <ul class="mt-2">
                                    <li class="text-white rounded p-1 mb-1">
                                        <strong>10:00 - 14:00</strong>
                                        <div class="mt-2 flex flex-wrap gap-1">
                                            <span class="bg-blue-50 text-sky-800 text-xs font-semibold py-1 px-2 rounded">Pedro García</span>
                                            <span class="bg-blue-50 text-sky-800 text-xs font-semibold py-1 px-2 rounded">Carla Sánchez</span>
                                            <span class="bg-blue-50 text-sky-800 text-xs font-semibold py-1 px-2 rounded">Luis Fernández</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <div class="border bg-sky-600 text-white rounded-lg p-2 shadow">
                                <h3 class="font-bold text-lg">03</h3>
                                <ul class="mt-2">
                                    <li class="text-white rounded p-1 mb-1">
                                        <strong>12:00 - 16:00</strong>
                                        <div class="mt-2 flex flex-wrap gap-1">
                                            <span class="bg-blue-50 text-sky-800 text-xs font-semibold py-1 px-2 rounded">Ana Martínez</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <div class="border bg-sky-700 text-white rounded-lg p-2 shadow">
                                <h3 class="font-bold text-lg">04</h3>
                                <ul class="mt-2">
                                    <li class="text-white rounded p-1 mb-1">
                                        <strong>14:00 - 18:00</strong>
                                        <div class="mt-2 flex flex-wrap gap-1">
                                            <span class="bg-blue-50 text-sky-800 text-xs font-semibold py-1 px-2 rounded">Diego Ramírez</span>
                                            <span class="bg-blue-50 text-sky-800 text-xs font-semibold py-1 px-2 rounded">Laura González</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <div class="border bg-sky-800 text-white rounded-lg p-2 shadow">
                                <h3 class="font-bold text-lg">05</h3>
                                <ul class="mt-2">
                                    <li class="text-white rounded p-1 mb-1">
                                        <strong>16:00 - 20:00</strong>
                                        <div class="mt-2 flex flex-wrap gap-1">
                                            <span class="bg-blue-50 text-sky-800 text-xs font-semibold py-1 px-2 rounded">Javier Castro</span>
                                            <span class="bg-blue-50 text-sky-800 text-xs font-semibold py-1 px-2 rounded">Elena Ruiz</span>
                                            <span class="bg-blue-50 text-sky-800 text-xs font-semibold py-1 px-2 rounded">Mario Ortega</span>
                                            <span class="bg-blue-50 text-sky-800 text-xs font-semibold py-1 px-2 rounded">Sofía Morales</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                    </section>
                    <section class="relative w-full bg-white p-8 rounded-lg shadow-md mt-8 ml-4">
                        <div class="flex justify-around">
                            <button class="w-40 py-4 bg-sky-900 text-white text-lg font-semibold rounded-lg shadow-md hover:bg-blue-600 transition duration-200">
                                Solicitudes
                            </button>
                            <button class="w-40 py-4 bg-sky-900 text-white text-lg font-semibold rounded-lg shadow-md hover:bg-blue-600 transition duration-200">
                                Formularios
                            </button>
                            <button class="w-40 py-4 bg-sky-900 text-white text-lg font-semibold rounded-lg shadow-md hover:bg-blue-600 transition duration-200">
                                Estadísticas
                            </button>
                            <button class="w-40 py-4 bg-sky-900 text-white text-lg font-semibold rounded-lg shadow-md hover:bg-blue-600 transition duration-200">
                                Satisfacción
                            </button>
                        </div>
                    </section>

                    <x-users.all-employees :section="$section"></x-users.all-employees>

                </div>
            </div>
        </x-sidebar>

    @else

        <div class="flex flex-wrap">
            <div class="flex flex-col w-1/3 px-8">
                <div class="ms-3 mt-5 text-2xl p-2 font-bold">
                    Bienvenido, User
                </div>
                <section class="relative w-full bg-white px-8 rounded-lg shadow-md mt-5 ml-4">
                    <div class="absolute top-4 right-4">
                        <svg class="w-9 h-9 text-blue-500 transition duration-75 dark:text-blue-900 group-hover:text-blue-900 dark:group-hover:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                        </svg>
                    </div>
                    <nav class="py-5 border-b border-blue/10">
                        <h3 class="font-bold text-xl hover:underline">Siguiente turno:</h3>
                        <div class="py-2">
                            <p class="text-xl ps-4">- Jueves 18:</p>
                            <p class="text-lg ps-6">10:00 -> 14:00</p>
                        </div>
                    </nav>
                </section>

                <section class="relative w-full bg-white mt-9 px-5 pb-8 rounded-lg shadow-md mt-2 ml-4">
                    <!-- Título con iconos -->
                    <div class="flex items-center justify-between py-4 border-b border-blue/10">
                        <div class="flex items-center space-x-2">
                            <!-- Icono de campana -->
                            <svg class="w-7 h-7 text-blue-900 pt-1" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2a7 7 0 00-7 7v3.29l-1.29 1.29a1 1 0 00-.21 1.09A1 1 0 005 15h14a1 1 0 00.91-1.41l-1.29-1.29V9a7 7 0 00-7-7zm0 18a3 3 0 01-2.82-2h5.64A3 3 0 0112 20z"/>
                            </svg>
                            <h2 class="text-xl font-bold">Notificaciones</h2>
                        </div>
                        <!-- Icono de ajustes -->
                        <svg class="w-8 h-8 text-blue-900 cursor-pointer" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 7a2 2 0 100-4 2 2 0 000 4zm0 5a2 2 0 100-4 2 2 0 000 4zm0 5a2 2 0 100-4 2 2 0 000 4z"/>
                        </svg>
                    </div>

                    <!-- Lista de notificaciones -->
                    <ul class="mt-4 space-y-2">

                        <li class="flex flex-col items-center px-4 py-4 bg-blue-50 rounded-lg">
                            <div class="flex justify-between w-full mb-4">
                                <div>
                                    <h3><strong>Cambio de turno con María</strong></h3>
                                </div>
                                <div class="flex-grow"></div> <!-- Espaciador flexible para empujar los botones a la derecha -->
                                <div class="flex space-x-2"> <!-- Añadir espacio entre los botones -->
                                    <a href="/" class="bg-blue-500 px-2 py-1 rounded-xl text-xs text-white">Aceptar</a>
                                    <a href="/" class="bg-red-500 px-2 py-1 rounded-xl text-xs text-white">Rechazar</a>
                                </div>
                            </div>
                            <div class="w-full"> <!-- Contenedor de la tabla -->
                                <table class="w-full border-collapse border-0">
                                    <thead>
                                    <tr>
                                        <th class="px-2 py-1 text-center">Turno Actual</th>
                                        <th class="px-2 py-1 text-center">Posible turno</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <tr>
                                        <td class="px-1 py-1 bg-blue-50 text-sky-900 text-sm text-center font-semibold rounded">14/2/2024 <br> (12:00-16:00)</td>
                                        <td class="px-1 py-1 bg-blue-50 text-sky-900 text-sm text-center font-semibold rounded">13/2/2024 <br> (8:00-12:00)</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </li>
                        <li class="flex items-center px-4 py-4 bg-blue-50 rounded-lg">
                            <span class="text-md text-gray-700">Formularios a rellenar de Diciembre</span>
                        </li>
                        <li class="flex items-center px-4 py-4 bg-blue-50 rounded-lg">
                            <span class="text-md text-gray-700">Encuesta de satisfacción Noviembre</span>
                        </li>
                        <li class="flex items-center px-4 py-4 bg-blue-50 rounded-lg">
                            <span class="text-md text-gray-700">Solicitud de día libre aceptada</span>
                        </li>
                        <li class="flex items-center px-4 py-4 bg-blue-50 rounded-lg">
                            <span class="text-md text-gray-700">Encuesta de satisfacción Octubre</span>
                        </li>
                        <li class="flex items-center px-4 py-4 bg-blue-50 rounded-lg">
                            <span class="text-md text-gray-700">Encuesta de satisfacción Septiembre</span>
                        </li>
                        <li class="flex items-center px-4 py-4 bg-blue-50 rounded-lg">
                            <span class="text-md text-gray-700">Baja por maternidad Agustina</span>
                        </li>
                        <li class="flex items-center px-4 py-4 bg-blue-50 rounded-lg">
                            <span class="text-md text-gray-700">Encuesta de satisfacción Agosto</span>
                        </li>
                        @if(auth()->user()->notifications->isEmpty())
                            <li class="px-4 py-4 text-center text-gray-500">
                                No hay nuevas notificaciones.
                            </li>
                        @else
                            @foreach(auth()->user()->notifications as $notification)
                                @php
                                    $isUnread = !$notification->read;
                                    $bgColor = $isUnread ? 'bg-blue-100' : 'bg-blue-50';
                                @endphp

                                <li class="flex items-center px-4 py-4 rounded-lg {{ $bgColor }}">
                                    @if($notification->url)
                                        <a href="{{ $notification->url }}" class="text-md text-blue-700 font-semibold hover:underline">
                                            {{ $notification->message }}
                                        </a>
                                    @else
                                        <span class="text-md text-gray-700">{{ $notification->message }}</span>
                                    @endif
                                </li>
                    @endforeach
                    @endif
                    </ul>

                </section>
                <x-sections.all-sections></x-sections.all-sections>

            </div>
            <div class="flex flex-col w-2/3 mt-20 pe-12">
                <section class="relative w-full bg-white px-8 pb-8 rounded-lg shadow-md mt-2 ml-4">
                    <div class="absolute top-4 right-4 pe-4">
                        <!-- Icono con distintas personas -->
                        <svg class="w-9 h-9 text-blue-500 transition duration-75 dark:text-blue-900 group-hover:text-blue-900 dark:group-hover:text-white" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 11a4 4 0 100-8 4 4 0 000 8zm6 0a4 4 0 100-8 4 4 0 000 8zm-6 2c-4 0-7 2-7 5v1h14v-1c0-3-3-5-7-5zm6 0h2c3 0 6 2 6 5v1h-4v-1c0-2-1-3-3-4z"/>
                        </svg>
                    </div>
                    <nav class="py-5 border-b border-blue/10">
                        <div class="flex items-center space-x-2">
                            <!-- Icono de calendario -->
                            <svg class="w-6 h-6 text-blue-900" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M3 3h18a1 1 0 011 1v16a1 1 0 01-1 1H3a1 1 0 01-1-1V4a1 1 0 011-1zm0 2v4h18V5H3zm0 6v8h18v-8H3zM8 7h2v2H8V7zm6 0h2v2h-2V7z"/>
                            </svg>
                            <h3 class="font-bold text-xl hover:underline">Horario semanal:</h3>
                        </div>
                    </nav>
                    <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-2 text-center pt-4">
                        <div class="font-bold hidden lg:block text-black">Lunes</div>
                        <div class="font-bold hidden lg:block text-black">Martes</div>
                        <div class="font-bold hidden lg:block text-black">Miércoles</div>
                        <div class="font-bold hidden lg:block text-black">Jueves</div>
                        <div class="font-bold hidden lg:block text-black">Viernes</div>

                        <div class="border bg-sky-400 text-white rounded-lg p-2 shadow">
                            <h3 class="font-bold text-lg">01</h3>
                            <ul class="mt-2">
                                <li class="text-white rounded p-1 mb-1">
                                    <strong>08:00 - 12:00</strong>
                                    <div class="mt-2 flex flex-wrap gap-1">
                                        <span class="bg-blue-50 text-sky-800 text-xs font-semibold py-1 px-2 rounded">Juan Pérez</span>
                                        <span class="bg-blue-50 text-sky-800 text-xs font-semibold py-1 px-2 rounded">María López</span>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div class="border bg-sky-500 text-white rounded-lg p-2 shadow">
                            <h3 class="font-bold text-lg">02</h3>
                            <ul class="mt-2">
                                <li class="text-white rounded p-1 mb-1">
                                    <strong>10:00 - 14:00</strong>
                                    <div class="mt-2 flex flex-wrap gap-1">
                                        <span class="bg-blue-50 text-sky-800 text-xs font-semibold py-1 px-2 rounded">Pedro García</span>
                                        <span class="bg-blue-50 text-sky-800 text-xs font-semibold py-1 px-2 rounded">Carla Sánchez</span>
                                        <span class="bg-blue-50 text-sky-800 text-xs font-semibold py-1 px-2 rounded">Luis Fernández</span>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div class="border bg-sky-600 text-white rounded-lg p-2 shadow">
                            <h3 class="font-bold text-lg">03</h3>
                            <ul class="mt-2">
                                <li class="text-white rounded p-1 mb-1">
                                    <strong>12:00 - 16:00</strong>
                                    <div class="mt-2 flex flex-wrap gap-1">
                                        <span class="bg-blue-50 text-sky-800 text-xs font-semibold py-1 px-2 rounded">Ana Martínez</span>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div class="border bg-sky-700 text-white rounded-lg p-2 shadow">
                            <h3 class="font-bold text-lg">04</h3>
                            <ul class="mt-2">
                                <li class="text-white rounded p-1 mb-1">
                                    <strong>14:00 - 18:00</strong>
                                    <div class="mt-2 flex flex-wrap gap-1">
                                        <span class="bg-blue-50 text-sky-800 text-xs font-semibold py-1 px-2 rounded">Diego Ramírez</span>
                                        <span class="bg-blue-50 text-sky-800 text-xs font-semibold py-1 px-2 rounded">Laura González</span>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div class="border bg-sky-800 text-white rounded-lg p-2 shadow">
                            <h3 class="font-bold text-lg">05</h3>
                            <ul class="mt-2">
                                <li class="text-white rounded p-1 mb-1">
                                    <strong>16:00 - 20:00</strong>
                                    <div class="mt-2 flex flex-wrap gap-1">
                                        <span class="bg-blue-50 text-sky-800 text-xs font-semibold py-1 px-2 rounded">Javier Castro</span>
                                        <span class="bg-blue-50 text-sky-800 text-xs font-semibold py-1 px-2 rounded">Elena Ruiz</span>
                                        <span class="bg-blue-50 text-sky-800 text-xs font-semibold py-1 px-2 rounded">Mario Ortega</span>
                                        <span class="bg-blue-50 text-sky-800 text-xs font-semibold py-1 px-2 rounded">Sofía Morales</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                </section>
                <section class="relative w-full bg-white p-8 rounded-lg shadow-md mt-8 ml-4">
                    <div class="flex justify-around">
                        <button class="w-40 py-4 bg-sky-900 text-white text-lg font-semibold rounded-lg shadow-md hover:bg-blue-600 transition duration-200">
                            Solicitudes
                        </button>
                        <button class="w-40 py-4 bg-sky-900 text-white text-lg font-semibold rounded-lg shadow-md hover:bg-blue-600 transition duration-200">
                            Formularios
                        </button>
                        <button class="w-40 py-4 bg-sky-900 text-white text-lg font-semibold rounded-lg shadow-md hover:bg-blue-600 transition duration-200">
                            Estadísticas
                        </button>
                        <button class="w-40 py-4 bg-sky-900 text-white text-lg font-semibold rounded-lg shadow-md hover:bg-blue-600 transition duration-200">
                            Satisfacción
                        </button>
                    </div>
                </section>

                <x-users.all-employees :section="$section"></x-users.all-employees>

            </div>
        </div>
    @endif

</x-layout>
