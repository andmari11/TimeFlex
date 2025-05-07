<!-- cargamos alpine con defer -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<script>
    function expectedHoursComponent() {
        return {
            editMode: false,

            setGlobalHours() {
                // se obtiene los valores introducidos por el usuario
                const morning = document.getElementById('global-morning').value;
                const afternoon = document.getElementById('global-afternoon').value;
                const night = document.getElementById('global-night').value;
                // no dejamos que las horas puedan ser negativas
                if (morning < 0 || afternoon < 0 || night < 0) {
                    alert('Las horas no pueden ser negativas.');
                    return;
                }
                // se obtiene mes de los desplegables y el año usando el actual (seria 2025 ahora)
                const month = document.getElementById('mesSelect').value;
                const year = new Date().getFullYear();
                // cogemos los inputs de la tabla para mañana, tarde y noche
                const inputsMorning = document.querySelectorAll('.input-morning');
                const inputsAfternoon = document.querySelectorAll('.input-afternoon');
                const inputsNight = document.querySelectorAll('.input-night');

                inputsMorning.forEach((input, index) => {
                    // usando el turno de mañana obtenemos el usuario y su seccion
                    const userId = input.dataset.userId;
                    const sectionId = input.dataset.sectionId;

                    input.value = morning;
                    inputsAfternoon[index].value = afternoon;
                    inputsNight[index].value = night;

                    // se guardan los valores obtenidos en la bd con un post
                    fetch('/expected-hours/store-or-update', {
                        method: 'POST',
                        credentials: 'same-origin', // importante para que no de problemas el csrf
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ // definimos el JSON que se espera como respuesta
                            user_id: userId,
                            section_id: sectionId,
                            month: month,
                            year: year,
                            morning_hours: morning,
                            afternoon_hours: afternoon,
                            night_hours: night
                        })
                    })
                        .then(res => res.json())
                        .then(data => {
                            console.log(`Se guardan los datos para el usuario ${userId}`, data);
                            if (typeof cargarGraficoEmpleado === 'function') {
                                cargarGraficoEmpleado(userId);
                            }
                            if (typeof cargarGraficoHorasVsEsperadas === 'function') {
                                cargarGraficoHorasVsEsperadas(userId);
                            }
                        })
                        .catch(err => console.error(`Ha ocurrido un error al guardar los datos del usuario ${userId}`, err));
                });

                // inicializamos los campos de horas a nivel de seccion
                document.getElementById('global-morning').value = '';
                document.getElementById('global-afternoon').value = '';
                document.getElementById('global-night').value = '';
                // salir modo edicion
                this.editMode = false;
                alert('Horas asignadas a todos los empleados.');
            }
            ,

            saveChanges() {
                const sectionId = document.getElementById('seccionSelect').value;
                const month = document.getElementById('mesSelect').value;
                const year = new Date().getFullYear();
                const rows = document.querySelectorAll('#expectedHoursTable tr');

                rows.forEach(row => {
                    const userId = row.querySelector('.input-morning')?.dataset?.userId;
                    if (!userId) return;

                    const morning = row.querySelector('.input-morning').value;
                    const afternoon = row.querySelector('.input-afternoon').value;
                    const night = row.querySelector('.input-night').value;

                    fetch('/expected-hours/store-or-update', {
                        method: 'POST',
                        credentials: 'same-origin', // importante para que no de problemas el csrf
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            user_id: userId,
                            section_id: sectionId,
                            month: month,
                            year: year,
                            morning_hours: morning,
                            afternoon_hours: afternoon,
                            night_hours: night
                        })
                    })
                        .then(res => res.json())
                        .then(data => {
                            console.log(`Se guardan los datos para el usuario ${userId}`, data);
                            if (typeof cargarGraficoEmpleado === 'function') {
                                cargarGraficoEmpleado(userId);
                            }
                            if (typeof cargarGraficoHorasVsEsperadas === 'function') {
                                cargarGraficoHorasVsEsperadas(userId);
                            }
                        })
                        .catch(err => console.error(`Ha ocurrido un error al guardar los datos del usuario ${userId}`, err));
                });

                alert('Cambios guardados con éxito.');
            },

            init() {
                this.$watch('editMode', value => {
                    document.querySelectorAll('#expectedHoursTable input').forEach(input => {
                        input.disabled = !value;
                    });
                });
            }
        }
    }
</script>

