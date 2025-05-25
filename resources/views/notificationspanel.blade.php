<x-layout title="Centro de Notificaciones">
    <x-page-heading>Bienvenido a tu centro de notificaciones</x-page-heading>
    <div style="display: flex; justify-content: center; margin-top: -25px;">
        <button onclick="window.location.href='/menu'" style="padding: 0; font-size: 16px; background: none; color: gray; border: none; text-decoration: underline; cursor: pointer;">
            Volver al menú principal
        </button>
    </div>
    <div class="max-w-5xl mx-auto py-10">
        <h1 class="text-3xl font-bold text-sky-900 mb-6 text-center">📬 Notificaciones Recibidas</h1>
        <!-- Filtro para elegir el tipo de notificacion -->
        <form method="GET" action="{{ route('notifications.panel') }}" class="mb-6 text-center">
            <label for="tipo" class="font-medium text-gray-700 mr-2">Filtrar por tipo:</label>
            <select name="tipo" id="tipo" class="border border-gray-300 rounded px-3 py-1">
                <option value="todas" {{ request('tipo') == 'todas' ? 'selected' : '' }}>Todas</option>
                @foreach($tipos as $tipo)
                    <option value="{{ $tipo }}" {{ request('tipo') == $tipo ? 'selected' : '' }}>
                        <!-- primera letra en mayuscula -->
                        {{ ucfirst($tipo) }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="ml-2 px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                Aplicar
            </button>
        </form>

        <!-- recorremos las notificaciones del usuario -->
        @forelse ($notifications as $notification)
            @php
            // asignamos azul si no se ha leído aun y blanco si se ha leído (aunque siempre se leen por donde tenemos puesto el link)
                $isUnread = !$notification->read;
                $bgColor = $isUnread ? 'bg-blue-100' : 'bg-white';
            @endphp
                <!-- contenedor individual para cada notificacion (color segun leida o no) -->
            <div class="flex items-start p-4 mb-4 rounded shadow-sm {{ $bgColor }}">
                <div class="mr-4">
                    @switch($notification->tipo)
                        @case('ayuda')
                            <!-- icono de pregunta -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6 text-blue-700">
                                <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm11.378-3.917c-.89-.777-2.366-.777-3.255 0a.75.75 0 0 1-.988-1.129c1.454-1.272 3.776-1.272 5.23 0 1.513 1.324 1.513 3.518 0 4.842a3.75 3.75 0 0 1-.837.552c-.676.328-1.028.774-1.028 1.152v.75a.75.75 0 0 1-1.5 0v-.75c0-1.279 1.06-2.107 1.875-2.502.182-.088.351-.199.503-.331.83-.727.83-1.857 0-2.584ZM12 18a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
                            </svg>
                            @break

                        @case('sistema')
                            <!-- icono de actualizacion -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6 text-blue-700">
                                <path fill-rule="evenodd" d="M4.755 10.059a7.5 7.5 0 0 1 12.548-3.364l1.903 1.903h-3.183a.75.75 0 1 0 0 1.5h4.992a.75.75 0 0 0 .75-.75V4.356a.75.75 0 0 0-1.5 0v3.18l-1.9-1.9A9 9 0 0 0 3.306 9.67a.75.75 0 1 0 1.45.388Zm15.408 3.352a.75.75 0 0 0-.919.53 7.5 7.5 0 0 1-12.548 3.364l-1.902-1.903h3.183a.75.75 0 0 0 0-1.5H2.984a.75.75 0 0 0-.75.75v4.992a.75.75 0 0 0 1.5 0v-3.18l1.9 1.9a9 9 0 0 0 15.059-4.035.75.75 0 0 0-.53-.918Z" clip-rule="evenodd" />
                            </svg>
                            @break

                        @case('turno')
                            <!-- icono de maletin -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6 text-blue-700">
                                <path fill-rule="evenodd" d="M7.5 5.25a3 3 0 0 1 3-3h3a3 3 0 0 1 3 3v.205c.933.085 1.857.197 2.774.334 1.454.218 2.476 1.483 2.476 2.917v3.033c0 1.211-.734 2.352-1.936 2.752A24.726 24.726 0 0 1 12 15.75c-2.73 0-5.357-.442-7.814-1.259-1.202-.4-1.936-1.541-1.936-2.752V8.706c0-1.434 1.022-2.7 2.476-2.917A48.814 48.814 0 0 1 7.5 5.455V5.25Zm7.5 0v.09a49.488 49.488 0 0 0-6 0v-.09a1.5 1.5 0 0 1 1.5-1.5h3a1.5 1.5 0 0 1 1.5 1.5Zm-3 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
                                <path d="M3 18.4v-2.796a4.3 4.3 0 0 0 .713.31A26.226 26.226 0 0 0 12 17.25c2.892 0 5.68-.468 8.287-1.335.252-.084.49-.189.713-.311V18.4c0 1.452-1.047 2.728-2.523 2.923-2.12.282-4.282.427-6.477.427a49.19 49.19 0 0 1-6.477-.427C4.047 21.128 3 19.852 3 18.4Z" />
                            </svg>
                            @break

                        @default
                            <!-- icono de informacion (generico) -->
                            <svg class="w-6 h-6 text-blue-700" fill="none" stroke="currentColor" stroke-width="2"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z"/>
                            </svg>
                    @endswitch
                </div>

                <div class="flex-1">
                        <p class="text-gray-800">{{ $notification->message }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                </div>
            </div>
        @empty
            <p class="text-center text-gray-500 mt-10">No hay nuevas notificaciones.</p>
        @endforelse
    </div>
</x-layout>
