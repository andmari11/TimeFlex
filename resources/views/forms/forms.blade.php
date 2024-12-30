<!DOCTYPE html>
<html>
<head>
    <title>Formularios Disponibles</title>
</head>
<body>
<h1>Formularios Disponibles</h1>

@if($formularios->isEmpty())
    <p>No hay formularios disponibles en este momento.</p>
@else
    <ul>
        @foreach($formularios as $formulario)
            <li>
                <h2>{{ $formulario->title }}</h2>
                <p>{{ $formulario->summary }}</p>
                <p>Disponible desde: {{ $formulario->start_date }}</p>
                <p>Hasta: {{ $formulario->end_date }}</p>
            </li>
        @endforeach
    </ul>
@endif
</body>
</html>
