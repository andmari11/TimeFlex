<x-layout :title="'Mi horario'">
    <x-page-heading>Tus horarios</x-page-heading>



    <div class="w-full max-w-2xl bg-white p-8 rounded-lg shadow-md mt-10 mx-auto">
        <a class ="btn bg-red-500 p-1 rounded-lg text-white" href="/fastapi-schedule">Crear</a>
        <a class ="btn bg-blue-500 p-1 rounded-lg text-white" href="">Refrescar</a>

        @foreach($schedules as $schedule)

            <x-schedules.single-schedule-item :schedule="$schedule"></x-schedules.single-schedule-item>
            <hr class="my-6">
        @endforeach
    </div>
</x-layout>
