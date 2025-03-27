<x-layout :title="'Ayuda'">
    <x-page-heading>Bienvenido a la página de ayuda</x-page-heading>
    <!-- ====== FAQ Section Start -->
    <section
        x-data="
   {
   openFaq1: false,
   openFaq2: false,
   openFaq3: false,
   openFaq4: false,
   openFaq5: false,
   openFaq6: false
   }
   "
        class="relative z-20 overflow-hidden bg-white dark:bg-dark pt-20 pb-12 lg:pt-[120px] lg:pb-[90px]"
    >
        <div class="container mx-auto">
            <div class="flex flex-wrap -mx-4">
                <div class="w-full px-4">
                    <div class="mx-auto mb-[60px] max-w-[520px] text-center lg:mb-20">
               <span class="block mb-2 text-lg font-semibold text-primary">
               Preguntas frecuentes
               </span>
                        <h2
                            class="text-dark dark:text-white mb-4 text-3xl font-bold sm:text-[40px]/[48px]"
                        >
                            ¿Alguna pregunta? Resuelve tus problemas
                        </h2>
                        <p class="text-base text-body-color dark:text-dark-6">
                            Nuestra sección de preguntas frecuentes contiene las soluciones a los obstáculos mas habituales a la hora de interactuar con la aplicación
                        </p>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap -mx-4">
                <div class="w-full px-4 lg:w-1/2">
                    <div
                        class="w-full p-4 mb-8 bg-white rounded-lg shadow-[0px_20px_95px_0px_rgba(201,203,204,0.30)] dark:shadow-[0px_20px_95px_0px_rgba(0,0,0,0.30)] dark:bg-dark-2 sm:p-8 lg:px-6 xl:px-8"
                    >
                        <button
                            class="flex w-full text-left faq-btn"
                            @click="openFaq1 = !openFaq1"
                        >
                            <div
                                class="bg-primary/5 dark:bg-white/5 text-primary mr-5 flex h-10 w-full max-w-[40px] items-center justify-center rounded-lg"
                            >
                                <svg
                                    :class="openFaq1 && 'rotate-180'"
                                    width="22"
                                    height="22"
                                    viewBox="0 0 22 22"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        d="M11 15.675C10.7937 15.675 10.6219 15.6062 10.45 15.4687L2.54374 7.69998C2.23436 7.3906 2.23436 6.90935 2.54374 6.59998C2.85311 6.2906 3.33436 6.2906 3.64374 6.59998L11 13.7844L18.3562 6.53123C18.6656 6.22185 19.1469 6.22185 19.4562 6.53123C19.7656 6.8406 19.7656 7.32185 19.4562 7.63123L11.55 15.4C11.3781 15.5719 11.2062 15.675 11 15.675Z"
                                        fill="currentColor"
                                    />
                                </svg>
                            </div>
                            <div class="w-full">
                                <h4
                                    class="mt-1 text-lg font-semibold text-dark dark:text-white"
                                >
                                    ¿Dónde puedo consultar los días de vacaciones que son más probables que me acepten?
                                </h4>
                            </div>
                        </button>
                        <div x-show="openFaq1" class="faq-content pl-[62px]">
                            <p
                                class="py-3 text-base leading-relaxed text-body-color dark:text-dark-6"
                            >
                                Por supuesto, en la página global de estadísticas tendrás información acerca de tus vacaciones. Además, en la página de tus horarios puedes acceder al apartado de estadísticas para conocer los días dentro del horario con mayor probabilidad de ser aceptados como días de vacaciones.
                            </p>
                        </div>
                    </div>
                    <div
                        class="w-full p-4 mb-8 bg-white rounded-lg shadow-[0px_20px_95px_0px_rgba(201,203,204,0.30)] dark:shadow-[0px_20px_95px_0px_rgba(0,0,0,0.30)] dark:bg-dark-2 sm:p-8 lg:px-6 xl:px-8"
                    >
                        <button
                            class="flex w-full text-left faq-btn"
                            @click="openFaq2 = !openFaq2"
                        >
                            <div
                                class="bg-primary/5 dark:bg-white/5 text-primary mr-5 flex h-10 w-full max-w-[40px] items-center justify-center rounded-lg"
                            >
                                <svg
                                    :class="openFaq2 && 'rotate-180'"
                                    width="22"
                                    height="22"
                                    viewBox="0 0 22 22"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        d="M11 15.675C10.7937 15.675 10.6219 15.6062 10.45 15.4687L2.54374 7.69998C2.23436 7.3906 2.23436 6.90935 2.54374 6.59998C2.85311 6.2906 3.33436 6.2906 3.64374 6.59998L11 13.7844L18.3562 6.53123C18.6656 6.22185 19.1469 6.22185 19.4562 6.53123C19.7656 6.8406 19.7656 7.32185 19.4562 7.63123L11.55 15.4C11.3781 15.5719 11.2062 15.675 11 15.675Z"
                                        fill="currentColor"
                                    />
                                </svg>
                            </div>
                            <div class="w-full">
                                <h4
                                    class="mt-1 text-lg font-semibold text-dark dark:text-white"
                                >
                                    ¿Hay alguna forma de agilizar la creación de nuevos formularios?
                                </h4>
                            </div>
                        </button>
                        <div x-show="openFaq2" class="faq-content pl-[62px]">
                            <p
                                class="py-3 text-base leading-relaxed text-body-color dark:text-dark-6"
                            >
                                Sí, la funcionalidad de duplicar formularios permite duplicar el contenido de un formulario anterior, agilizando el proceso para elaborar nuevos formularios similares. Gracias a ella, no tendrás que crear cada formulario desde cero y podrás optimizar tu tiempo.
                            </p>
                        </div>
                    </div>
                    <div
                        class="w-full p-4 mb-8 bg-white rounded-lg shadow-[0px_20px_95px_0px_rgba(201,203,204,0.30)] dark:shadow-[0px_20px_95px_0px_rgba(0,0,0,0.30)] dark:bg-dark-2 sm:p-8 lg:px-6 xl:px-8"
                    >
                        <button
                            class="flex w-full text-left faq-btn"
                            @click="openFaq3 = !openFaq3"
                        >
                            <div
                                class="bg-primary/5 dark:bg-white/5 text-primary mr-5 flex h-10 w-full max-w-[40px] items-center justify-center rounded-lg"
                            >
                                <svg
                                    :class="openFaq3 && 'rotate-180'"
                                    width="22"
                                    height="22"
                                    viewBox="0 0 22 22"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        d="M11 15.675C10.7937 15.675 10.6219 15.6062 10.45 15.4687L2.54374 7.69998C2.23436 7.3906 2.23436 6.90935 2.54374 6.59998C2.85311 6.2906 3.33436 6.2906 3.64374 6.59998L11 13.7844L18.3562 6.53123C18.6656 6.22185 19.1469 6.22185 19.4562 6.53123C19.7656 6.8406 19.7656 7.32185 19.4562 7.63123L11.55 15.4C11.3781 15.5719 11.2062 15.675 11 15.675Z"
                                        fill="currentColor"
                                    />
                                </svg>
                            </div>
                            <div class="w-full">
                                <h4
                                    class="mt-1 text-lg font-semibold text-dark dark:text-white"
                                >
                                    ¿Cómo puedo saber cuándo tengo nuevas notificaciones?
                                </h4>
                            </div>
                        </button>
                        <div x-show="openFaq3" class="faq-content pl-[62px]">
                            <p
                                class="py-3 text-base leading-relaxed text-body-color dark:text-dark-6"
                            >
                                En el momento en el que recibas nuevas notificaciones, el icono de la campana situado en la esquina superior derecha aparecerá con un círculo rojo indicando que tienes notificaciones pendientes de leer.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="w-full px-4 lg:w-1/2">
                    <div
                        class="w-full p-4 mb-8 bg-white rounded-lg shadow-[0px_20px_95px_0px_rgba(201,203,204,0.30)] dark:shadow-[0px_20px_95px_0px_rgba(0,0,0,0.30)] dark:bg-dark-2 sm:p-8 lg:px-6 xl:px-8"
                    >
                        <button
                            class="flex w-full text-left faq-btn"
                            @click="openFaq4 = !openFaq4"
                        >
                            <div
                                class="bg-primary/5 dark:bg-white/5 text-primary mr-5 flex h-10 w-full max-w-[40px] items-center justify-center rounded-lg"
                            >
                                <svg
                                    :class="openFaq4 && 'rotate-180'"
                                    width="22"
                                    height="22"
                                    viewBox="0 0 22 22"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        d="M11 15.675C10.7937 15.675 10.6219 15.6062 10.45 15.4687L2.54374 7.69998C2.23436 7.3906 2.23436 6.90935 2.54374 6.59998C2.85311 6.2906 3.33436 6.2906 3.64374 6.59998L11 13.7844L18.3562 6.53123C18.6656 6.22185 19.1469 6.22185 19.4562 6.53123C19.7656 6.8406 19.7656 7.32185 19.4562 7.63123L11.55 15.4C11.3781 15.5719 11.2062 15.675 11 15.675Z"
                                        fill="currentColor"
                                    />
                                </svg>
                            </div>
                            <div class="w-full">
                                <h4
                                    class="mt-1 text-lg font-semibold text-dark dark:text-white"
                                >
                                    ¿Puedo consultar el horario de mis compañeros de trabajo?
                                </h4>
                            </div>
                        </button>
                        <div x-show="openFaq4" class="faq-content pl-[62px]">
                            <p
                                class="py-3 text-base leading-relaxed text-body-color dark:text-dark-6"
                            >
                                Por supuesto, desde la pestaña de mi equipo podrás no solo consultar quiénes son los miembros de tu sección sino que también podrás visualizar sus horarios y sus correos electrónicos. De esta forma, podrás obtener información valiosa para solicitar cambios de turno y conocer cómo contactar con tus compañeros.
                            </p>
                        </div>
                    </div>
                    <div
                        class="w-full p-4 mb-8 bg-white rounded-lg shadow-[0px_20px_95px_0px_rgba(201,203,204,0.30)] dark:shadow-[0px_20px_95px_0px_rgba(0,0,0,0.30)] dark:bg-dark-2 sm:p-8 lg:px-6 xl:px-8"
                    >
                        <button
                            class="flex w-full text-left faq-btn"
                            @click="openFaq5 = !openFaq5"
                        >
                            <div
                                class="bg-primary/5 dark:bg-white/5 text-primary mr-5 flex h-10 w-full max-w-[40px] items-center justify-center rounded-lg"
                            >
                                <svg
                                    :class="openFaq5 && 'rotate-180'"
                                    width="22"
                                    height="22"
                                    viewBox="0 0 22 22"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        d="M11 15.675C10.7937 15.675 10.6219 15.6062 10.45 15.4687L2.54374 7.69998C2.23436 7.3906 2.23436 6.90935 2.54374 6.59998C2.85311 6.2906 3.33436 6.2906 3.64374 6.59998L11 13.7844L18.3562 6.53123C18.6656 6.22185 19.1469 6.22185 19.4562 6.53123C19.7656 6.8406 19.7656 7.32185 19.4562 7.63123L11.55 15.4C11.3781 15.5719 11.2062 15.675 11 15.675Z"
                                        fill="currentColor"
                                    />
                                </svg>
                            </div>
                            <div class="w-full">
                                <h4
                                    class="mt-1 text-lg font-semibold text-dark dark:text-white"
                                >
                                    ¿Existe alguna forma de que como administrador pueda ver información global de la empresa?
                                </h4>
                            </div>
                        </button>
                        <div x-show="openFaq5" class="faq-content pl-[62px]">
                            <p
                                class="py-3 text-base leading-relaxed text-body-color dark:text-dark-6"
                            >
                                La página de dashboard, exclusiva para administradores, permite de un vistazo obtener información de las métricas más importantes de la empresa: número de empleados totales, horas trabajadas, satisfacción y distribución de empleados a nivel de sección...
                            </p>
                        </div>
                    </div>
                    <div
                        class="w-full p-4 mb-8 bg-white rounded-lg shadow-[0px_20px_95px_0px_rgba(201,203,204,0.30)] dark:shadow-[0px_20px_95px_0px_rgba(0,0,0,0.30)] dark:bg-dark-2 sm:p-8 lg:px-6 xl:px-8"
                    >
                        <button
                            class="flex w-full text-left faq-btn"
                            @click="openFaq6 = !openFaq6"
                        >
                            <div
                                class="bg-primary/5 dark:bg-white/5 text-primary mr-5 flex h-10 w-full max-w-[40px] items-center justify-center rounded-lg"
                            >
                                <svg
                                    :class="openFaq6 && 'rotate-180'"
                                    width="22"
                                    height="22"
                                    viewBox="0 0 22 22"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        d="M11 15.675C10.7937 15.675 10.6219 15.6062 10.45 15.4687L2.54374 7.69998C2.23436 7.3906 2.23436 6.90935 2.54374 6.59998C2.85311 6.2906 3.33436 6.2906 3.64374 6.59998L11 13.7844L18.3562 6.53123C18.6656 6.22185 19.1469 6.22185 19.4562 6.53123C19.7656 6.8406 19.7656 7.32185 19.4562 7.63123L11.55 15.4C11.3781 15.5719 11.2062 15.675 11 15.675Z"
                                        fill="currentColor"
                                    />
                                </svg>
                            </div>
                            <div class="w-full">
                                <h4
                                    class="mt-1 text-lg font-semibold text-dark dark:text-white"
                                >
                                    ¿Los horarios de trabajo tienen que ser de un mes de duración o pueden ser flexibles?
                                </h4>
                            </div>
                        </button>
                        <div x-show="openFaq6" class="faq-content pl-[62px]">
                            <p
                                class="py-3 text-base leading-relaxed text-body-color dark:text-dark-6"
                            >
                                Timeflex te permite establecer horarios de trabajo de duración flexible, por lo que puedes crear horarios de 2 semanas o de 3 meses y medio, por ejemplo. ¡Con Timeflex podrás optimizar cualquier día del año!
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 right-0 z-[-1]">
            <svg
                width="1440"
                height="886"
                viewBox="0 0 1440 886"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
            >
                <path
                    opacity="0.5"
                    d="M193.307 -273.321L1480.87 1014.24L1121.85 1373.26C1121.85 1373.26 731.745 983.231 478.513 729.927C225.976 477.317 -165.714 85.6993 -165.714 85.6993L193.307 -273.321Z"
                    fill="url(#paint0_linear)"
                />
                <defs>
                    <linearGradient
                        id="paint0_linear"
                        x1="1308.65"
                        y1="1142.58"
                        x2="602.827"
                        y2="-418.681"
                        gradientUnits="userSpaceOnUse"
                    >
                        <stop stop-color="#3056D3" stop-opacity="0.36" />
                        <stop offset="1" stop-color="#F5F2FD" stop-opacity="0" />
                        <stop offset="1" stop-color="#F5F2FD" stop-opacity="0.096144" />
                    </linearGradient>
                </defs>
            </svg>
        </div>
    </section>
    <!-- ====== FAQ Section End -->
    <div class="bg-white">
        <div class="isolate bg-white px-6 py-8 sm:py-20 lg:px-8">

            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-balance text-4xl font-semibold tracking-tight text-gray-900 sm:text-5xl">¿Sigues con dudas? Contáctanos</h2>
                <p class="mt-2 text-lg/8 text-gray-600">¿Necesitas ayuda adicional para entender alguna de las funcionalidades de TimeFlex? Escríbenos y descubre cómo podemos ayudarte.</p>
            </div>
            <form id="ayudaForm" method="POST" class="mx-auto mt-16 max-w-xl sm:mt-20">
                @csrf
                <div class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2">
                    <div>
                        <label for="first-name" class="block text-sm/6 font-semibold text-gray-900">Nombre</label>
                        <div class="mt-2.5">
                            <input type="text" name="first-name" id="first-name" autocomplete="given-name" class="block w-full rounded-md bg-white px-3.5 py-2 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600">
                        </div>
                    </div>
                    <div>
                        <label for="last-name" class="block text-sm/6 font-semibold text-gray-900">Apellidos</label>
                        <div class="mt-2.5">
                            <input type="text" name="last-name" id="last-name" autocomplete="family-name" class="block w-full rounded-md bg-white px-3.5 py-2 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600">
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="company" class="block text-sm/6 font-semibold text-gray-900">Nombre de empresa</label>
                        <div class="mt-2.5">
                            <input type="text" name="company" id="company" autocomplete="organization" class="block w-full rounded-md bg-white px-3.5 py-2 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600">
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="email" class="block text-sm/6 font-semibold text-gray-900">Correo electrónico</label>
                        <div class="mt-2.5">
                            <input type="email" name="email" id="email" autocomplete="email" class="block w-full rounded-md bg-white px-3.5 py-2 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600">
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="phone-number" class="block text-sm/6 font-semibold text-gray-900">Número de teléfono</label>
                        <div class="mt-2.5">
                            <div class="flex rounded-md bg-white outline outline-1 -outline-offset-1 outline-gray-300 has-[input:focus-within]:outline has-[input:focus-within]:outline-2 has-[input:focus-within]:-outline-offset-2 has-[input:focus-within]:outline-indigo-600">
                                <div class="grid shrink-0 grid-cols-1 focus-within:relative">
                                    <select id="country" name="country" autocomplete="country" aria-label="Country" class="col-start-1 row-start-1 w-full appearance-none rounded-md py-2 pl-3.5 pr-7 text-base text-gray-500 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                        <option>ES</option>
                                        <option>US</option>
                                        <option>CA</option>
                                        <option>EU</option>
                                    </select>
                                    <svg class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-500 sm:size-4" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" data-slot="icon">
                                        <path fill-rule="evenodd" d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="text" name="phone-number" id="phone-number" class="block min-w-0 grow py-1.5 pl-1 pr-3 text-base text-gray-900 placeholder:text-gray-400 focus:outline focus:outline-0 sm:text-sm/6" placeholder="123 456 789">
                            </div>
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="message" class="block text-sm/6 font-semibold text-gray-900">Mensaje</label>
                        <div class="mt-2.5">
                            <textarea name="message" id="message" rows="4" class="block w-full rounded-md bg-white px-3.5 py-2 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600"></textarea>
                        </div>
                    </div>
                    <div class="flex gap-x-4 sm:col-span-2 items-center">
                        <div class="relative">
                            <input type="checkbox" name="privacy" id="privacy" value="1" class="sr-only peer" required>
                            <label for="privacy" class="block w-10 h-6 bg-gray-200 rounded-full peer-checked:bg-indigo-600 transition-colors duration-200 cursor-pointer"></label>
                            <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform duration-200 peer-checked:translate-x-4 pointer-events-none"></div>
                        </div>

                        <label for="privacy" class="text-sm/6 text-gray-600 cursor-pointer">
                            Confirme que ha leído y acepta nuestra
                            <a href="{{ asset('politica_privacidad_timeflex.pdf') }}" class="font-semibold text-indigo-600" target="_blank">política&nbsp;de privacidad</a>.
                        </label>
                    </div>



                </div>
                <div id="formMessages" class="mt-6"></div>
                <div class="mt-10">
                    <button type="submit" class="block w-full rounded-md bg-indigo-600 px-3.5 py-2.5 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Enviar</button>
                </div>
            </form>
            <div id="successModal"
                 class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-40">
                <div class="bg-white w-full max-w-md p-6 rounded-lg shadow-lg text-center">
                    <h2 class="text-2xl font-semibold text-green-700 mb-4">Gracias por contactarnos</h2>
                    <p class="text-gray-700">Te responderemos en el menor tiempo posible a tu consulta.</p>
                    <button id="closeModal"
                            class="mt-6 inline-block px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none">
                        Cerrar
                    </button>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('ayudaForm');
            const messages = document.getElementById('formMessages');
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
            const closeModalBtn = document.getElementById('closeModal');
            const modal = document.getElementById('successModal');

            closeModalBtn.addEventListener('click', () => {
                modal.classList.add('hidden');
            });
            const checkbox = document.getElementById('privacy');
            checkbox.addEventListener('invalid', function () {
                this.setCustomValidity('Debes aceptar nuestra política de privacidad para continuar con la petición');
            });
            checkbox.addEventListener('input', function () {
                this.setCustomValidity('');
            });
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                messages.innerHTML = '';



                const formData = new FormData(form);

                fetch("{{ route('ayuda.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin',
                    body: formData
                })
                    .then(async response => {
                        if (response.ok) {
                            form.reset();
                            const modal = document.getElementById('successModal');
                            modal.classList.remove('hidden');
                            modal.scrollIntoView({ behavior: 'smooth', block: 'center' });

                        } else {
                            const data = await response.json();
                            if (data.errors) {
                                let errorList = '<ul class="list-disc list-inside text-sm">';
                                Object.values(data.errors).forEach(errorGroup => {
                                    errorGroup.forEach(error => {
                                        errorList += `<li>${error}</li>`;
                                    });
                                });
                                errorList += '</ul>';

                                messages.innerHTML = `
                                    <div class="text-red-700 bg-red-50 border border-red-200 rounded-md px-4 py-3 shadow-sm">
                                        <p class="font-semibold mb-2">Corrige los siguientes errores:</p>
                                        ${errorList}
                                    </div>
                                    `;
                            }
                        }
                    })
                    .catch(error => {
                        messages.innerHTML = `
                        <div class="text-red-700 bg-red-50 border border-red-200 rounded-md px-4 py-3 shadow-sm">
                            <p class="font-semibold">Error al enviar el formulario. Inténtalo de nuevo más tarde.</p>
                        </div>
                    `;
                        console.error('Error:', error);
                    });
            });

        });
    </script>

</x-layout>
