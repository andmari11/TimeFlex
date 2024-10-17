<div>
    <img class="h-8 w-8 rounded-full" src="https://static.vecteezy.com/system/resources/previews/004/274/186/non_2x/person-icon-user-interface-icon-silhouette-of-man-simple-symbol-a-glyph-symbol-in-your-web-site-design-logo-app-ui-webinar-video-chat-ect-vector.jpg" alt="">

</div>
<div class="p-2 text-white text-bold text-l">
    <h3>{{$employee->name}}</h3>
</div>
<div class=" flex justify-between">
    <div>
        <a href="#" class=" bg-white/10 hover:bg-white/35 px-2 py-1 rounded-xl text-xs text-white">{{$employee->role}}</a>
        @if(auth()->user()->role === 'employee')
        <span class="bg-white/10 hover:bg-white/35 px-2 py-1 rounded-xl text-xs text-white">{{$employee->section->name}}</span> <!-- Falta colocar mejor la etiqueta de seccion dentro de la caja -->
        @endif
        @if(auth()->user()->role === 'admin')
            <a href="/users/{{$employee->id}}/edit" class="bg-blue-500 hover:bg-white/35 px-2 py-1 rounded-xl text-xs text-white">Editar</a>
            <button form="delete-form-{{$employee->id}}"  class=" bg-red-600 hover:bg-white/35 px-2 py-1 rounded-xl text-xs text-white">Eliminar</button>
            <form method="POST" action="/users/{{$employee->id}}/delete" id="delete-form-{{$employee->id}}" class="hidden">
                @csrf
                @method('DELETE')
            </form>
            {{--<button>
                <a href="/shifts/{{$employee->id}}/edit">
                    <img class="h-8 w-8" src="{{ asset('editar.png') }}" alt="editar">
                </a>
            </button>
            <button>
                <img class="h-8 w-8" src="{{ asset('elimina.png') }}" alt="eliminar">
            </button>--}}
        @endif
    </div>

</div>


