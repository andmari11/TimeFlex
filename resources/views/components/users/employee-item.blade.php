<div>
    <img class="h-8 w-8 rounded-full" src="https://static.vecteezy.com/system/resources/previews/004/274/186/non_2x/person-icon-user-interface-icon-silhouette-of-man-simple-symbol-a-glyph-symbol-in-your-web-site-design-logo-app-ui-webinar-video-chat-ect-vector.jpg" alt="">

</div>
<div class="p-2 text-white text-bold text-l">
    <h3>{{$employee->name}}</h3>
</div>
<div class=" flex justify-between">
    <div>
        <a href="#" class=" bg-white/10 hover:bg-white/35 px-2 py-1 rounded-xl text-xs text-white">{{$employee->role}}</a>
    </div>
    <div>
        @if(auth()->user()->role === 'admin')
            <a href="/shifts/{{$employee->id}}/edit" class="bg-blue-500 hover:bg-white/35 px-2 py-1 rounded-xl text-xs text-white">Editar</a>
            <a href="#" class=" bg-red-600 hover:bg-white/35 px-2 py-1 rounded-xl text-xs text-white">Eliminar</a>
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


