@php

    if (auth()->user()->role === 'employee') {
        // Si es un empleado, obtenemos su sección
        $section = auth()->user()->section;
    }
@endphp


<x-layout :title="'Mi área'">
    @if(auth()->user()->role === 'admin')
        <x-sections.all-sections></x-sections.all-sections>
    @endif


        <x-users.all-employees :section="$section"></x-users.all-employees>


</x-layout>
