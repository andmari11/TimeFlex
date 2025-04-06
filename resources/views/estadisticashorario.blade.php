@vite(['resources/js/app.js'])
<x-layout :title="'Estadísticas de horario'">
    <x-page-heading>Bienvenido a la página de estadísticas de horario de {{ $section->name }}</x-page-heading>

    <div class="flex justify-center items-center gap-4 my-4">
        <button id="prevMonth" class="px-3 py-1 bg-gray-300 rounded">←</button>
        <h2 id="monthTitle" class="text-lg font-semibold"></h2>
        <button id="nextMonth" class="px-3 py-1 bg-gray-300 rounded">→</button>
    </div>

    <div style="display: flex; flex-direction: column; align-items: center; gap: 40px;">
        <div id="calendarioHeatmap" style="width:100%; max-width: 1000px; height: 500px;"></div>
        <div class="mt-4 flex gap-2 flex-wrap justify-center items-center text-sm font-medium text-gray-700">
            <span>Menor demanda</span>
            <span class="w-6 h-4 rounded" style="background-color: #00cc00; border: 1px solid #ccc;"></span>
            <span class="w-6 h-4 rounded" style="background-color: #ccff99;"></span>
            <span class="w-6 h-4 rounded" style="background-color: #f1666d;"></span>
            <span class="w-6 h-4 rounded" style="background-color: #ed2024;"></span>
            <span>Mayor demanda</span>
        </div>
        <div id="mejoresPeoresDiasMes" style="width:100%; max-width: 1000px; height: 500px;"></div>
    </div>

    <script>
        const today = new Date();
        let currentMonth = today.getMonth(); // 0 -> enero, ...
        let currentYear = today.getFullYear();
        const monthNames = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];
        const dayLabels = ['Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab', 'Dom'];

        function renderHeatmap(month, year) {
            const weeks = ['Semana 1', 'Semana 2', 'Semana 3', 'Semana 4', 'Semana 5', 'Semana 6']; // maximo de semanas -> 6
            const data = [];
            let date = new Date(Date.UTC(year, month, 1));
            let week = 0;
            let dayNumber = 1;

            while (date.getMonth() === month) {
                let dayIndex = date.getDay() - 1;
                if (dayIndex === -1) dayIndex = 6;

                const value = Math.floor(Math.random() * 10);
                data.push({ x: dayIndex, y: week, value, day: dayNumber });

                if (date.getDay() === 0) week++;
                date.setDate(date.getDate() + 1);
                dayNumber++;
            }

            Highcharts.chart('calendarioHeatmap', {
                chart: { type: 'heatmap', marginTop: 40, marginBottom: 80, plotBorderWidth: 1,backgroundColor: '#f8f9fa', borderRadius: 20 },
                title: { text: `Demanda de turnos en ${monthNames[month]} de ${year}` },
                xAxis: { categories: dayLabels, title: null },
                yAxis: { categories: weeks, title: null, reversed: true },
                colorAxis: {
                    min: 0,  // habria que poner el minimo de turnos del mes
                    max: 10, //habria que poner el maximo de turnos del mes
                    stops: [
                        [0, '#00cc00'],   // Muy libre
                        [0.25, '#ccff99'], // Libre
                        [0.5, '#e6f0DC'],  // Normal
                        [0.75, '#f1666d'], // Alta demanda
                        [1, '#ed2024']     // Muy alta
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
                        return `<b>${weeks[this.point.y]}, ${dayLabels[this.point.x]}</b>: Valor ${this.point.value}, Día ${this.point.options.day}`;
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
        }

        function renderTimeline(month, year) {
            const timelineData = [
                { name: '62%', day: '8', color: '#00cc00' },
                { name: '51%', day: '6', color: '#6eea8e' },
                { name: '42%', day: '11', color: '#ccff99' },
                { name: '33%', day: '9', color: '#e6f0DC' },
                { name: '12%', day: '17', color: '#ff9ea2' },
                { name: '9%', day: '4', color: '#f1666d' },
                { name: '3%', day: '27', color: '#dc143c' },
                { name: '1%', day: '12', color: '#ed2024' }
            ]; // ejemplo de datos -> luego metere reales

            Highcharts.chart('mejoresPeoresDiasMes', {
                chart: {
                    type: 'timeline',
                    backgroundColor: '#f8f9fa',
                    borderRadius: 20,
                    marginTop: 40,
                    marginBottom: 80
                },
                title: {
                    text: `Días más y menos libres de ${monthNames[month]}`,
                    align: 'center'
                },
                xAxis: {
                    type: 'category',
                    categories: timelineData.map(d => `${d.day} de ${monthNames[month]}`),
                    labels: { style: { fontSize: '14px', fontWeight: 'bold' } },
                    tickInterval: 1,
                    lineWidth: 0
                },
                yAxis: { visible: false },
                legend: { enabled: false },
                tooltip: {
                    formatter: function () {
                        return `<b>${this.point.day} de ${monthNames[month]}</b> - ${this.point.name} turnos libres`;
                    }
                },
                series: [{
                    name: 'Días más y menos libres',
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
        }

        function updateAllCharts() {
            document.getElementById('monthTitle').textContent = `${monthNames[currentMonth]} ${currentYear}`;
            renderHeatmap(currentMonth, currentYear);
            renderTimeline(currentMonth, currentYear);
        }

        document.addEventListener("DOMContentLoaded", function () {
            document.getElementById('prevMonth').addEventListener('click', () => {
                currentMonth--;
                if (currentMonth < 0) {
                    currentMonth = 11;
                    currentYear--;
                }
                updateAllCharts();
            });

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
