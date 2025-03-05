<x-layout :title="'Calendario'">
    <x-page-heading> Horario de {{ $schedule->section->name }}</x-page-heading>
    <x-schedules.single-schedule-calendar :schedule="$schedule" :days="$days"></x-schedules.single-schedule-calendar>

</x-layout>
