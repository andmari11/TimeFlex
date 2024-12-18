<div class="p-4 bg-gray-100 shadow rounded-xl my-1">
    <div class="flex flex-col gap-2">
        <p class="text-black text-bold text-l"><strong>ID:</strong> {{ $schedule->id }}</p>
        <p class="text-black text-bold text-l"><strong>Estado:</strong> {{ $schedule->status }}</p>
    </div>
    <div class="flex justify-center mt-4">
        <a
            href="/horario/{{$schedule->id}}"
            class="w-full bg-cyan-500 hover:bg-cyan-400 text-center px-4 py-2 rounded-xl text-white text-bold transition-all duration-300 hover:scale-105"
        >
            Ver detalles
        </a>
    </div>
</div>

