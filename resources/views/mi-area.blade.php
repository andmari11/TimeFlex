<x-layout :title="'Mi área'">
    <x-page-heading>Bienvenido a tu área personal</x-page-heading>
    @auth()
        @if(auth()->user()->role === 'admin')
            <a href="register" class="bg-blue-900 text-white font-bold py-2 px-6 mx-8 my-12 rounded-md hover:bg-blue-700 transition relative top-6"> Nuevo empleado para la compañia </a>
        @endif
    @endauth
</x-layout>
