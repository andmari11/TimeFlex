<x-layout :title="'Formularios disponibles'">
    <div class="container mx-auto py-10 px-6">
        <div class="text-center mb-10">
            <h1 class="text-4xl font-bold text-gray-800">Formularios Disponibles</h1>
            <p class="text-gray-600 mt-2">Aquí encontrarás todos los formularios organizados por sección.</p>
        </div>

        @if(auth()->user()->role === 'admin')
            <div class="text-right mb-6">
                <a href="/formularios/create" class="btn bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded shadow-md">
                    Crear nuevo formulario
                </a>
            </div>
        @endif

        @if($formularios->isEmpty())
            <p class="text-center text-gray-500 text-lg">No hay formularios disponibles en este momento.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($formularios as $formulario)
                    @php
                        $userId = auth()->user()->id;
                        $hasAnswered = \App\Models\Result::where('id_user', $userId)
                            ->where('id_form', $formulario->id)
                            ->exists();
                    @endphp

                    <div class="bg-white border border-gray-200 rounded-lg shadow hover:shadow-lg transition-shadow duration-300">
                        <div class="p-6">
                            <h2 class="text-2xl font-semibold text-gray-800">{{ $formulario->title }}</h2>
                            <p class="text-gray-600 mt-2">{{ $formulario->summary }}</p>

                            <!-- Información de la sección -->
                            <p class="text-sm text-gray-500 mt-4">
                                <strong>Sección:</strong> {{ $formulario->section->name ?? 'Sin sección asignada' }}
                            </p>

                            <p class="text-sm text-gray-500 mt-2">
                                <strong>Disponible desde:</strong> {{ $formulario->start_date }}<br>
                                <strong>Hasta:</strong> {{ $formulario->end_date }}
                            </p>
                            @php
                                $currentDate = now();
                                $startDate = \Carbon\Carbon::parse($formulario->start_date);
                                $endDate = \Carbon\Carbon::parse($formulario->end_date);
                            @endphp

                            <div class="mt-6">
                                @if(!$hasAnswered && $currentDate->between($startDate, $endDate))
                                    <a href="{{ route('forms.show', $formulario->id) }}" class="btn bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded shadow">
                                        Contestar formulario
                                    </a>
                                @elseif($hasAnswered)
                                    <p class="text-green-600 font-semibold">¡Ya has respondido este formulario!</p>
                                @endif
                            </div>

                            @if(auth()->user()->role === 'admin')
                                <div class="mt-4 flex justify-between items-center">
                                    <a href="{{ route('forms.edit', $formulario->id) }}" class="text-blue-500 hover:text-blue-700 font-semibold">Editar</a>
                                    <form action="{{ route('forms.destroy', $formulario->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este formulario?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 font-semibold">Eliminar</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layout>
