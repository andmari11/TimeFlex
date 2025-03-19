@vite(['resources/js/app.js'])

<x-layout :title="'Estadísticas de horario'">
    <x-page-heading>Bienvenido a la página de estadísticas de horario</x-page-heading>
    <div style="display: flex; justify-content: space-around; align-items: center; flex-wrap: wrap;">
        <div id="calendarioHeatmap" style="width:100%; height:100%;"></div>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const days = ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'];
                let startDate = new Date(Date.UTC(2025, 0, 1));
                let endDate = new Date(Date.UTC(2025, 0, 31));

                // Encontrar el primer domingo del mes para empezar la semana
                while (startDate.getDay() !== 0) {
                    startDate.setDate(startDate.getDate() - 1);
                }

                const weeks = [];
                let week = [];
                let weekCount = 0;

                while (startDate <= endDate) {
                    if (startDate.getDay() === 0 && week.length > 0) {
                        weeks.push(week);
                        week = [];
                        weekCount++;
                    }
                    if (startDate.getMonth() === 0) { // Enero
                        week.push(startDate.getDate());
                    } else {
                        week.push(null); // Para días que no son parte de Enero
                    }
                    startDate.setDate(startDate.getDate() + 1);
                }
                // Agregar la última semana si es necesario
                if (week.length > 0) {
                    weeks.push(week);
                }

                const data = weeks.flatMap((week, y) =>
                    week.map((day, x) => [x, weekCount - y, day])
                );

                Highcharts.chart('calendarioHeatmap', {
                    chart: {
                        type: 'heatmap',
                        marginTop: 40,
                        marginBottom: 80,
                        plotBorderWidth: 1
                    },
                    title: {
                        text: 'Actividad diaria en enero de 2025'
                    },
                    xAxis: {
                        categories: days,
                        title: null
                    },
                    yAxis: {
                        categories: weeks.map((_, i) => `Semana ${weekCount - i + 1}`),
                        title: null
                    },
                    colorAxis: {
                        min: 0,
                        minColor: '#FFFFFF',
                        maxColor: Highcharts.getOptions().colors[0]
                    },
                    legend: {
                        align: 'right',
                        layout: 'vertical',
                        margin: 0,
                        verticalAlign: 'top',
                        y: 25,
                        symbolHeight: 280
                    },
                    tooltip: {
                        formatter: function () {
                            return `<b>${days[this.point.x]} ${this.point.value} de Enero:</b> Valor`;
                        }
                    },
                    series: [{
                        name: 'Actividad diaria',
                        borderWidth: 1,
                        data: data.filter(d => d[2] !== null), // Filtrar días nulos
                        dataLabels: {
                            enabled: true,
                            color: '#000000',
                            formatter: function () {
                                return this.point.value;
                            }
                        }
                    }]
                });
            });
        </script>
    </div>
</x-layout>
