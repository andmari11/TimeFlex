<section  x-data="{ open_sections: false }" class="w-full max-w-md bg-white px-8 rounded-lg shadow-md mt-10 ml-4 " >
    <nav @click="open_sections = !open_sections" class="flex justify-between items-center py-5 border-b border-blue/10  hover:cursor-pointer">
        <div class="inline-flex items-center gap-x-2">
            <span class="w-2 h-2 bg-black inline-block"></span>
            <h3 class="text-bold text-xl hover:underline">Secciones</h3>
        </div>
        <div>
            <a href="register-section" class="bg-white text-blue-900 font-bold py-2 px-4 my-12 rounded-full border-2 border-blue-900 hover:bg-blue-900 hover:text-white transition"> + </a>
        </div>
    </nav>
    <section x-show="open_sections" class="p-4 rounded-xl flex flex-col text-center overflow-y-auto" style="max-height: 500px;" >

            <div class="p-4 bg-gray-600 shadow rounded-xl my-1">
                <div class="p-4 text-white text-bold text-l">
                    <a href="/menu" class="relative inline-block px-4 py-2 rounded-full transition-all duration-300 hover:bg-white hover:text-black hover:scale-110 ">Ver Todos</a>
                </div>
                <div class="flex justify-between">
                    <a class=" bg-white/10 hover:bg-white/35 px-2 py-1 rounded-xl text-xs text-white">{{auth()->user()->company->employees->count()}} empleados totales</a>
                    <a class="bg-cyan-500 hover:bg-white/35 px-2 py-1 rounded-xl text-xs text-white">Todos los empleados</a>
                </div>
            </div>


            @foreach (auth()->user()->company->sections as $section)
                <div class="p-4 bg-gray-600 shadow rounded-xl my-1">
                    <div class="flex justify-end gap-1">
                        <a class="bg-blue-500 hover:bg-white/35 px-2 py-1 rounded-xl text-xs text-white">Editar</a>
                        <button class=" bg-red-600 hover:bg-white/35 px-2 py-1 rounded-xl text-xs text-white">Eliminar</button>
                    </div>
                    <div class="p-4 text-white text-bold text-l">
                        <a href="/menu/{{ $section->id }}" class="relative inline-block px-4 py-2 rounded-full transition-all duration-300 hover:bg-white hover:text-black hover:scale-110">{{ $section->name }}</a>
                    </div>
                    <div class="flex justify-between">
                        <a class=" bg-white/10 hover:bg-white/35 px-2 py-1 rounded-xl text-xs text-white">{{$section->users->count()}} empleados</a>
                        <a href="/equipo/{{ $section->id }}" class="bg-cyan-500 hover:bg-white/35 px-2 py-1 rounded-xl text-xs text-white">Ver equipo</a>
                    </div>
                </div>


            @endforeach

    </section>
</section>
