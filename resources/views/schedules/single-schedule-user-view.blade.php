<x-layout :title="'Calendario'">
    <x-page-heading> Horario de {{ $schedule->section->name }}</x-page-heading>
    <div class="flex justify-between">

        <x-schedules.single-schedule-calendar class="basis-[50%] flex-grow" :schedule="$schedule" :days="$days"></x-schedules.single-schedule-calendar>
        <div class="basis-[50%] flex-grow mt-8 p-4 mr-10 w-30 shadow rounded-lg bg-white">

        </div>
    </div>
</x-layout>
