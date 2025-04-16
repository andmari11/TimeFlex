<x-layout title="Centro de Notificaciones">
    <x-page-heading>Bienvenido a tu centro de notificaciones</x-page-heading>
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
                    <svg class="w-6 h-6 text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <!-- icono de info (aunque realmente se ve toda por pantalla, podriamos quitarlo pero queda bien) -->
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z"/>
                    </svg>
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
