<x-layout :title="'Calendario'">
    <x-page-heading> Estadísticas de {{ $schedule->section->name }}</x-page-heading>

    <div class="p-4 m-10 bg-white shadow rounded-xl">
        <div class="flex justify-end">
            <div class="flex space-x-0">
                <a href="/horario/{{ $schedule->id }}" class="bg-gray-200 text-black text-s font-semibold py-2 px-4 rounded-l focus:outline-none">Horario de equipo</a>
                <a href="/horario/personal/{{ $schedule->id  }}" class="bg-gray-200 text-black text-s font-semibold py-2 px-4  focus:outline-none">Horario personal</a>
                <a href="/stats" class="bg-sky-900 text-white text-s font-semibold py-2 px-4 rounded-r focus:outline-none">Estadísticas</a>

            </div>
        </div>
        <img src="{{ $imgUrl }}" alt="graph">
    </div>
</x-layout>
