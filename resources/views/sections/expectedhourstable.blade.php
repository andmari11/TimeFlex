<!-- cargamos alpine con defer -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<script>
    function expectedHoursComponent() {
        return {
            editMode: false,

            setGlobalHours() {
                const morning = document.getElementById('global-morning').value;
                const afternoon = document.getElementById('global-afternoon').value;
                const night = document.getElementById('global-night').value;

                document.querySelectorAll('.input-morning').forEach(input => input.value = morning);
                document.querySelectorAll('.input-afternoon').forEach(input => input.value = afternoon);
                document.querySelectorAll('.input-night').forEach(input => input.value = night);

                document.getElementById('global-morning').value = '';
                document.getElementById('global-afternoon').value = '';
                document.getElementById('global-night').value = '';
            },

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
                        credentials: 'same-origin',
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
                        .then(data => console.log(`Guardado user ${userId}`, data))
                        .catch(err => console.error(`Error al guardar user ${userId}`, err));
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

    <div class="flex justify-end gap-4 mt-4">
        <select id="seccionSelect" class="border border-gray-300 rounded-md px-3 py-2 shadow-sm">
            @foreach ($sections as $section)
                @if ($section->name !== 'Sin sección')
                    <option value="{{ $section->id }}" {{ $section->id == $defaultSectionId ? 'selected' : '' }}>
                        {{ $section->name }}
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
            <thead class="bg-gray-100 sticky top-0 z-10">
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
            <button @click="editMode = true" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-6 rounded shadow">
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
                <input id="global-morning" type="number" class="w-24 border-gray-300 rounded shadow-sm px-2 py-1 text-center" />
            </div>
            <div>
                <label for="global-afternoon" class="block text-sm font-medium">Tarde</label>
                <input id="global-afternoon" type="number" class="w-24 border-gray-300 rounded shadow-sm px-2 py-1 text-center" />
            </div>
            <div>
                <label for="global-night" class="block text-sm font-medium">Noche</label>
                <input id="global-night" type="number" class="w-24 border-gray-300 rounded shadow-sm px-2 py-1 text-center" />
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
            if (!sectionId) return;

            fetch(`/expected-hours/section?section_id=${sectionId}&month=${month}`, {
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
                        <td><input type="number" value="${item.morning_hours}" class="input-morning text-center border rounded w-20" data-user-id="${item.user_id}" disabled /></td>
                        <td><input type="number" value="${item.afternoon_hours}" class="input-afternoon text-center border rounded w-20" data-user-id="${item.user_id}" disabled /></td>
                        <td><input type="number" value="${item.night_hours}" class="input-night text-center border rounded w-20" data-user-id="${item.user_id}" disabled /></td>
                    `;
                        tableBody.appendChild(row);
                    });
                })
                .catch(error => console.error("Error al hacer fetch:", error));
        }
        //actualizacion de la tabla al cambiar seccion o mes
        sectionSelect.addEventListener('change', fetchExpectedHours);
        monthSelect.addEventListener('change', fetchExpectedHours);

        fetchExpectedHours();
    });
</script>
