<x-layout :title="'Mi área'">
    <x-page-heading>Bienvenido a tu área personal</x-page-heading>
    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md mt-10">
        @auth()
            @if(auth()->user()->role === 'admin')
                <p>Compañeros:</p>
                <a href="register" class="bg-white text-blue-900 font-bold py-2 px-4 my-12 rounded-full border-2 border-blue-900 hover:bg-blue-900 hover:text-white transition relative top-6"> + </a>
            @endif
        @endauth
        @foreach(auth()->user()->company->employees as $employee)
            <x-users.employee-item :employee="$employee"></x-users.employee-item>
        @endforeach
    </div>
</x-layout>
