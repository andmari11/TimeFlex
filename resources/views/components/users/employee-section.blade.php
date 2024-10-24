<div class="p-4 bg-gray-600 shadow rounded-xl my-1 w-full max-w-sm mx-auto">
    <div class="flex items-center justify-center">
        <img class="h-20 w-20 rounded-full" src="https://static.vecteezy.com/system/resources/previews/004/274/186/non_2x/person-icon-user-interface-icon-silhouette-of-man-simple-symbol-a-glyph-symbol-in-your-web-site-design-logo-app-ui-webinar-video-chat-ect-vector.jpg" alt="">
    </div>
    <div class="flex flex-col items-center">
        <h3 class="text-xl text-white my-2 font-bold">{{$employee->name}}</h3>
        <h3 class="text-lg text-white my-2 bg-white/10 hover:bg-white/35 px-3 py-1 rounded-xl">{{$employee->section->name}}</h3>
        <a class="text-lg text-white my-2 hover:underline">{{$employee->email}}</a>
        <a class="bg-cyan-700 hover:bg-white/35 px-4 py-3 rounded-xl text-xl text-white font-bold my-3">Ver horario</a>
    </div>
    @if(auth()->user()->role === 'admin')
        <a href="/users/{{$employee->id}}/edit" class="bg-blue-500 hover:bg-white/35 px-2 py-1 rounded-xl text-xs text-white">Editar</a>
        <button onclick="confirmDelete(event, {{$employee->id}})" class="bg-red-600 hover:bg-white/35 px-2 py-1 rounded-xl text-xs text-white">Eliminar</button>
        <form method="POST" action="/users/{{$employee->id}}/delete" id="delete-form-{{$employee->id}}" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    @endif

</div>
<script>
    function confirmDelete(event, employeeId) {
        event.preventDefault(); // Evita que se envíe el formulario inmediatamente
        const confirmation = confirm("¿Estás seguro de que deseas eliminar este empleado?");
        if (confirmation) {
            document.getElementById('delete-form-' + employeeId).submit(); // Envía el formulario si el usuario confirma
        }
    }
</script>