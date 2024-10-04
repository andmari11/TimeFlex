<!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
@props(['ref', 'clase' => ""])

@php
    if(request()->is($ref)){
        $clase = "mx-4 rounded-md bg-gray-100 text-gray-900 px-3 py-2 text-sm font-medium"; // Estilo cuando está activo
    } else {
        $clase = "mx-4 rounded-md bg-gray-100 text-gray-900 hover:bg-white hover:text-black px-3 py-2 text-sm font-medium"; // Estilo por defecto
    }
@endphp

<a href="{{ $ref }}" class="{{ $clase }}" aria-current="page">{{ $slot }}</a>

