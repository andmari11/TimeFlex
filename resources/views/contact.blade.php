<x-layout :title="'Contacto'">

<div class="bg-white">
    <div class="isolate bg-white px-6 py-8 sm:py-20 lg:px-8">

        <div class="mx-auto max-w-2xl text-center">
            <h2 class="text-balance text-4xl font-semibold tracking-tight text-gray-900 sm:text-5xl">Contáctanos</h2>
            <p class="mt-2 text-lg/8 text-gray-600">¿Tienes preguntas o quieres probar TimeFlex? Escríbenos y descubre cómo podemos ayudarte.</p>
        </div>
        <form id="contactoForm" action="{{ route('contact.store') }}" method="POST" class="mx-auto mt-16 max-w-xl sm:mt-20">
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
                <div id="formMessages" class="mt-6"></div>
            </div>
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
        const form = document.getElementById('contactoForm');
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

            fetch("{{ route('contact.store') }}", {
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
