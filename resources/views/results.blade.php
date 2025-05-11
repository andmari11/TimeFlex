@php

    if (auth()->user()->role === 'employee') {
        // Si es un empleado, obtenemos su sección
        $section = auth()->user()->section;
    }
@endphp

<x-layout :title="'Resultados'">
        <x-page-heading>Resultados</x-page-heading>
        <div class="bg-white p-8 rounded-lg shadow-md mx-10 my-10">
            <section class="text-center pt-2">

                <form action="/search" class="mt-2 mb-7">
                    <input type="text" name="q" placeholder="Busca compañeros..." value="{{$query}}" class="rounded-xl border px-5 py-4 w-full max-w-xl bg-white/25 focus:outline-none border-gray-300"/>
                </form>

            </section>
            @if($employees->isEmpty())
                <div class="w-full flex flex-col items-center justify-center py-20">
                    <p class="text-3xl font-semibold text-gray-600 text-center mb-6">
                        No se encontraron compañeros<br> que coincidan con <span class="text-gray-800">“{{ $query }}”</span>
                    </p>
                    <a href="{{ url('/equipo') }}" class="mt-8 inline-block bg-blue-500 hover:bg-blue-400 text-white px-6 py-3 rounded-lg text-lg font-medium">Volver</a>
                </div>
            @else
                <div class="flex flex-wrap gap-10 justify-center">
                    @foreach($employees as $employee)
                        <x-users.employee-section :employee="$employee" :showGraphs="false"/>
                    @endforeach
                </div>
            @endif

        </div>
</x-layout>
