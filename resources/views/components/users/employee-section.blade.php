@vite(['resources/js/app.js'])

<div class="p-4  bg-blue-50 shadow rounded-xl my-1 w-full max-w-sm mx-auto">
    <div class="flex items-center justify-center">
        <img class="h-20 w-20 rounded-full" src="https://static.vecteezy.com/system/resources/previews/004/274/186/non_2x/person-icon-user-interface-icon-silhouette-of-man-simple-symbol-a-glyph-symbol-in-your-web-site-design-logo-app-ui-webinar-video-chat-ect-vector.jpg" alt="">
    </div>
    <div class="flex flex-col items-center">
        <h3 class="text-xl text-gray-700 my-2 font-bold">{{$employee->name}}</h3>
        <h3 class="text-lg text-gray-700 my-2 bg-white/10 hover:bg-white/35 px-3 py-1 rounded-xl">{{$employee->section->name}}</h3>
        <a class="text-lg text-gray-700 my-2 hover:underline">{{$employee->email}}</a>
        <a class="bg-sky-700 hover:bg-sky-900 px-4 py-3 rounded-xl text-xl text-white font-bold my-3">Ver horario</a>
    </div>
    @if(auth()->user()->role === 'admin')
        <a href="/sections/{{$employee->section->id}}/edit" class="bg-blue-500 hover:bg-blue-400 px-2 py-1 rounded-xl text-xs text-white">Editar</a>
        <button onclick="confirmDeleteSection(event, {{$employee->section->id}})" class="bg-red-600 hover:bg-red-400 px-2 py-1 rounded-xl text-xs text-white">Eliminar</button>
        <form method="POST" action="/sections/{{$employee->section->id}}/delete" id="delete-form-{{$employee->section->id}}" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    @endif
</div>
<div id="statsuser" style="width: 100%; height: 400px;"></div>
<div id="statsuser2" style="width: 100%; height: 400px;"></div>
<script>
    function confirmDeleteSection(event, sectionID) {
        event.preventDefault(); // Evita que se envíe el formulario inmediatamente
        const confirmation = confirm("¿Estás seguro de que deseas eliminar esta sección?");
        if (confirmation) {
            document.getElementById('delete-form-' + sectionID).submit(); // Envía el formulario si el usuario confirma
        }
    }
</script>
