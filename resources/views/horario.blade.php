<x-layout :title="'Mi horario'">
    <x-page-heading>Bienvenido a tu página de horarios</x-page-heading>

    <a class ="btn bg-red-500 p-1 rounded-lg text-white" href="/pruebaAPI">Crear</a>
    <a class ="btn bg-blue-500 p-1 rounded-lg text-white" href="">Refrescar</a>

    <div class="w-full max-w-xl bg-white p-8 rounded-lg shadow-md mt-10">
        @foreach($schedules as $schedule)
            <p><strong>ID:</strong> {{ $schedule->id }}</p>
            <p><strong>Company ID:</strong> {{ $schedule->company_id }}</p>
            <p><strong>Estado:</strong> {{ $schedule->status }}</p>


            <p><strong>Schedule JSON</strong></p>
            @if($schedule->scheduleJSON)
                <ul class="list-disc list-inside">
                    @foreach($schedule->scheduleJSON as $user => $dates)
                        <strong>{{ $user }}:</strong>
                        <ul class="ml-4 list-decimal list-inside">
                            @foreach($dates as $date)
                                <li>{{ $date }}</li>
                            @endforeach
                        </ul>
                    @endforeach
                </ul>
            @else
                <p>No hay horarios disponibles.</p>
            @endif

            <hr class="my-6">
        @endforeach
    </div>
</x-layout>
