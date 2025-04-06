<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="p-4 bg-white shadow rounded-xl relative min-h-[200px] flex flex-col justify-between">
    <!-- Botón de opciones -->
    <div class="absolute top-2 right-2" x-data="{ open_options_menu: false }">
        @if(auth()->user()->role == 'admin')

        <button
            @click="open_options_menu = !open_options_menu"
            type="button"
            class="px-3 py-1 text-black rounded-full text-lg  font-bold">
            &#x22EE;
        </button>

        <!-- Menú desplegable -->
        <div x-show="open_options_menu" @click.away="open_options_menu = false"
             class="absolute right-0 z-10 mt-2 w-48 bg-white shadow-lg rounded-md ring-1 ring-black ring-opacity-5">

            <a href="/shift-exchange/{{$schedule->id}}/worker/0/turno/0/0"
               class="block px-4 py-2 text-gray-800 hover:bg-gray-100 text-sm transition-all">
                Cambio de turno
            </a>
            @if($schedule->status != 'success')
                <a href="/horario/{{$schedule->id}}/optimize"
                   class="block px-4 py-2 text-gray-800 hover:bg-gray-100 text-sm transition-all">
                    Optimizar
                </a>
            @endif
            <a href="/horario/{{$schedule->id}}/edit"
               class="block px-4 py-2 text-gray-800 hover:bg-gray-100 text-sm transition-all">
                Editar
            </a>
            <button
                class="block px-4 py-2 text-gray-800 hover:bg-red-100 text-sm transition-all w-full text-left"
                onclick="event.preventDefault(); document.getElementById('delete-form-{{$schedule->id}}').submit();">
                Eliminar
            </button>

            <form method="POST" action="/horario/{{$schedule->id}}/delete" id="delete-form-{{$schedule->id}}" class="hidden">
                @csrf
                @method('DELETE')
            </form>


        </div>
        @endif
</div>

    <!-- Contenido principal -->
    <div class="flex flex-col gap-2 mt-4">
        <p class="text-lg  text-black">
            <strong>ID:</strong> {{ $schedule->name }}
        </p>

        <p class="text-lg  text-{{
            $schedule->status == 'success' ? 'green' :
            ($schedule->status == 'not_optimized' ? 'blue' :
            ($schedule->status == 'finalized' ? 'orange' :
            ($schedule->status == 'failed' ? 'red' : 'black')))
        }}-600">
            <strong>Estado:</strong>
            {{
                $schedule->status == 'success' ? 'Éxito' :
                ($schedule->status == 'not_optimized' ? 'No optimizado' :
                ($schedule->status == 'finalized' ? 'Finalizado' :
                ($schedule->status == 'failed' ? 'Fallido' : 'Desconocido')))
            }}
        </p>

        @if($schedule->status == 'failed' && $schedule->simulation_message)
            <p class="text-gray-700 text-sm">
                {!! nl2br(e(\Illuminate\Support\Str::limit($schedule->simulation_message, 200, '...'))) !!}
            </p>
        @endif
    </div>

    <!-- Botón de acción -->
    <div class="mt-4">
        <a href="/horario/{{$schedule->id}}"
           class="block w-full text-center bg-blue-600 hover:bg-blue-500 text-white font-bold py-2 rounded-xl transition-all">
            Ver detalles
        </a>
    </div>
</div>

