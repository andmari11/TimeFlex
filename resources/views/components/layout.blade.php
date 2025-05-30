<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{$title ?? "TimeFlex"}}</title>
    @vite(['resources/js/app.js'])
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        window.satComponent = () => ({
            open: false,
            data: null,
            chart: null,
            init() {
                // cuando Alpine inicializa el componente llamo al endpoint
                fetch('/satisfaction-user-vs-section', { credentials: 'same-origin' })
                    .then(r => r.json())
                    .then(json => { this.data = json })
                    .catch(console.error)

                // veo si el modal esta abierto?
                this.$watch('open', visible => {
                    if (!visible) return
                    // esperamos a que se muestre contenedor
                    this.$nextTick(() => {
                        if (!this.chart && this.data) {
                            this.chart = Highcharts.chart('satisfaccionModalContainer', {
                                chart:    { type: 'spline', animation: false },
                                title:    { text: 'Evolución de la Satisfacción' },
                                subtitle: { text: 'Empleado vs Sección' },
                                xAxis:    { categories: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                                        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'] },
                                yAxis:    { title: { text: 'Satisfacción' }, min: 0, max: 10 },
                                series: [
                                    { name: 'Empleado',      data: this.data.empleado },
                                    { name: 'Media de la sección', data: this.data.seccion }
                                ]
                            });
                        } else if (this.chart) {
                            // reajustar tamaño
                            this.chart.reflow();
                        }
                    });
                });
            }
        });
    </script>


</head>

<body class="h-full {{ $background ?? 'bg-gray-100' }}">
    @auth
        <!-- llamadas para ver el numero de notificaciones -->
        <script>
            function checkUnreadNotifications() {
                fetch('/unread-notifications', {
                    credentials: 'same-origin',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        const notificationDot = document.getElementById('notification-dot');
                        const notificationsList = document.getElementById('notifications-list');

                        if (data.length > 0) {
                            notificationDot.classList.remove('hidden');
                        } else {
                            notificationDot.classList.add('hidden');
                        }

                        if (data.length > 0) {
                            notificationsList.innerHTML = '';
                            const notificationCenterURL = '/notificationspanel'; // defino ruta al centro de notificaciones
                            data.forEach(notification => {
                                const notificationElement = document.createElement('a');
                                notificationElement.href = notification.url ? notification.url : `${notificationCenterURL }?tipo=${notification.tipo}`;
                                notificationElement.classList.add('block', 'px-4', 'py-2', 'text-sm', 'text-gray-700', 'hover:bg-gray-100', 'hover:underline', 'hover:rounded-md');
                                notificationElement.textContent = notification.message;

                                notificationsList.appendChild(notificationElement);
                            });
                        } else {
                            notificationsList.innerHTML = '<a href="#" class="block px-4 py-2 text-sm hover:rounded-md text-gray-700 hover:bg-gray-100">No hay notificaciones nuevas</a>';
                        }
                    })
                    .catch(error => console.error('Error al obtener las notificaciones:', error));
            }

            // Llamar a la función al cargar la página y cada segundo para actualizar
            setInterval(checkUnreadNotifications, 1000);
            checkUnreadNotifications(); // Llamada inicial para obtener las notificaciones al cargar la página
        </script>
    @endauth



    <div x-data="{ open_menu: false , open_profile_menu: false}" x-cloak class="min-h-full">
        <nav class="bg-gray-800">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <img class="h-10 w-10" src="{{ asset('logo_no_background.png') }}" alt="logo" >
                        </div>
                        <div class="hidden md:block">
                            <div class="ml-10 flex items-baseline space-x-4">
                                @guest
                                    <x-nav-link-mobile ref="/">Home </x-nav-link-mobile>
                                    <x-nav-link-mobile ref="/about-us">Sobre nosotros </x-nav-link-mobile>
                                    <x-nav-link-mobile ref="/contact">Contacto</x-nav-link-mobile>
                                @endguest
                                @auth
                                    <x-nav-link-mobile ref="/menu">Mi área </x-nav-link-mobile>
                                    <x-nav-link-mobile ref="/horario">Mis horarios </x-nav-link-mobile>
                                    <x-nav-link-mobile ref="/formularios">Mis formularios </x-nav-link-mobile>
                                    <x-nav-link-mobile ref="/equipo">Mi equipo </x-nav-link-mobile>
                                    @if(auth()->user()->role != 'admin')
                                        <x-nav-link-mobile ref="/ayuda">Ayuda </x-nav-link-mobile>
                                    @endif
                                        @if(auth()->user()->role === 'admin')
                                            <x-nav-link-mobile ref="/dashboard">Dashboard </x-nav-link-mobile>
                                        @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                    <div class="hidden md:block">

                        <div class="ml-4 flex items-center md:ml-6">
                            @guest
                            <x-nav-link ref="/login">Iniciar Sesión </x-nav-link>
                            <x-nav-link-light ref="/register-company"> ¡Organiza tu empresa hoy! </x-nav-link-light>

                            @endguest
                            @auth
                                <button type="button"
                                        class="relative rounded-full bg-gray-800 p-1 text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
                                        x-data="{ open_options_menu: false }">
                                    <span class="absolute -inset-1.5" @click="open_options_menu = !open_options_menu"></span>
                                    <span class="sr-only">View notifications</span>

                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" @click="open_options_menu = !open_options_menu">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                                    </svg>
                                    <div id="notification-dot-container relative">
                                        <span id="notification-dot" class="absolute top-0 right-0 block h-2.5 w-2.5 rounded-full bg-red-500 ring-2 ring-gray-800 hidden"></span>


                                        <!-- Menú desplegable -->
                                        <div x-show="open_options_menu" @click.away="open_options_menu = false"
                                             class="absolute right-0 z-10 mt-2 w-64 bg-white shadow-lg rounded-md ring-1 ring-black ring-opacity-5">
                                            <div id="notifications-list">
                                            </div>

                                            <!-- Botón fijo en la parte inferior para ir al centro de notificaciones -->
                                            <div class="border-t border-gray-200 mt-2">
                                                <a href="{{ route('notifications.panel') }}"
                                                   class="block w-full text-center px-4 py-2 text-sm font-medium text-blue-600 hover:bg-gray-100 hover:underline">
                                                    Ir al Centro de Notificaciones
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </button>

                            <!-- Profile dropdown -->
                            <div class="relative ml-3" @click.outside="open_profile_menu=false">
                                <div>
                                    <button @click="open_profile_menu = !open_profile_menu" type="button" class="relative flex max-w-xs items-center rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                        <span class="absolute -inset-1.5"></span>
                                        <span class="sr-only">Open user menu</span>
                                        <img class="h-8 w-8 rounded-full" src="https://static.vecteezy.com/system/resources/previews/004/274/186/non_2x/person-icon-user-interface-icon-silhouette-of-man-simple-symbol-a-glyph-symbol-in-your-web-site-design-logo-app-ui-webinar-video-chat-ect-vector.jpg" alt="">
                                    </button>
                                </div>


                                <div x-show="open_profile_menu" class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                                    <!-- Active: "bg-gray-100", Not Active: "" -->
                                    <a href="/perfil" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-0">Tu perfil</a>

                                    <!-- Ajustes con su submenu -->
                                    <div x-data="{ showSubmenu: false }" class="relative"
                                         @mouseenter="showSubmenu = true"
                                         @mouseleave="showSubmenu = false">

                                        <div class="flex items-center justify-between px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer">
                                            Ajustes

                                            <!-- Pintamos la flecha que abre el submenu -->
                                            <svg class="w-4 h-4 ml-2 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </div>

                                        <!-- Submenu de ajustes -->
                                        <div x-show="showSubmenu" x-transition class="absolute top-0 left-full ml-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg z-30">
                                            <!-- Enlace a la ventana cuya lógica y aspecto se definen más abajo -->
                                            <a href="#"
                                               @click.prevent="$dispatch('open-notification-settings')"
                                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-blue-600">
                                                Notificaciones
                                            </a>
                                        </div>
                                    </div>

                                    <form method="POST" action="/logout">
                                        @csrf
                                        <button type='submit' class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-2">Cerrar sesión</button>
                                    </form>
                                </div>
                            </div>
                            @endauth
                        </div>
                    </div>
                    <div  class="-mr-2 flex md:hidden">
                        <!-- Mobile menu button -->
                        <button @click="open_menu = !open_menu" type="button" class="relative inline-flex items-center justify-center rounded-md bg-gray-800 p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800" aria-controls="mobile-menu" aria-expanded="false">

                            <span class="absolute -inset-0.5"></span>
                            <span  class="sr-only">Open main menu</span>
                            <!-- Menu open: "hidden", Menu closed: "block" -->
                            <svg class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>

                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile menu, show/hide based on menu state. -->
            <div class="md:hidden" id="mobile-menu">
                <div x-show="open_menu" class="space-y-1 px-2 pb-3 pt-2 sm:px-3">
                    @guest
                        <x-nav-link-mobile ref="/">Home </x-nav-link-mobile>
                        <x-nav-link-mobile ref="/about-us">Sobre nosotros </x-nav-link-mobile>
                        <x-nav-link-mobile ref="/contact">Contacto</x-nav-link-mobile>
                    @endguest
                    @auth
                        <x-nav-link-mobile ref="/menu">Mi área </x-nav-link-mobile>
                        <x-nav-link-mobile ref="/horario">Mis horarios </x-nav-link-mobile>
                        <x-nav-link-mobile ref="/formularios">Mis formularios </x-nav-link-mobile>
                        <x-nav-link-mobile ref="/equipo">Mi equipo </x-nav-link-mobile>
                        @if(auth()->user()->role != 'admin')
                            <x-nav-link-mobile ref="/ayuda">Ayuda </x-nav-link-mobile>
                        @endif
                            @if(auth()->user()->role === 'admin')
                                <x-nav-link-mobile ref="/dashboard">Dashboard </x-nav-link-mobile>
                            @endif
                    @endauth
                </div>
                <div class="border-t border-gray-700 pb-3 pt-4" >
                    <div class="flex items-center px-5">
                        <button @click="open_profile_menu=!open_profile_menu" type="button" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-800">
                            <!-- Imagen de perfil -->
                            <img class="h-10 w-10 rounded-full" src="https://static.vecteezy.com/system/resources/previews/004/274/186/non_2x/person-icon-user-interface-icon-silhouette-of-man-simple-symbol-a-glyph-symbol-in-your-web-site-design-logo-app-ui-webinar-video-chat-ect-vector.jpg" alt="">

                            <!-- Información de perfil (nombre y email) -->
                            <div class="ml-3 text-left">
                                <div class="text-base font-medium leading-none text-white">Mario Gómez</div>
                                <div class="text-sm font-medium leading-none text-gray-400">mariogl@gmail.com</div>
                            </div>
                        </button>

                        <button type="button" class="relative ml-auto flex-shrink-0 rounded-full bg-gray-800 p-1 text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                            <span class="absolute -inset-1.5"></span>
                            <span class="sr-only">View notifications</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                            </svg>
                        </button>
                    </div>
                    <div x-show="open_profile_menu"  class="mt-3 space-y-1 px-2">
                        <a href="#" class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white">Tu perfil</a>
                        <a href="#" class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white">Ajustes</a>
                        <a href="#" class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white">Cerrar sesión</a>
                    </div>
                </div>
            </div>
        </nav>

        <main>
            @if ($errors->has('message'))
                <div class="alert w-full bg-red-600 text-white p-4 ">
                    {{ $errors->first('message') }}
                </div>
            @endif
            {{$slot}}
        </main>
    </div>
    @auth
        <!-- Inicializamos las notificaciones con los valores guardados en la BD -->
        <script>
            function getNotificationsPreferencesData() {
                return {
                    open: false,
                    settings: {
                        ayuda: true,
                        turno: true,
                        sistema: true,
                        otras: true,
                    },
                    init() {
                        fetch('/get-notifications-preferences')
                            .then(response => response.json())
                            .then(data => {
                                this.settings = data;
                            })
                            .catch(error => {
                                console.error('Error cargando las preferencias de notificaciones de la BD :', error);
                            });
                    }
                }
            }
        </script>
    <!-- Panel de ajuste de notificaciones -->
    <div
        x-data="getNotificationsPreferencesData()"
        @open-notification-settings.window="open = true"
        @close-notification-settings.window="open = false"
        x-show="open"
        style="display: none"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
    >
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 relative">
            <!-- Botón de cerrar el panel -->
            <button @click="open = false" class="absolute top-3 right-3 text-gray-500 hover:text-gray-800">
                ✕
            </button>

            <h2 class="text-xl font-semibold mb-4">Tipos de Notificaciones Activadas</h2>
            <div class="space-y-4">
                <template x-for="(enabled, category) in settings" :key="category">
                    <div class="flex justify-between items-center">
                        <span class="capitalize" x-text="category"></span>
                        <input
                            type="checkbox"
                            :checked="enabled"
                            @change="settings[category] = !settings[category]"
                            class="w-10 h-5 bg-gray-300 rounded-full appearance-none checked:bg-green-500 focus:outline-none transition duration-200 relative before:absolute before:content-[''] before:w-4 before:h-4 before:bg-white before:rounded-full before:top-0.5 before:left-0.5 checked:before:translate-x-5 before:transition before:duration-200"
                        />
                    </div>
                </template>
            </div>

            <div class="mt-6 text-right">
                <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700" @click="guardarNotificaciones(settings)">
                    Guardar preferencias
                </button>

            </div>
        </div>
        <script>
            function guardarNotificaciones(settings) {
                fetch('/save-notifications-preferences', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(settings)
                })
                    .then(res => {
                        if (!res.ok) {
                            throw new Error('Ha ocurrido un error al guardar las preferencias de notificaciones');
                        }
                        return res.json();
                    })
                    .then(data => {
                        if (data.success) {
                            alert('Preferencias de notificaciones guardadas con éxito');
                            // cerramos la ventana de preferencias de notificaciones
                            window.dispatchEvent(new CustomEvent('close-notification-settings'));
                        } else {
                            alert('Ha ocurrido un error al guardar las preferencias de notificaciones');
                        }
                    })
                    .catch(error => {
                        console.error('Ha ocurrido un error inesperado: ', error);
                        alert('Ha ocurrido un error inesperado: ' + error.message);
                    });
            }

        </script>

    </div>
    @endauth
    @stack('scripts')
</body>
</html>
