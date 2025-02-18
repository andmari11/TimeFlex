<x-layout :title="'Formularios disponibles'">
    <x-page-heading>Formularios disponibles</x-page-heading>

    <a class="btn bg-red-500 p-2 rounded-lg text-white mb-4 inline-block" href="/formularios/create">Crear nuevo formulario</a>

    @if($formularios->isEmpty())
        <p>No hay formularios disponibles en este momento.</p>
    @else
        <ul class="list-disc list-inside">
            @foreach($formularios as $formulario)
                <li class="mb-6">
                    <h2 class="text-xl font-semibold">{{ $formulario->title }}</h2>
                    <p>{{ $formulario->summary }}</p>
                    <p>Disponible desde: {{ $formulario->start_date }}</p>
                    <p>Hasta: {{ $formulario->end_date }}</p>

                    <form action="{{ route('forms.destroy', $formulario->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este formulario?');" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn bg-red-500 text-white p-2 rounded-lg mt-2">Eliminar</button>
                    </form>
                </li>
            @endforeach
        </ul>
    @endif
</x-layout>
