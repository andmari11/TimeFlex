@php
        $section = auth()->user()->section;
@endphp
<x-layout :title="'Mi equipo'">
    <x-page-heading>Sección de {{$section->name}}</x-page-heading>
    <div class="flex flex-wrap gap-10 bg-white p-8 rounded-lg shadow-md mx-10 my-10">
        @foreach($section->users as $employee)

                <x-users.employee-section :employee="$employee"></x-users.employee-section>

        @endforeach
    </div>
</x-layout>
