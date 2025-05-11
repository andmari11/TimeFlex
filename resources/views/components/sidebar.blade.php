
<button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar" aria-controls="default-sidebar" type="button" class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
    <span class="sr-only">Open sidebar</span>
    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
        <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
    </svg>
</button>
<div class="flex ">

    <aside id="default-sidebar" class="min-w-[275px] top-12 left-0 z-40 w-64  transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
        <div class=" px-3 overflow-y-auto bg-gray-800 h-full">
            <div class="flex flex-col h-screen">
                <!-- Sección principal -->
                <ul class="space-y-4 font-medium pt-4">
                    <li>
                        <a href="/menu"
                           class="flex items-center p-2 rounded-lg group
                                  {{ Request::is('menu') ? 'bg-gray-700 text-white' : 'text-white hover:bg-gray-700' }}">
                            <svg class="w-6 h-6 transition duration-75
                                        {{ Request::is('menu') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}"
                                 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7" />
                            </svg>
                            <span class="ms-3">Ver Todos</span>
                        </a>
                    </li>
                    @foreach (auth()->user()->company->sections->reverse() as $section)
                        <li>
                            <a href="/menu/{{ $section->id }}"
                               class="flex items-center p-2 rounded-lg group
                                      {{ Request::is('menu/' . $section->id) ? 'bg-gray-700 text-white' : 'text-white hover:bg-gray-700' }}">
                                <svg class="w-6 h-6 transition duration-75
                                            {{ Request::is('menu/' . $section->id) ? 'text-white' : 'text-gray-400 group-hover:text-white' }}"
                                     xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                                <span class="ms-3">{{ $section->name }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>

                @if(session('historial_accesos'))
                    <ul class="mt-auto mb-20 pt-4 space-y-2 font-medium border-t border-gray-700">
                        <div class="text-white p-2 flex items-center">
                            <svg class="flex-shrink-0 w-5 h-5 transition duration-75 text-gray-400 group-hover:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2" />
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/>
                            </svg>
                            <span class="ms-3"> Últimos Accesos </span>
                        </div>

                        @foreach(session('historial_accesos') as $item)
                            <li>
                                <a href="{{ $item['link'] }}" class="block ps-10 px-3 py-1 transition duration-75 rounded-lg hover:bg-gray-700 text-white">{{ $item['titulo'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

        </div>
    </aside>


    <div class="w-full">
        {{$slot}}
    </div>
</div>
