<section  x-data="{ open_sections: true }" class="w-full bg-white px-5 rounded-lg shadow-md mt-10 ml-4 " >
    <nav @click="open_sections = !open_sections" class="flex justify-between items-center py-5 border-b border-blue/10  hover:cursor-pointer">
        <div class="inline-flex items-center gap-x-2">
            <span class="w-2 h-2 bg-black inline-block"></span>
            <h3 class="font-bold text-xl hover:underline">Secciones</h3>
        </div>
        <div>
            <a href="/register-section" @click.stop
               class="flex items-center justify-center w-8 h-8 bg-white text-blue-900 rounded-full border-2 border-blue-900 hover:bg-blue-900 hover:text-white transition">
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v8m-4-4h8" />
                </svg>
            </a>

        </div>
    </nav>
    <section x-show="open_sections" class="p-4 rounded-xl flex flex-col text-center overflow-y-auto" style="max-height: 500px;" >

            <div class="p-4 bg-blue-50 shadow rounded-xl my-1">
                <div class="p-4 text-black text-bold text-l">
                    <a href="/menu" class="relative inline-block px-4 py-2 rounded-full transition-all duration-300 hover:scale-110 ">Ver Todos</a>
                </div>
                <div class="flex justify-end">
                    <!--<a class=" bg-white/10 hover:bg-white/35 px-2 py-1 rounded-xl text-xs text-white">{{auth()->user()->company->employees->count()}} empleados totales</a>-->
                    <a href ="/equipo" class="bg-cyan-500 hover:bg-cyan-400 px-2 py-1 rounded-xl text-xs text-white">Todos los empleados ({{auth()->user()->company->employees->count()}})</a>
                </div>
            </div>


            @foreach (auth()->user()->company->sections->reverse() as $section)
                <div class="p-4 bg-blue-50 shadow rounded-xl my-1">
                    <div class="flex justify-end gap-1">
                        @if(auth()->user()->role === 'admin')
                            <a href="/sections/{{$section->id}}/edit" class="bg-blue-500 hover:bg-blue-400 px-2 py-1 rounded-xl text-xs text-white">Editar</a>
                            @if(!$section->default)
                                <button onclick="confirmDeleteSection(event, {{$section->id}})" class="bg-red-600 hover:bg-red-500 px-2 py-1 rounded-xl text-xs text-white">Eliminar</button>
                                <form method="POST" action="/sections/{{$section->id}}/delete" id="delete-form-{{$section->id}}" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @endif
                        @endif
                    </div>
                    <div class="p-4 text-black text-bold text-l">
                        <a href="/menu/{{ $section->id }}" class="relative inline-block px-4 py-2 rounded-full transition-all duration-300 hover:text-black hover:scale-110">{{ $section->name }}</a>
                    </div>
                    <div class="flex justify-end">
                        <!--<a class="  text-xs text-black">{{$section->users->count()}} empleados</a>-->
                        <a href="/equipo/{{ $section->id }}" class="bg-sky-500 hover:bg-sky-400 px-2 py-1 rounded-xl text-xs text-white">Ver equipo ({{$section->users->count()}}) </a>
                    </div>
                </div>


            @endforeach

    </section>
</section>

<script>
    function confirmDelete(event, sectionID) {
        event.preventDefault(); // Evita que se envíe el formulario inmediatamente
        const confirmation = confirm("¿Estás seguro de que deseas eliminar esta sección?");
        if (confirmation) {
            document.getElementById('delete-form-' + sectionID).submit(); // Envía el formulario si el usuario confirma
        }
    }
</script>
