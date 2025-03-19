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

    </div>
</x-layout>
