<section x-data="{ open_all_employees: true }" class="w-full bg-white px-5 rounded-lg shadow-md mt-8 mx-4">
    <nav @click="open_all_employees = !open_all_employees" class="flex justify-between items-center py-5 border-b border-blue/10 hover:cursor-pointer">
        <div class="inline-flex items-center gap-x-2">
            <span class="w-2 h-2 bg-black inline-block"></span>
            @if(auth()->user()->role === 'employee')
                <h3 class="font-bold text-xl hover:underline">Compañeros</h3>
            @endif
            @if(auth()->user()->role === 'admin')
                @if($section)
                    <h3 class="font-bold text-xl hover:underline">{{$section->name}}</h3>
                @else
                    <h3 class="font-bold text-xl hover:underline">Todos los empleados</h3>
                @endif
            @endif
        </div>

        <div class="flex items-center gap-4">
            @if(auth()->user()->role === 'admin')
                <!-- Botón para añadir usuario -->
                <a href="/register-user" @click.stop
                   class="flex items-center justify-center w-8 h-8 bg-white text-blue-900 rounded-full border-2 border-blue-900 hover:bg-blue-900 hover:text-white transition">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v8m-4-4h8" />
                    </svg>
                </a>

                <!-- Botón de opciones con @click.stop -->
                <div class="relative" x-data="{ openMenu: false }">
                    <button @click.stop="openMenu = !openMenu"
                            class="text-gray-700 hover:bg-gray-200 rounded-full p-2 text-lg">
                        &#x22EE; <!-- Ícono de tres puntos verticales -->
                    </button>

                    <!-- Menú desplegable -->
                    <div x-show="openMenu" @click.away="openMenu = false"
                         class="absolute right-0 z-10 mt-2 w-48 bg-white shadow-lg rounded-md ring-1 ring-black ring-opacity-5">
                        <a href="/register-section" @click.stop
                           class="block w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-100 text-sm transition-all">
                            Añadir sección
                        </a>


                        <button class="block w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-100 text-sm transition-all"
                                onclick="editSection()">
                            Editar sección
                        </button>
                        <button class="block w-full text-left px-4 py-2 text-gray-800 hover:bg-red-100 text-sm transition-all"
                                onclick="deleteSection()">
                            Eliminar sección
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </nav>

    <section x-show="open_all_employees" class="p-4 rounded-xl flex flex-col text-center overflow-y-auto" style="max-height: 500px;">
        @if(!$section && auth()->user()->role === 'admin')
            @foreach(auth()->user()->company->employees as $employee)
                <div class="p-4 bg-blue-50 shadow rounded-xl my-1">
                    <x-users.employee-item :employee="$employee"></x-users.employee-item>
                </div>
            @endforeach
        @else
            @foreach($section->users as $employee)
                <div class="p-4 bg-blue-50 shadow rounded-xl my-1">
                    <x-users.employee-item :employee="$employee"></x-users.employee-item>
                </div>
            @endforeach
        @endif
    </section>
</section>
