<x-layout :title="'Home'">
    <div class="relative isolate overflow-hidden bg-white px-6 py-24 sm:py-32 lg:overflow-visible lg:px-0">
        <div class="absolute inset-0 -z-10 overflow-hidden">
            <svg class="absolute left-[max(50%,25rem)] top-0 h-[64rem] w-[128rem] -translate-x-1/2 stroke-gray-200 [mask-image:radial-gradient(64rem_64rem_at_top,white,transparent)]" aria-hidden="true">
                <defs>
                    <pattern id="e813992c-7d03-4cc4-a2bd-151760b470a0" width="200" height="200" x="50%" y="-1" patternUnits="userSpaceOnUse">
                        <path d="M100 200V.5M.5 .5H200" fill="none" />
                    </pattern>
                </defs>
                <svg x="50%" y="-1" class="overflow-visible fill-gray-50">
                    <path d="M-100.5 0h201v201h-201Z M699.5 0h201v201h-201Z M499.5 400h201v201h-201Z M-300.5 600h201v201h-201Z" stroke-width="0" />
                </svg>
                <rect width="100%" height="100%" stroke-width="0" fill="url(#e813992c-7d03-4cc4-a2bd-151760b470a0)" />
            </svg>
        </div>
        <div class="mx-auto grid max-w-2xl grid-cols-1 gap-x-4 gap-y-16 lg:mx-0 lg:max-w-none lg:grid-cols-2 lg:items-start lg:gap-y-10">
            <div class="lg:col-span-2 lg:col-start-1 lg:row-start-1 lg:mx-auto lg:grid lg:w-full lg:max-w-7xl lg:grid-cols-2 lg:gap-x-8 lg:px-8">
                <div class="lg:pr-4">
                    <div class="lg:max-w-lg">
                        <p class="text-base font-semibold leading-7 text-indigo-600">Olvídate del caos</p>
                    </div>
                    <div class="lg:max-w-xl">
                        <h1 class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Haz que el trabajo se adapte a ti</h1>
                    </div>
                    <div class="lg:max-w-lg">
                        <p class="mt-6 text-xl leading-8 text-gray-700">TimeFlex es la única solución que automatiza la gestión de turnos y horarios, aumentando la satisfacción de los empleados y simplificando el trabajo de RRHH.</p>
                    </div>
                </div>
            </div>
            <div class="-ml-12 -mt-12 p-12 lg:sticky lg:top-4 lg:col-start-2 lg:row-span-2 lg:row-start-1 lg:overflow-hidden">
                <img class="w-[48rem] max-w-none rounded-xl bg-gray-900 shadow-xl ring-1 ring-gray-400/10 sm:w-[57rem]" src="{{asset('mi-area-2.PNG')}}" alt="Interfaz de TimeFlex">
            </div>
            <div class="lg:col-span-2 lg:col-start-1 lg:row-start-2 lg:mx-auto lg:grid lg:w-full lg:max-w-7xl lg:grid-cols-2 lg:gap-x-8 lg:px-8">
                <div class="lg:pr-4">
                    <div class="max-w-xl text-base leading-7 text-gray-700 lg:max-w-lg">
                        <p>En un entorno laboral complejo, TimeFlex simplifica la planificación. ¿Turnos que se solapan? ¿Preferencias difíciles de cuadrar? Olvídate de los problemas y céntrate en lo realmente importante. Nosotros nos encargamos del resto.</p>
                        <ul role="list" class="mt-8 space-y-8 text-gray-600">
                            <li class="flex gap-x-3">
                                <svg class="mt-1 h-5 w-5 flex-none text-indigo-600" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M3 10h18v4H3v-4zm0 6h18v4H3v-4zm0-12h18v4H3V4z" />
                                </svg>
                                <span><strong class="font-semibold text-gray-900">Horarios automatizados en un clic<br></strong> TimeFlex genera automáticamente turnos optimizados según las preferencias de los empleados y las necesidades de tu empresa.</span>
                            </li>
                            <li class="flex gap-x-3">
                                <svg class="mt-1 h-5 w-5 flex-none text-indigo-600" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M12 22c5.52 0 10-4.48 10-10S17.52 2 12 2 2 6.48 2 12s4.48 10 10 10zm-1-9h2v6h-2v-6zm0-4h2v2h-2V9z" />
                                </svg>
                                <span><strong class="font-semibold text-gray-900">Mejor clima laboral <br></strong> Nuestro sistema de compensación equilibra los turnos para que todos los empleados trabajen con satisfacción y justicia.</span>
                            </li>
                            <li class="flex gap-x-3">
                                <svg class="mt-1 h-5 w-5 flex-none text-indigo-600" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M9 16h6v-6h4l-7-7-7 7h4v6z" />
                                </svg>
                                <span><strong class="font-semibold text-gray-900">Gestión de RRHH sin estrés<br></strong> TimeFlex elimina la dificultad de cuadrar horarios, permitiendo que RRHH se enfoque en tareas estratégicas.</span>
                            </li>
                        </ul>

                        <p class="mt-8">Horarios optimizados, empleados más felices y una gestión eficiente de RRHH. Todo en una sola plataforma. No esperes más, haz de TimeFlex un pilar clave en tu empresa.</p>
                        <h2 class="mt-16 text-2xl font-bold tracking-tight text-gray-900">¿Aún tienes dudas? ¡Pruébalo gratis!</h2>
                        <p class="mt-6">Sabemos que es difícil de creer, por eso te ofrecemos una demo gratuita de 15 días. Descubre por ti mismo cómo TimeFlex revoluciona la gestión de horarios.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-layout>
