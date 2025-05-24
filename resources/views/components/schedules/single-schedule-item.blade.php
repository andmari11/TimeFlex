<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="p-4 bg-white shadow rounded-xl relative min-h-[200px] flex flex-col justify-between" x-data="{ openModalEliminacion: false }">
    <!-- Botón de opciones -->
    <div class="absolute top-2 right-2" x-data="{ open_options_menu: false }">
        @if(auth()->user()->role == 'admin')

            <button
                @click="open_options_menu = !open_options_menu"
                type="button"
                class="px-3 py-1 text-black rounded-full text-lg font-bold">
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

                <button @click="openModalEliminacion = true"
                        class="block px-4 py-2 text-gray-800 hover:bg-red-100 text-sm transition-all w-full text-left">
                    Eliminar
                </button>

                <form method="POST" action="/horario/{{$schedule->id}}/delete" id="delete-form-{{$schedule->id}}" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        @endif
    </div>

    <!-- Modal de confirmación -->
    <div x-show="openModalEliminacion" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-lg max-w-lg w-full p-6">
            <h2 class="text-lg font-bold mb-4">Confirmar eliminación</h2>
            <p class="text-gray-700 mb-4">¿Estás seguro de que deseas eliminar este elemento? Esta acción no se puede deshacer.</p>

            <div class="flex justify-end space-x-4">
                <button @click="openModalEliminacion = false"
                        class="bg-gray-500 hover:bg-gray-400 text-white font-bold py-2 px-4 rounded">
                    Cancelar
                </button>
                <form method="POST" action="/horario/{{$schedule->id}}/delete">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="bg-red-600 hover:bg-red-500 text-white font-bold py-2 px-4 rounded">
                        Confirmar eliminación
                    </button>
                </form>
            </div>
        </div>
    </div>
<!-- Contenido principal -->
    <div class="flex flex-col gap-2 mt-4">
        <p class="text-lg  text-black">
            <strong>ID:</strong> {{ $schedule->name }}
        </p>

        <p class="text-lg  text-{{
                $schedule->status == 'success' ? 'green' :
                ($schedule->status == 'not_optimized' ? 'cyan' :
                ($schedule->status == 'finalized' ? 'amber' :
                ($schedule->status == 'failed' ? 'red' :
                ($schedule->status == 'regenerado' ? 'indigo' :
                ($schedule->status == 'pending' ? 'yellow' : 'gray')))))
            }}-600">
            <strong>Estado:</strong>
            {{
                $schedule->status == 'success' ? 'Éxito' :
                ($schedule->status == 'not_optimized' ? 'No optimizado' :
                ($schedule->status == 'finalized' ? 'Finalizado' :
                ($schedule->status == 'failed' ? 'Fallido' :
                ($schedule->status == 'regenerado' ? 'Regenerado' :
                ($schedule->status == 'pending' ? 'Pendiente' : 'Desconocido')))))
            }}
        </p>

        @if($schedule->status == 'failed' && $schedule->simulation_message)
            <p class="text-gray-700 text-sm">
                {!! nl2br(e(\Illuminate\Support\Str::limit($schedule->simulation_message, 200, '...'))) !!}

                <!-- Botón para abrir el modal -->
                <button @click="openModal = true" class="text-blue-600 hover:underline text-sm">
                    Ver más
                </button>
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

    <!-- Modal -->
    <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-lg max-w-lg w-full p-6 overflow-y-auto max-h-[80vh]">
            <h2 class="text-lg font-bold mb-4">Mensaje de simulación</h2>
            <div class="text-gray-700 text-sm">
                {!! nl2br(e($schedule->simulation_message)) !!}
            </div>
            <button @click="openModal = false" class="mt-4 bg-red-600 hover:bg-red-500 text-white font-bold py-2 px-4 rounded">
                Cerrar
            </button>
        </div>
    </div>
</div>
