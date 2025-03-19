<div class="p-4 bg-white mb-6 shadow rounded-xl my-1 relative min-h-[200px] flex flex-col justify-between">
    <div class="absolute top-2 right-2">

        <a href="/shift-exchange/{{$schedule->id}}/worker/0/turno/0/0" class="bg-gray-300 hover:bg-gray-200 text-gray-800 px-2 py-1 rounded text-sm transition-all duration-300">
            Cambio de turno
        </a>
        <a href="/horario/{{$schedule->id}}/edit" class="bg-gray-300 hover:bg-gray-200 text-gray-800 px-2 py-1 rounded text-sm transition-all duration-300 ms-2">
            Editar
        </a>
        <button
            class="bg-gray-300 hover:bg-gray-200 text-gray-800 px-2 py-1 rounded text-sm transition-all duration-300 ms-2">
            Eliminar
        </button>
    </div>
    <div class="flex flex-col gap-2">
        <p class="text-black text-bold text-l"><strong>ID:</strong> {{ $schedule->name }}</p>
        <p class="text-{{
            $schedule->status == 'success' ? 'green' :
            ($schedule->status == 'not_optimized' ? 'blue' :
            ($schedule->status == 'finalized' ? 'orange' :
            ($schedule->status == 'failed' ? 'red' : 'black')))
        }}-600 text-bold text-l">
            <strong>Estado:</strong>
            {{
                $schedule->status == 'success' ? 'Éxito' :
                ($schedule->status == 'not_optimized' ? 'No optimizado' :
                ($schedule->status == 'finalized' ? 'Finalizado' :
                ($schedule->status == 'failed' ? 'Fallido' : 'Desconocido')))
            }}
        </p>
        @if($schedule->status == 'failed' && $schedule->simulation_message )
            <p class="text-black text-l">
                {!! nl2br(e(\Illuminate\Support\Str::limit($schedule->simulation_message, 200, '...'))) !!}
            </p>
        @endif


    </div>
    <div class="flex justify-center mt-4">
        <a
            href="/horario/{{$schedule->id}}"
            class="w-full bg-blue-600 hover:bg-blue-500 text-center px-4 py-2 rounded-xl text-white text-bold transition-all duration-300"
        >
            Ver detalles
        </a>
    </div>
</div>
