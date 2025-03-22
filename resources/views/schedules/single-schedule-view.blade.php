<x-layout :title="'Calendario'">
    <x-page-heading> Horario de {{ $schedule->section->name }}</x-page-heading>
    <x-schedules.single-schedule-calendar :schedule="$schedule" :months="$months"></x-schedules.single-schedule-calendar>

</x-layout>
