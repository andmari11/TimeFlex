
<x-layout :title="'Calendarios disponibles'">
    <div class="container mx-auto py-10 px-6">
        <div class="text-center mb-10">
            <h1 class="text-4xl font-bold text-gray-800">Tus horarios</h1>
            <p class="text-gray-600 mt-2">Consulta los horarios de forma fácil y rápida.</p>
        </div>

        @if(auth()->user()->role === 'admin')
            <div class="text-right mb-6">
                <a href="/horario-registrar" class="btn bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded shadow-md">
                    Crear nuevo horario
                </a>
            </div>
        @endif

        @if($schedules->isEmpty())
            <p class="text-center text-gray-500 text-lg">No hay horarios disponibles en este momento.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($schedules as $schedule)

                    <x-schedules.single-schedule-item :schedule="$schedule"></x-schedules.single-schedule-item>
                @endforeach
            </div>
        @endif
        @if(!$schedules->isEmpty())
            <div class="py-8">
                {{ $schedules->links() }}
            </div>
        @endif
    </div>
</x-layout>
