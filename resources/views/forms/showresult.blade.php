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

                            @if($answer->id_question_type == 9 && $answer->file)
                                <!-- Mostrar archivo si es tipo "Carga de Archivo" -->
                                <p class="text-gray-800 mt-1">
                                    <a href="{{ route('file.download', $answer->file->id) }}" class="text-blue-500 hover:underline">
                                        Descargar archivo: {{ $answer->file->name }}
                                    </a>
                                </p>

                                @if(Str::startsWith($answer->file->mime, 'image/'))
                                    <!-- Previsualización de imagen -->
                                    <img src="{{ route('file.show', $answer->file->id) }}" alt="{{ $answer->file->name }}" class="mt-4 max-w-full h-auto rounded-lg shadow-md">
                                @endif
                            @elseif($answer->id_question_type == 4)
                                @if($answer->shiftTypes->period === 0)
                                    ({{ \Carbon\Carbon::parse($answer->shiftTypesstart)->format('d/m/Y H:i') }} - {{ \Carbon\Carbon::parse($answer->shiftTypes->end)->format('d/m/Y H:i') }}) -
                                @else
                                    ({{ \Carbon\Carbon::parse($answer->shiftTypes->start)->format('H:i') }} - {{ \Carbon\Carbon::parse($answer->shiftTypes->end)->format('H:i') }}) -
                                @endif
                                @switch($answer->shiftTypes->period)
                                    @case(0)
                                        Una sola vez
                                        @break
                                    @case(1)
                                        Diaria
                                        @break
                                    @case(2)
                                        Semanal
                                        @break
                                    @case(3)
                                        Mensual
                                        @break
                                    @case(4)
                                        Anual
                                        @break
                                    @default
                                        Periodo no definido
                                @endswitch
                            @else
                                <!-- Mostrar texto normal -->
                                <p class="text-gray-800 mt-1">{{ $answer->respuesta }}</p>
                            @endif
                        </li>
                    @endforeach
                </ul>

                <div class="mt-6 flex justify-between items-center">
                    <a href="{{ route('forms.index') }}"
                       class="btn bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded shadow-md">
                        Volver
                    </a>
                    @php
                        $currentDate = now(); // Fecha actual
                        $endDate = \Carbon\Carbon::parse($formulario->end_date); // Fecha de finalización del formulario
                    @endphp

                    @if($currentDate->lessThanOrEqualTo($endDate))
                        <a href="{{ route('forms.editresults', $formulario->id) }}"
                           class="btn bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded shadow-md">
                            Editar Respuestas
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>
</x-layout>