<!-- defino funciones y variable editmode directamente dentro de Alpine con x-data -->
<section x-data="expectedHoursComponent()" x-init="init()" class="relative w-full bg-white mt-9 px-5 pb-8 rounded-lg shadow-md ml-4">
    <div class="flex items-center justify-between py-4 border-b border-blue/10">
        <h2 class="text-xl font-bold">Horas mensuales esperadas por empleado</h2>
    </div>

    <div class="flex justify-between gap-2 mt-4">
        <select id="seccionSelect" class="border border-gray-300 rounded-md px-3 py-2 shadow-sm w-full max-w-xs">
            <!-- opcion Todas -->
            <option value="0" {{ $defaultSectionId === 0 ? 'selected' : '' }}>Todas</option>

            <!-- resto de opciones -->
            @foreach($sections as $sec)
                <!-- tratamos de forma especial sin seccion para que salga en su lugar todas -->
                @if($sec->name !== 'Sin sección')
                    <option value="{{ $sec->id }}" {{ $defaultSectionId === $sec->id ? 'selected' : '' }}>
                        {{ $sec->name }}
                    </option>
                @endif
            @endforeach
        </select>


        <select id="mesSelect" class="border border-gray-300 rounded-md px-3 py-2 shadow-sm">
            @for ($i = 1; $i <= 12; $i++)
                <option value="{{ $i }}" {{ $i == $currentMonth ? 'selected' : '' }}>
                    {{ ucfirst(\Carbon\Carbon::create()->month($i)->locale('es')->monthName) }}
                </option>
            @endfor
        </select>
    </div>

    <div class="mt-6 overflow-y-auto max-h-72">
        <table class="min-w-full table-auto border text-center text-sm">
            <thead class="bg-gray-100">
            <tr>
                <th class="p-2">Empleado</th>
                <th class="p-2">Mañana</th>
                <th class="p-2">Tarde</th>
                <th class="p-2">Noche</th>
            </tr>
            </thead>
            <tbody id="expectedHoursTable">
            <!-- dinamicamente se va rellenando -->
            </tbody>
        </table>
    </div>

    <div class="flex justify-end mt-4">
        <template x-if="!editMode">
            <button @click="editMode = true" class="bg-blue-800 hover:bg-blue-600 text-white font-semibold py-2 px-6 rounded shadow">
                Editar
            </button>
        </template>
        <template x-if="editMode">
            <button @click="saveChanges(); editMode = false" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded shadow">
                Guardar cambios
            </button>
        </template>
    </div>

    <div x-show="editMode" class="mt-6 border rounded-lg p-4 bg-gray-50">
        <h3 class="text-md font-semibold mb-3">Definir horas por turno para toda la sección</h3>
        <div class="flex flex-wrap gap-4">
            <div>
                <label for="global-morning" class="block text-sm font-medium">Mañana</label>
                <input id="global-morning" type="number" min="0" class="w-24 border-gray-300 rounded shadow-sm px-2 py-1 text-center" />
            </div>
            <div>
                <label for="global-afternoon" class="block text-sm font-medium">Tarde</label>
                <input id="global-afternoon" type="number" min="0" class="w-24 border-gray-300 rounded shadow-sm px-2 py-1 text-center" />
            </div>
            <div>
                <label for="global-night" class="block text-sm font-medium">Noche</label>
                <input id="global-night" type="number" min="0" class="w-24 border-gray-300 rounded shadow-sm px-2 py-1 text-center" />
            </div>
            <div class="flex items-end">
                <button @click="setGlobalHours" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-4 py-2 rounded shadow">
                    Aplicar a todos
                </button>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sectionSelect = document.getElementById('seccionSelect');
        const monthSelect = document.getElementById('mesSelect');
        const tableBody = document.getElementById('expectedHoursTable');

        function fetchExpectedHours() {
            const sectionId = sectionSelect.value;
            const month = monthSelect.value;
            const sectionParam = (sectionSelect.value === 'all') ? 0 : sectionSelect.value;

            fetch(`/expected-hours/section?section_id=${sectionParam}&month=${month}`, {
                method: 'GET',
                credentials: 'same-origin', //lo ponemos para que no de problemas el csrf
            })
                .then(response => response.json())
                .then(data => {
                    tableBody.innerHTML = '';
                    data.forEach(item => {
                        const row = document.createElement('tr');
                        row.classList.add('border');
                        row.innerHTML = `
                            <td class="p-2 font-semibold">${item.user?.name ?? 'Sin nombre'}</td>
                            <td><input type="number" min="0" value="${item.morning_hours}" class="input-morning text-center border rounded w-20" data-user-id="${item.user_id}" data-section-id="${item.section_id}" disabled /></td>
                            <td><input type="number" min="0" value="${item.afternoon_hours}" class="input-afternoon text-center border rounded w-20" data-user-id="${item.user_id}" data-section-id="${item.section_id}" disabled /></td>
                            <td><input type="number" min="0" value="${item.night_hours}" class="input-night text-center border rounded w-20" data-user-id="${item.user_id}" data-section-id="${item.section_id}" disabled /></td>
                        `;
                        tableBody.appendChild(row);
                    });
                })
                .catch(error => console.error("Error al hacer fetch:", error));
        }
        //actualizacion de la tabla al cambiar seccion o mes
        sectionSelect.addEventListener('change', function () {
            const sectionId = this.value;
            if (sectionId === 'all') {
                window.location.href = '/menu';
            } else {
                window.location.href = `/menu/${sectionId}`;
            }
        });

        monthSelect.addEventListener('change', fetchExpectedHours);

        fetchExpectedHours();
    });
</script>
