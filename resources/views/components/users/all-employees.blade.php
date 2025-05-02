<section x-data="{ open_all_employees: true, openModal: false, openMenu: false }"
         class="w-full bg-white px-5 rounded-lg shadow-md mt-8 mx-4">
    <nav @click="open_all_employees = !open_all_employees"
         class="flex justify-between items-center py-5 border-b border-blue/10 hover:cursor-pointer">
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
                <a href="/register-user" @click.stop
                   class="flex items-center justify-center w-8 h-8 bg-white text-blue-900 rounded-full border-2 border-blue-900 hover:bg-blue-900 hover:text-white transition">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v8m-4-4h8"/>
                    </svg>
                </a>

                <div class="relative">
                    <button @click.stop="openMenu = !openMenu"
                            class="text-gray-700 hover:bg-gray-200 rounded-full p-2 text-lg">
                        &#x22EE;
                    </button>

                    <div x-show="openMenu" @click.away="openMenu = false"
                         class="absolute right-0 z-10 mt-2 w-48 bg-white shadow-lg rounded-md ring-1 ring-black ring-opacity-5">
                        <a href="/register-section" @click.stop
                           class="block w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-100 text-sm transition-all">
                            Añadir sección
                        </a>

                        <button @click.stop="openModal = true"
                                class="block w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-100 text-sm transition-all">
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

    <!-- MODAL DE EDICIÓN -->
    @if(auth()->user()->role === 'admin')
        <div x-show="openModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                <h2 class="text-lg font-bold mb-4">Editar sección</h2>

                <div class="space-y-3">
                    @foreach($sections as $section)
                        @if($section->id !== 0 && $section->id !== 1)
                            <div class="flex justify-between items-center border p-2 rounded-lg">
                                <span class="font-medium">{{ $section->name }}</span>
                                <a href="/sections/{{ $section->id }}/edit"
                                    class="text-blue-600 underline hover:text-blue-800 transition-all">
                                    Editar
                                </a>
                            </div>
                       @endif
                  @endforeach
                </div>

                <button @click="openModal = false"
                        class="mt-4 w-full px-4 py-2 bg-red-400 text-white rounded-lg hover:bg-red-500 transition-all">
                    Cerrar
                </button>
            </div>
        </div>
    @endif

    <section x-show="open_all_employees" class="p-4 rounded-xl flex flex-col text-center overflow-y-auto" style="max-height: 500px;">
        @if(auth()->user()->role === 'admin')
            @foreach($sections as $sec)
                @foreach($sec->users as $employee)
                    <div class="p-4 bg-blue-50 shadow rounded-xl my-1">
                        <x-users.employee-item :employee="$employee"></x-users.employee-item>
                    </div>
                @endforeach
            @endforeach
        @elseif(auth()->user()->role === 'employee')
            @foreach($section->users as $employee)
                <div class="p-4 bg-blue-50 shadow rounded-xl my-1">
                    <x-users.employee-item :employee="$employee"></x-users.employee-item>
                </div>
            @endforeach
        @endif
    </section>
</section>
