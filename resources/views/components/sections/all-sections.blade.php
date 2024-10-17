<section class="w-full max-w-md bg-white px-8 rounded-lg shadow-md mt-10 ml-4 overflow-y-auto" style="max-height: 500px;">
    <nav class="flex justify-between items-center py-5 border-b border-blue/10">
        <div class="inline-flex items-center gap-x-2">
            <span class="w-2 h-2 bg-black inline-block"></span>
            <h3 class="text-bold text-xl">Secciones</h3>
        </div>
        <div>
            <a href="register-section" class="bg-white text-blue-900 font-bold py-2 px-4 my-12 rounded-full border-2 border-blue-900 hover:bg-blue-900 hover:text-white transition"> + </a>
        </div>
    </nav>
    <section class="p-4 rounded-xl flex flex-col text-center">
        <a class="p-4 bg-gray-900 shadow rounded-xl my-1"  href="/menu">
            <h3 class="text-white">
                Ver todos
            </h3>
        </a>
        @foreach (auth()->user()->company->sections as $section)
            <a class="p-4 bg-gray-600 shadow rounded-xl my-1" href="/menu/{{ $section->id }}">
                <h3 class="text-white">
                    {{ $section->name }}
                </h3>
            </a>
        @endforeach
    </section>
</section>
