@vite(['resources/js/app.js'])

<x-layout :title="'Estadísticas'">
    <x-page-heading>Bienvenido a la página de estadísticas</x-page-heading>
    <div style="display: flex; justify-content: space-around; align-items: center; flex-wrap: wrap;">
        <div id="totalShiftsHours" style="width:50%; height:400px;"></div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                fetch('/total-shifts-hours')
                    .then(response => response.json())
                    .then(data => {

                        const hoursData = data.hours;
                        const monthlyHours = new Array(12).fill(0);

                        hoursData.forEach(entry => {
                            const monthIndex = parseInt(entry.month) - 1; // Convertir el mes a índice (0-11)
                            monthlyHours[monthIndex] = entry.hours;
                        });

                        Highcharts.chart('totalShiftsHours', {
                            chart: { type: 'column' },
                            title: { text: 'Horas Trabajadas' },
                            xAxis: {
                                categories: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                                    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre']
                            },
                            yAxis: { title: { text: 'Horas' } },
                            series: [{
                                name: 'Horas',
                                data: monthlyHours
                            }]
                        });
                    })
                    .catch(error => console.error('Error loading the data: ', error));
            });
        </script>
        <div id="employeesPerSectionChart" style="width:50%; height:400px;"></div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                fetch('/employees-per-section')
                    .then(response => response.json())
                    .then(data => {
                        const categories = data.map(item => `Sección ${item.section_id}`);
                        const seriesData = data.map(item => item.total);

                        Highcharts.chart('employeesPerSectionChart', {
                            chart: { type: 'column' },
                            title: { text: 'Número de empleados por sección' },
                            xAxis: {
                                categories: categories,
                                title: { text: 'Secciones' }
                            },
                            yAxis: {
                                min: 0,
                                title: { text: 'Número de Empleados' },
                                allowDecimals: false
                            },
                            series: [{
                                name: 'Empleados',
                                data: seriesData
                            }]
                        });
                    })
                    .catch(error => console.error('Error loading the data: ', error));
            });
        </script>
        <div id="satisfaccion" style="width:100%; height:500px;"></div>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                if (document.getElementById('satisfaccion')) {
                    console.log("Iniciando grafico2...");

                    // Datos de ejemplo
                    const inflationData = [4, 7, 5, 10, 6, 9, 8, 3, 7];
                    const historicalAverage = [5, 6, 6, 7, 6, 7, 6, 5, 6];

                    Highcharts.chart('satisfaccion', {
                        chart: {
                            type: 'spline',
                            animation: {
                                duration: 1000 // duracion animacion
                            }
                        },

                        title: {
                            text: 'Evolución de la Satisfacción'
                        },

                        subtitle: {
                            text: 'Empleado vs Sección'
                        },

                        xAxis: {
                            categories: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre']
                        },

                        yAxis: {
                            title: {
                                text: 'Satisfacción'
                            }
                        },

                        plotOptions: {
                            series: {
                                animation: {
                                    duration: 1500
                                },
                                marker: {
                                    enabled: true,
                                    symbol: 'circle'
                                },
                                lineWidth: 2
                            }
                        },

                        series: [{
                            name: 'Empleado',
                            data: inflationData,
                            animation: {
                                duration: 1500
                            }
                        }, {
                            name: 'Media de la sección',
                            data: historicalAverage,
                            animation: {
                                duration: 1500,
                                defer: 1000 // retraso de la animacion
                            }
                        }]
                    });
                } else {
                    console.error("No ha cargado el div satisfaccion");
                }
            });
        </script>

    </div>
</x-layout>
