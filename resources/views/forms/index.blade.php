<x-layout :title="'Formularios disponibles'">
    <div class="container mx-auto py-10 px-6">
        <div class="text-center mb-10">
            <h1 class="text-4xl font-bold text-gray-800">Formularios Disponibles</h1>
            <p class="text-gray-600 mt-2">Explora y gestiona los formularios disponibles según tu rol.</p>
        </div>

        @if(auth()->user()->role === 'admin')
            <div class="text-right mb-6">
                <a href="/formularios/create" class="btn bg-red-500 hover:bg-red-600 text-white font-semibold py-3 px-6 rounded-md shadow-md">
                    + Crear nuevo formulario
                </a>
            </div>
        @endif

        @if($formularios->isEmpty())
            <p class="text-center text-gray-500 text-lg">No hay formularios disponibles en este momento.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($formularios as $formulario)
                    <div class="bg-white border border-gray-200 rounded-lg shadow hover:shadow-lg transition-shadow duration-300">
                        <div class="p-6 flex flex-col justify-between h-full">
                            <h2 class="text-2xl font-semibold text-gray-800">{{ $formulario->title }}</h2>
                            <p class="text-gray-600 mt-3">{{ $formulario->summary }}</p>
                            <p class="text-sm text-gray-500 mt-4">
                                <strong>Desde:</strong> {{ \Carbon\Carbon::parse($formulario->start_date)->format('d/m/Y H:i') }}<br>
                                <strong>Hasta:</strong> {{ \Carbon\Carbon::parse($formulario->end_date)->format('d/m/Y H:i') }}
                            </p>

                            @if(auth()->user()->role === 'admin')
                                <!-- Mostrar secciones asignadas -->
                                <p class="text-sm text-gray-500 mt-4">
                                    <strong>Secciones asignadas:</strong>
                                <ul class="list-disc list-inside text-gray-600 mt-1">
                                    @foreach($formulario->sections as $section)
                                        <li>{{ $section->name }}</li>
                                    @endforeach
                                </ul>
                                </p>
                            @endif

                            <!-- Botones de acción -->
                            <div class="mt-6">
                                @php
                                    $currentDate = now();
                                    $startDate = \Carbon\Carbon::parse($formulario->start_date);
                                    $endDate = \Carbon\Carbon::parse($formulario->end_date);
                                    $userId = auth()->user()->id;
                                    $hasAnswered = \App\Models\Result::where('id_user', $userId)->where('id_form', $formulario->id)->exists();
                                @endphp

                                @if(!$hasAnswered && $currentDate->between($startDate, $endDate))
                                    <a href="{{ route('forms.show', $formulario->id) }}" class="btn bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded shadow-md">
                                        Contestar
                                    </a>
                                @elseif($hasAnswered)
                                    <p class="text-green-600 font-semibold">¡Ya has respondido este formulario!</p>
                                @else
                                    <p class="text-red-500 font-semibold">No disponible actualmente</p>
                                @endif
                            </div>

                            <!-- Opciones de administración -->
                            @if(auth()->user()->role === 'admin')
                                <div class="mt-4 flex justify-between items-center border-t border-gray-300 pt-4">
                                    <a href="{{ route('forms.edit', $formulario->id) }}" class="btn text-blue-500 hover:text-blue-700 font-semibold text-sm">
                                        Editar
                                    </a>
                                    <form action="{{ route('forms.destroy', $formulario->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este formulario?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn text-red-500 hover:text-red-700 font-semibold text-sm">
                                            Eliminar
                                        </button>
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
