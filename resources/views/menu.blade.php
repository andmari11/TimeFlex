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

                    <x-sections.all-sections></x-sections.all-sections>
                    <x-users.all-employees :section="$section"></x-users.all-employees>
                </div>
                <div class="flex flex-col w-2/3 mt-20 pe-12">
                    <section class="relative w-full bg-white px-8 pb-8 rounded-lg shadow-md mt-2 ml-4">
                        <div class="absolute top-4 right-4">
                            <svg class="w-9 h-9 text-blue-500 transition duration-75 dark:text-blue-900 group-hover:text-blue-900 dark:group-hover:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                            </svg>
                        </div>
                        <nav class="py-5 border-b border-blue/10">
                            <h3 class="font-bold text-xl hover:underline">Horario semanal:</h3>

                        </nav>
                        <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-2 text-center pt-4">
                            <div class="font-bold hidden lg:block">Lunes</div>
                            <div class="font-bold hidden lg:block">Martes</div>
                            <div class="font-bold hidden lg:block">Miércoles</div>
                            <div class="font-bold hidden lg:block">Jueves</div>
                            <div class="font-bold hidden lg:block">Viernes</div>

                            <div class="border bg-blue-100 rounded-lg p-2 shadow">
                                <h3 class="font-bold text-lg">
                                    01
                                </h3>
                                <ul class="mt-2">
                                    <li class="bg-blue-100 text-black rounded p-1 mb-1">
                                        <strong>08:00 - 12:00</strong>
                                        <div class="mt-2 flex flex-wrap gap-1">
                    <span class="bg-blue-200 text-blue-800 text-xs font-semibold py-1 px-2 rounded">
                        Juan Pérez
                    </span>
                                            <span class="bg-blue-200 text-blue-800 text-xs font-semibold py-1 px-2 rounded">
                        María López
                    </span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="border bg-green-100 rounded-lg p-2 shadow">
                                <h3 class="font-bold text-lg">
                                    02
                                </h3>
                                <ul class="mt-2">
                                    <li class="bg-green-100 text-black rounded p-1 mb-1">
                                        <strong>10:00 - 14:00</strong>
                                        <div class="mt-2 flex flex-wrap gap-1">
                    <span class="bg-blue-200 text-blue-800 text-xs font-semibold py-1 px-2 rounded">
                        Pedro García
                    </span>
                                            <span class="bg-blue-200 text-blue-800 text-xs font-semibold py-1 px-2 rounded">
                        Carla Sánchez
                    </span>
                                            <span class="bg-blue-200 text-blue-800 text-xs font-semibold py-1 px-2 rounded">
                        Luis Fernández
                    </span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="border bg-yellow-100 rounded-lg p-2 shadow">
                                <h3 class="font-bold text-lg">
                                    03
                                </h3>
                                <ul class="mt-2">
                                    <li class="bg-yellow-100 text-black rounded p-1 mb-1">
                                        <strong>12:00 - 16:00</strong>
                                        <div class="mt-2 flex flex-wrap gap-1">
                    <span class="bg-blue-200 text-blue-800 text-xs font-semibold py-1 px-2 rounded">
                        Ana Martínez
                    </span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="border bg-purple-100 rounded-lg p-2 shadow">
                                <h3 class="font-bold text-lg">
                                    04
                                </h3>
                                <ul class="mt-2">
                                    <li class="bg-purple-100 text-black rounded p-1 mb-1">
                                        <strong>14:00 - 18:00</strong>
                                        <div class="mt-2 flex flex-wrap gap-1">
                    <span class="bg-blue-200 text-blue-800 text-xs font-semibold py-1 px-2 rounded">
                        Diego Ramírez
                    </span>
                                            <span class="bg-blue-200 text-blue-800 text-xs font-semibold py-1 px-2 rounded">
                        Laura González
                    </span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="border bg-red-100 rounded-lg p-2 shadow">
                                <h3 class="font-bold text-lg">
                                    05
                                </h3>
                                <ul class="mt-2">
                                    <li class="bg-red-100 text-black rounded p-1 mb-1">
                                        <strong>16:00 - 20:00</strong>
                                        <div class="mt-2 flex flex-wrap gap-1">
                    <span class="bg-blue-200 text-blue-800 text-xs font-semibold py-1 px-2 rounded">
                        Javier Castro
                    </span>
                                            <span class="bg-blue-200 text-blue-800 text-xs font-semibold py-1 px-2 rounded">
                        Elena Ruiz
                    </span>
                                            <span class="bg-blue-200 text-blue-800 text-xs font-semibold py-1 px-2 rounded">
                        Mario Ortega
                    </span>
                                            <span class="bg-blue-200 text-blue-800 text-xs font-semibold py-1 px-2 rounded">
                        Sofía Morales
                    </span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </section>

                </div>
            </div>
        </x-sidebar>

    @else
        <x-users.all-employees :section="$section"></x-users.all-employees>

    @endif


</x-layout>
