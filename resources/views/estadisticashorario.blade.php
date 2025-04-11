@vite(['resources/js/app.js'])
<x-layout :title="'Estadísticas de horario'">
    <x-page-heading>Bienvenido a la página de estadísticas de horario de {{ $section->name }}</x-page-heading>

    <div class="flex justify-center items-center gap-4 my-4">
        <button id="prevMonth" class="px-3 py-1 bg-gray-300 rounded">←</button>
        <h2 id="monthTitle" class="text-lg font-semibold"></h2>
        <button id="nextMonth" class="px-3 py-1 bg-gray-300 rounded">→</button>
    </div>

    <!-- calendario turnos -->
    <div style="display: flex; flex-direction: column; align-items: center; gap: 40px;">
        <div id="calendarioTurnosHeatmap" style="width:100%; max-width: 1000px; height: 500px;"></div>
    </div>
    <!-- leyenda para turnos -->
    <div class="w-full flex justify-center">
        <div class="mt-8 flex gap-2 flex-wrap justify-center items-center text-sm font-medium text-gray-700">
        <span>Menor demanda</span>
            <span class="w-6 h-4 rounded" style="background-color: #00cc00; border: 1px solid #ccc;"></span>
            <span class="w-6 h-4 rounded" style="background-color: #ccff99;"></span>
            <span class="w-6 h-4 rounded" style="background-color: #f1666d;"></span>
            <span class="w-6 h-4 rounded" style="background-color: #ed2024;"></span>
            <span>Mayor demanda</span>
        </div>
    </div>

    <!-- calendario vacaciones -->
    <div class="mt-8" style="display: flex; flex-direction: column; align-items: center; gap: 40px;">
        <div id="calendarioVacacionesHeatmap" style="width:100%; max-width: 1000px; height: 500px;"></div>
    </div>

    <!-- leyenda vacaciones -->
    <div class="w-full flex justify-center">
        <div class="mt-8 flex gap-2 flex-wrap justify-center items-center text-sm font-medium text-gray-700">
        <span>Baja demanda</span>
            <span class="w-6 h-4 rounded" style="background-color: #00cc00; border: 1px solid #ccc;"></span>
            <span class="w-6 h-4 rounded" style="background-color: #ccff99;"></span>
            <span class="w-6 h-4 rounded" style="background-color: #f1666d;"></span>
            <span class="w-6 h-4 rounded" style="background-color: #ed2024;"></span>
            <span>Alta demanda</span>
        </div>
    </div>

    <!-- timeline mejores/peores dias en cuanto a demanda de vacaciones -->
    <div class="mt-8" style="display: flex; flex-direction: column; align-items: center; gap: 40px;">
        <div id="mejoresPeoresDiasMes" style="width:100%; max-width: 1000px; height: 500px;"></div>
    </div>
    <script>
        const sectionId = {{ $section->id }};
        const today = new Date();
        let currentMonth = today.getMonth(); // 0 -> enero, 1 -> febrero y asi
        let currentYear = today.getFullYear();
        const monthNames = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];
        const dayLabels = ['Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab', 'Dom'];
        function renderHeatmap(month, year) {
            const weeks = ['Semana 1', 'Semana 2', 'Semana 3', 'Semana 4', 'Semana 5', 'Semana 6']; // maximo de semanas -> 6
            const data = [];

            let date = new Date(Date.UTC(year, month, 1)); // inicializamos en el primer dia del mes
            let week = 0;
            let dayNumber = 1;

            fetch(`/section-demand/${sectionId}/${month + 1}-${year}`)
                .then(response => response.json())
                .then(demandaPorDia => {
                    const demandaPorFecha = {};
                    demandaPorDia.forEach(item => {
                        demandaPorFecha[item.day] = item.total; // cambiamos el formato del array recibido a algo mas comodo
                    });

                    while (date.getMonth() === month) {
                        let dayIndex = date.getDay() - 1;
                        if (dayIndex === -1) dayIndex = 6; // para pasar el domingo al final del calendario

                        const formattedDate = date.toISOString().split('T')[0]; // pasamos fecha a formato yyyy-mm-dd
                        const valor = demandaPorFecha[formattedDate] ?? 0; // si no hay datos entonces ponemos 0

                        data.push({ x: dayIndex, y: week, value: valor, day: dayNumber }); //metemos el valor de numero de turnos para ese dia

                        if (date.getDay() === 0) week++;
                        date.setDate(date.getDate() + 1);
                        dayNumber++;
                    }

                    const valores = data.map(d => d.value);
                    const maxValor = Math.max(...valores);
                    const minValor = Math.min(...valores);

                    Highcharts.chart('calendarioTurnosHeatmap', {
                        chart: {
                            type: 'heatmap',
                            marginTop: 40,
                            marginBottom: 80,
                            plotBorderWidth: 1,
                            backgroundColor: '#f8f9fa',
                            borderRadius: 20
                        },
                        title: { text: `Demanda de turnos` },
                        xAxis: { categories: dayLabels, title: null },
                        yAxis: { categories: weeks, title: null, reversed: true },
                        colorAxis: {
                            min: minValor,
                            max: maxValor,
                            stops: [
                                [0, '#00cc00'],   // verde oscuro
                                [0.3, '#ccff99'], // verde claro
                                [0.5, '#ffff99'], // amarillo
                                [0.8, '#f1666d'], // rojo claro
                                [1, '#ed2024']    // rojo oscuro

                            ]
                        },
                        legend: {
                            align: 'right',
                            layout: 'vertical',
                            verticalAlign: 'top',
                            y: 25,
                            symbolHeight: 280
                        },
                        tooltip: {
                            formatter: function () {
                                return `<b>${weeks[this.point.y]}, ${dayLabels[this.point.x]}</b>: ${this.point.value} turnos, Día ${this.point.options.day}`;
                            }
                        },
                        series: [{
                            name: 'Turnos demandados',
                            borderWidth: 1,
                            data: data,
                            dataLabels: {
                                enabled: true,
                                color: '#000',
                                formatter: function () {
                                    return this.point.options.day;
                                }
                            }
                        }]
                    });
                });
        }
        function renderHolidaysHeatmap(month, year) {
            const weeks = ['Semana 1', 'Semana 2', 'Semana 3', 'Semana 4', 'Semana 5', 'Semana 6']; // maximo de semanas -> 6
            const data = [];
            let date = new Date(Date.UTC(year, month, 1)); // inicializamos en el primer dia del mes
            let week = 0;
            let dayNumber = 1;

            fetch(`/section-holidays/${sectionId}/${month + 1}-${year}`)
                .then(response => response.json())
                .then(vacacionesPorDia => {
                    const vacacionesMap = {};
                    vacacionesPorDia.forEach(item => {
                        vacacionesMap[item.day] = item.total; // cambiamos el formato del array recibido a algo mas comodo
                    });

                    while (date.getMonth() === month) {
                        let dayIndex = date.getDay() - 1;
                        if (dayIndex === -1) dayIndex = 6; // para pasar el domingo al final del calendario

                        const formattedDate = date.toISOString().split('T')[0]; // pasamos fecha a formato yyyy-mm-dd
                        const valor = vacacionesMap[formattedDate] ?? 0; // si no hay datos entonces ponemos 0

                        data.push({ x: dayIndex, y: week, value: valor, day: dayNumber }); //metemos el valor de numero de turnos para ese dia

                        if (date.getDay() === 0) week++;
                        date.setDate(date.getDate() + 1);
                        dayNumber++;
                    }

                    const valores = data.map(d => d.value);
                    const maxValor = Math.max(...valores);
                    const minValor = Math.min(...valores);

                    Highcharts.chart('calendarioVacacionesHeatmap', {
                        chart: {
                            type: 'heatmap',
                            marginTop: 40,
                            marginBottom: 80,
                            plotBorderWidth: 1,
                            backgroundColor: '#f8f9fa',
                            borderRadius: 20
                        },
                        title: { text: `Vacaciones aceptadas` },
                        xAxis: { categories: dayLabels, title: null },
                        yAxis: { categories: weeks, title: null, reversed: true },
                        colorAxis: {
                            min: minValor,
                            max: maxValor,
                            stops: [
                                [0, '#00cc00'],   // verde oscuro
                                [0.3, '#ccff99'], // verde claro
                                [0.5, '#ffff99'], // amarillo
                                [0.8, '#f1666d'], // rojo claro
                                [1, '#ed2024']    // rojo oscuro

                            ]
                        },
                        legend: {
                            align: 'right',
                            layout: 'vertical',
                            verticalAlign: 'top',
                            y: 25,
                            symbolHeight: 280
                        },
                        tooltip: {
                            formatter: function () {
                                return `<b>${weeks[this.point.y]}, ${dayLabels[this.point.x]}</b>: ${this.point.value} empleados de vacaciones, Día ${this.point.options.day}`;
                            }
                        },
                        series: [{
                            name: 'Vacaciones por día',
                            borderWidth: 1,
                            data: data,
                            dataLabels: {
                                enabled: true,
                                color: '#000',
                                formatter: function () {
                                    return this.point.options.day;
                                }
                            }
                        }]
                    });
                });
        }

        function renderTimeline(month, year) {
            fetch(`/section-pending-holidays/${sectionId}/${month + 1}-${year}`)
                .then(response => response.json())
                .then(data => {
                    // obtenemos los valores unicos
                    const valoresUnicos = [...new Set(data.map(item => item.total))];
                    // si hay menos de 2 valores unicos no mostramos el grafico -> mostramos un mensaje
                    if (valoresUnicos.length < 2) {
                        document.getElementById('mejoresPeoresDiasMes').innerHTML = `
                    <div class="text-gray-600 text-center mt-10 font-medium">
                        No hay suficientes días con solicitudes pendientes para mostrar estadísticas comparativas
                    </div>`;
                        return;
                    }
                    // obtenemos los valores maximos y minimos en cuanto a numero de solicitudes
                    const maxSolicitudes = Math.max(...data.map(item => item.total));
                    const minSolicitudes = Math.min(...data.map(item => item.total));
                    // funcion para asignar color segun el numero de solicitudes para el timeline
                    const getColor = (total) => {
                        // lo del divisor lo pongo para evitar dividir por 0
                        const ratio = (total - minSolicitudes) / (maxSolicitudes - minSolicitudes || 1);

                        if (ratio <= 0.2) return '#00cc00';   // verde oscuro
                        if (ratio <= 0.4) return '#ccff99';   // verde claro
                        if (ratio <= 0.8) return '#ff6961';   // rojo claro
                        return '#ed2024';                     // rojo oscuro
                    };
                    // construccion del timeline (grafico) en caso de que haya un numero suficiente de resultados
                    const timelineData = data.map(item => {
                        const fecha = new Date(item.day);
                        return {
                            name: `${item.total} solicitud${item.total > 1 ? 'es' : ''}`,
                            day: item.day,
                            fecha: `${fecha.getDate()} de ${monthNames[month]}`,
                            color: getColor(item.total)
                        };
                    });

                    Highcharts.chart('mejoresPeoresDiasMes', {
                        chart: {
                            type: 'timeline',
                            backgroundColor: '#f8f9fa',
                            borderRadius: 20,
                            marginTop: 40,
                            marginBottom: 80
                        },
                        title: {
                            text: `Días con más y menos solicitudes de vacaciones pendientes`,
                            align: 'center'
                        },
                        xAxis: {
                            type: 'category',
                            categories: timelineData.map(d => d.fecha),
                            labels: { style: { fontSize: '14px', fontWeight: 'bold' } },
                            tickInterval: 1,
                            lineWidth: 0
                        },
                        yAxis: { visible: false },
                        legend: { enabled: false },
                        tooltip: {
                            formatter: function () {
                                return `<b>${this.point.fecha}</b> - ${this.point.name}`;
                            }
                        },
                        series: [{
                            name: 'Solicitudes de vacaciones pendientes',
                            data: timelineData,
                            marker: { symbol: 'rect', width: 225, height: 60, lineWidth: 0 },
                            lineWidth: 5,
                            lineColor: '#d3d3d3',
                            dataLabels: {
                                enabled: true,
                                allowOverlap: true,
                                formatter: function () {
                                    return `<b>${this.point.name}</b>`;
                                },
                                style: {
                                    fontSize: '18px',
                                    fontWeight: 'bold',
                                    color: '#000',
                                    textOutline: 'none'
                                }
                            }
                        }]
                    });
                });

        }
        // funcion para actualizar todos los graficos
        function updateAllCharts() {
            document.getElementById('monthTitle').textContent = `${monthNames[currentMonth]} ${currentYear}`;
            renderHeatmap(currentMonth, currentYear);
            renderHolidaysHeatmap(currentMonth, currentYear);
            renderTimeline(currentMonth, currentYear);
        }
        // funcion para actualizar los graficos con el mes obtenido al dar a la flecha <-
        document.addEventListener("DOMContentLoaded", function () {
            document.getElementById('prevMonth').addEventListener('click', () => {
                currentMonth--;
                if (currentMonth < 0) {
                    currentMonth = 11;
                    currentYear--;
                }
                updateAllCharts();
            });
            // funcion para actualizar los graficos con el mes obtenido al dar a la flecha ->
            document.getElementById('nextMonth').addEventListener('click', () => {
                currentMonth++;
                if (currentMonth > 11) {
                    currentMonth = 0;
                    currentYear++;
                }
                updateAllCharts();
            });

            updateAllCharts();
        });
    </script>
</x-layout>
