<div  x-data="{ open_drawer: false }">
    <div class="flex justify-between" >
        <div>
            <img class="h-8 w-8 rounded-full" src="https://static.vecteezy.com/system/resources/previews/004/274/186/non_2x/person-icon-user-interface-icon-silhouette-of-man-simple-symbol-a-glyph-symbol-in-your-web-site-design-logo-app-ui-webinar-video-chat-ect-vector.jpg" alt="">
        </div>
        <div>
            @if(auth()->user()->role === 'admin')
                <a href="/users/{{$employee->id}}/edit" class="bg-blue-500 hover:bg-white/35 px-2 py-1 rounded-xl text-xs text-white">Editar</a>
                <button onclick="confirmDelete(event, {{$employee->id}})" class="bg-red-600 hover:bg-white/35 px-2 py-1 rounded-xl text-xs text-white">Eliminar</button>
                <form method="POST" action="/users/{{$employee->id}}/delete" id="delete-form-{{$employee->id}}" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            @endif
        </div>
    </div>

    <button class="p-2 text-white text-bold text-l " >
        <h3 @click="open_drawer = true"  class="hover:underline">{{$employee->name}}</h3>
    </button>

    <div class="flex justify-start">
        <a href="#" class="bg-white/10 hover:bg-white/35 px-2 py-1 rounded-xl text-xs text-white">{{$employee->section?->name}}</a>
    </div>


    <x-drawer :title="$employee->name">
        <x-users.employee-section :employee="$employee"></x-users.employee-section>
    </x-drawer>
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
