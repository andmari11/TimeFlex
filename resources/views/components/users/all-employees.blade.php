


<section x-data="{ open_all_employees: true }" class="w-full max-w-md bg-white px-8 rounded-lg shadow-md mt-10 mx-4">
    <nav @click="open_all_employees = !open_all_employees" class="flex justify-between items-center py-5 border-b border-blue/10 hover:cursor-pointer">
        <div class="inline-flex items-center gap-x-2">
            <span class="w-2 h-2 bg-black inline-block"></span>
            @if(auth()->user()->role === 'employee')
                <h3 class="text-bold text-xl hover:underline">Compañeros</h3>
            @endif
            @if(auth()->user()->role === 'admin')
                @if($section)
                    <h3 class="text-bold text-xl hover:underline">{{$section->name}}</h3>
                @else
                    <h3 class="text-bold text-xl hover:underline">Todos los empleados</h3>
                @endif
            @endif
        </div>
        <div>
            @if(auth()->user()->role === 'admin')
                <a href="/register-user" @click.stop class="bg-white text-blue-900 font-bold py-2 px-4 my-12 rounded-full border-2 border-blue-900 hover:bg-blue-900 hover:text-white transition"> + </a>
            @endif
        </div>
    </nav>
    <section x-show="open_all_employees" class="p-4 rounded-xl flex flex-col text-center overflow-y-auto" style="max-height: 500px;"  >
        @if(!$section && auth()->user()->role === 'admin')
            @foreach(auth()->user()->company->employees as $employee)

                <div class="p-4 bg-gray-600 shadow rounded-xl my-1 ">
                    <x-users.employee-item :employee="$employee"></x-users.employee-item>
                </div>
            @endforeach
        @else

            @foreach($section->users as $employee)
                <div class="p-4 bg-gray-600 shadow rounded-xl my-1">
                    <x-users.employee-item :employee="$employee"></x-users.employee-item>
                </div>
            @endforeach
        @endif

    </section>
</section>
