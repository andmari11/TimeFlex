@php

    if (auth()->user()->role === 'employee') {
        // Si es un empleado, obtenemos su sección
        $section = auth()->user()->section;
    }
@endphp
<x-layout :title="'Mi equipo'">
    @if($section)
        <x-page-heading>Sección de {{ $section->name }}</x-page-heading>
        <div class="bg-white p-8 rounded-lg shadow-md mx-10 my-5">
            <section class="text-center pt-2">

                <form action="/search" class="mt-2 mb-7">
                    <input type="text" name="q" placeholder="Busca compañeros..." class="rounded-xl border px-5 py-4 w-full max-w-xl bg-white/25 focus:outline-none border-gray-300"/>
                </form>

            </section>
            <div class="flex flex-wrap gap-10">
                @foreach($section->users as $employee)
                    <x-users.employee-section :employee="$employee" :showGraphs="false"></x-users.employee-section>
                @endforeach
            </div>

        </div>
    @else
        <x-page-heading>Todas las secciones - Empleados</x-page-heading>
        <div class="bg-white p-8 rounded-lg shadow-md mx-10 my-5">
            <section class="text-center pt-2">
                <form action="/search" class="mt-2 mb-7">
                    <input type="text" name="q" placeholder="Busca compañeros..." class="rounded-xl border px-5 py-4 w-full max-w-xl bg-white/25 focus:outline-none border-gray-300"/>
                </form>

                @if(auth()->user()->role === 'admin')
                    <a href="/export-csv"
                       class="mt-4 px-3 py-1 bg-gray-600 text-white font-semibold rounded-md shadow-md hover:bg-gray-700 transition-all">
                        Descargar CSV
                    </a>
                @endif

            </section>
            <!-- Línea divisoria -->
            <hr class="my-6 border-t border-gray-300">

        <div class="flex flex-wrap -mx-3">
            @foreach(auth()->user()->company->employees as $employee)
                <div class="w-1/4 px-3">
                    <x-users.employee-section :employee="$employee" :showGraphs="false"></x-users.employee-section>
                </div>
            @endforeach
        </div>

        </div>
    @endif
</x-layout>
