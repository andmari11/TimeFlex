<x-layout :title="'Mi horario'">
    <x-page-heading>Bienvenido a tu página de horarios</x-page-heading>



    <div class="w-full max-w-xl bg-white p-8 rounded-lg shadow-md mt-10 mx-auto">
        <a class ="btn bg-red-500 p-1 rounded-lg text-white" href="/fastapi-schedule">Crear</a>
        <a class ="btn bg-blue-500 p-1 rounded-lg text-white" href="">Refrescar</a>
        <div class="p-4 bg-gray-100 mb-6 shadow rounded-xl my-1 relative mt-7">
            <div class="absolute top-2 right-2">
            </div>
            <div class="flex flex-col gap-2">
                <p class="text-black text-bold text-l"><strong>ID:</strong> Diciembre</p>
                <p class="text-blue-600 text-bold text-l"><strong>Estado:</strong> Recopilando preferencias</p>
            </div>
            <div class="flex justify-center mt-4">
                <a
                    href="/horario/1"
                    class="w-full bg-blue-600 hover:bg-blue-500 text-center px-4 py-2 rounded-xl text-white text-bold transition-all duration-300"
                >
                    Ver detalles
                </a>
            </div>
        </div>

        <div class="p-4 bg-gray-100 mb-6 shadow rounded-xl my-1 relative">
            <div class="absolute top-2 right-2">

            </div>
            <div class="flex flex-col gap-2">
                <p class="text-black text-bold text-l"><strong>ID:</strong> Noviembre</p>
                <p class="text-green-600 text-bold text-l"><strong>Estado:</strong> En curso</p>
            </div>
            <div class="flex justify-center mt-4">
                <a
                    href="/horario/1"
                    class="w-full bg-blue-600 hover:bg-blue-500 text-center px-4 py-2 rounded-xl text-white text-bold transition-all duration-300"
                >
                    Ver detalles
                </a>
            </div>
        </div>

        <div class="p-4 bg-gray-100 mb-6 shadow rounded-xl my-1 relative">
            <div class="absolute top-2 right-2">
                <button
                    class="bg-gray-300 hover:bg-gray-200 text-gray-800 px-2 py-1 rounded text-sm transition-all duration-300"
                >
                    Eliminar
                </button>
            </div>
            <div class="flex flex-col gap-2">
                <p class="text-black text-bold text-l"><strong>ID:</strong> Octubre</p>
                <p class="text-red-500 text-bold text-l"><strong>Estado:</strong> Finalizado</p>
            </div>
            <div class="flex justify-center mt-4">
                <a
                    href="/horario/1"
                    class="w-full bg-blue-600 hover:bg-blue-500 text-center px-4 py-2 rounded-xl text-white text-bold transition-all duration-300"
                >
                    Ver detalles
                </a>
            </div>
        </div>
        <div class="p-4 bg-gray-100 mb-6 shadow rounded-xl my-1 relative">
            <div class="absolute top-2 right-2">
                <button
                    class="bg-gray-300 hover:bg-gray-200 text-gray-800 px-2 py-1 rounded text-sm transition-all duration-300"
                >
                    Eliminar
                </button>
            </div>
            <div class="flex flex-col gap-2">
                <p class="text-black text-bold text-l"><strong>ID:</strong> Octubre</p>
                <p class="text-red-500 text-bold text-l"><strong>Estado:</strong> Finalizado</p>
            </div>
            <div class="flex justify-center mt-4">
                <a
                    href="/horario/1"
                    class="w-full bg-blue-600 hover:bg-blue-500 text-center px-4 py-2 rounded-xl text-white text-bold transition-all duration-300"
                >
                    Ver detalles
                </a>
            </div>
        </div>
        <div class="p-4 bg-gray-100 mb-6 shadow rounded-xl my-1 relative">
            <div class="absolute top-2 right-2">
                <button
                    class="bg-gray-300 hover:bg-gray-200 text-gray-800 px-2 py-1 rounded text-sm transition-all duration-300"
                >
                    Eliminar
                </button>
            </div>
            <div class="flex flex-col gap-2">
                <p class="text-black text-bold text-l"><strong>ID:</strong> Octubre</p>
                <p class="text-red-500 text-bold text-l"><strong>Estado:</strong> Finalizado</p>
            </div>
            <div class="flex justify-center mt-4">
                <a
                    href="/horario/1"
                    class="w-full bg-blue-600 hover:bg-blue-500 text-center px-4 py-2 rounded-xl text-white text-bold transition-all duration-300"
                >
                    Ver detalles
                </a>
            </div>
        </div>


            <!--@foreach($schedules as $schedule)

            <x-schedules.single-schedule-item :schedule="$schedule"></x-schedules.single-schedule-item>
            <hr class="my-6">
        @endforeach-->
    </div>
</x-layout>
