<x-layout :title="'Mis Respuestas al Formulario'">
    <div class="container mx-auto py-10 px-6">
        <div class="text-center mb-10">
            <h1 class="text-4xl font-bold text-gray-800">{{ $formulario->title }}</h1>
            <p class="text-gray-600 mt-2">{{ $formulario->summary }}</p>
            <p class="text-sm text-gray-500 mt-4">
                <strong>Fecha de Inicio:</strong> {{ \Carbon\Carbon::parse($formulario->start_date)->format('d/m/Y H:i') }}<br>
                <strong>Fecha de Finalización:</strong> {{ \Carbon\Carbon::parse($formulario->end_date)->format('d/m/Y H:i') }}
            </p>
        </div>

        @if($answers->isEmpty())
            <p class="text-center text-gray-500 text-lg">No has respondido este formulario.</p>
        @else
            <div class="bg-white border border-gray-200 rounded-lg shadow-lg p-6 max-w-4xl mx-auto">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Respuestas</h2>

                <ul class="divide-y divide-gray-200">
                    @foreach($answers as $answer)
                        <li class="py-4">
                            <p class="font-medium text-gray-700">{{ $answer->question->title }}</p>
                            <p class="text-gray-800 mt-1">{{ $answer->respuesta }}</p>
                        </li>
                    @endforeach
                </ul>

                <!-- Botón Editar Respuestas -->
                @php
                    $currentDate = now(); // Fecha actual
                    $endDate = \Carbon\Carbon::parse($formulario->end_date); // Fecha de finalización del formulario
                @endphp

                @if($currentDate->lessThanOrEqualTo($endDate))
                    <div class="mt-6 text-right">
                        <a href="{{ route('forms.editresults', $formulario->id) }}"
                           class="btn bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded shadow-md">
                            Editar respuestas
                        </a>
                    </div>
                @endif
            </div>
        @endif
    </div>
</x-layout>
