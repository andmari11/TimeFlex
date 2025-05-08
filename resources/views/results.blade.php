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
            <div class="flex flex-wrap gap-10">
                @foreach($employees as $employee)
                    <x-users.employee-section :employee="$employee" :showGraphs="false"></x-users.employee-section>
                @endforeach
            </div>

        </div>
</x-layout>
