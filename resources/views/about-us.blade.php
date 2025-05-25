<x-layout :title="'Sobre nosotros'">
    <div class="bg-white py-24 sm:py-32">
        <div class="mx-auto grid max-w-7xl gap-x-8 gap-y-20 px-6 lg:px-8 xl:grid-cols-3">
            <div class="max-w-2xl">
                <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Conoce a nuestro equipo</h2>
                <p class="mt-6 text-lg leading-8 text-gray-600">Jóvenes, apasionados y enamorados de las nuevas tecnologías. Así son nuestros socios fundadores. <br><br>  Somos estudiantes de Ingeniería Informática en la Universidad Complutense de Madrid, con ganas de innovar y crear soluciones que marquen la diferencia. Nos une la curiosidad, el espíritu emprendedor y el deseo de llevar la tecnología al siguiente nivel. 🚀</p>
            </div>
            <ul role="list" class="grid gap-x-8 gap-y-12 sm:grid-cols-2 sm:gap-y-16 xl:col-span-2 xl:px-10 xl:py-20">
                <li>
                    <div class="flex items-center gap-x-6">
                        <img class="h-16 w-16 rounded-full" src="{{asset("andres_mari.jpg")}}" alt="">
                        <div>
                            <h3 class="text-base font-semibold leading-7 tracking-tight text-gray-900">Andrés Marí Piqueras</h3>
                            <p class="text-sm font-semibold leading-6 text-indigo-600">Socio fundador</p>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="flex items-center gap-x-6">
                        <img class="h-16 w-16 rounded-full" src="{{asset("alex_lopez_foto.jpg")}}" alt="">
                        <div>
                            <h3 class="text-base font-semibold leading-7 tracking-tight text-gray-900">Alejandro López López de la Cova</h3>
                            <p class="text-sm font-semibold leading-6 text-indigo-600">Socio fundador</p>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="flex items-center gap-x-6">
                        <img class="h-16 w-16 rounded-full" src="{{asset("alvaro_foto.jpg")}}" alt="">
                        <div>
                            <h3 class="text-base font-semibold leading-7 tracking-tight text-gray-900">Álvaro Juan Martín Sánchez-Montañez</h3>
                            <p class="text-sm font-semibold leading-6 text-indigo-600">Socio fundador</p>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="flex items-center gap-x-6">
                        <img class="h-16 w-16 rounded-full" src="{{asset("gabriel_fernandez_foto.jpg")}}" alt="">
                        <div>
                            <h3 class="text-base font-semibold leading-7 tracking-tight text-gray-900">Gabriel Fernández Sacristán</h3>
                            <p class="text-sm font-semibold leading-6 text-indigo-600">Socio fundador</p>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</x-layout>

