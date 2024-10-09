<x-layout :title="'Mi área'">
    <x-page-heading>Bienvenido a tu área personal</x-page-heading>
    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md mt-10">
        @auth()
            @if(auth()->user()->role === 'admin')
                <p>Compañeros:</p>
                <a href="register" class="bg-white text-blue-900 font-bold py-2 px-4 my-12 rounded-full border-2 border-blue-900 hover:bg-blue-900 hover:text-white transition relative top-6"> + </a>
            @endif
            @foreach(auth()->user()->company->employees as $employee)
                <x-users.employee-item :employee="$employee"></x-users.employee-item>
                @if(auth()->user()->role === 'admin')
                <button>
                    <a href="/shifts/{{$employee->id}}/edit">
                        <img class="h-8 w-8" src="{{ asset('editar.png') }}" alt="editar">
                    </a>
                </button>
                <button>
                    <img class="h-8 w-8" src="{{ asset('elimina.png') }}" alt="eliminar">
                </button>
                @endif
            @endforeach
        @endauth
    </div>
</x-layout>
