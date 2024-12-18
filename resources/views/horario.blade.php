<x-layout :title="'Mi horario'">
    <x-page-heading>Bienvenido a tu página de horarios</x-page-heading>

    <a class ="btn bg-red-500 p-1 rounded-lg text-white" href="/pruebaAPI">Crear</a>
    <a class ="btn bg-blue-500 p-1 rounded-lg text-white" href="">Refrescar</a>

    <div class="w-full max-w-xl bg-white p-8 rounded-lg shadow-md mt-10 mx-5">
        @foreach($schedules as $schedule)

            <x-schedules.single-schedule-item :schedule="$schedule"></x-schedules.single-schedule-item>
            <hr class="my-6">
        @endforeach
    </div>
</x-layout>
