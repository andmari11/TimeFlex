<x-layout :title="'Resultados'">
        <x-page-heading>Resultados</x-page-heading>
        <div class="bg-white p-8 rounded-lg shadow-md mx-10 my-10">
            <div class="flex flex-wrap gap-10">
                @foreach($employees as $employee)
                    <x-users.employee-section :employee="$employee"></x-users.employee-section>
                @endforeach
            </div>

        </div>
</x-layout>
