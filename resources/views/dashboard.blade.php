@vite(['resources/js/app.js'])
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script src="https://code.highcharts.com/dashboards/dashboards.js"></script>
<script src="https://code.highcharts.com/dashboards/modules/layout.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/dashboards/modules/kpi.js"></script>

<link rel="stylesheet" href="https://code.highcharts.com/css/highcharts.css">
<link rel="stylesheet" href="https://code.highcharts.com/dashboards/css/datagrid.css">
<link rel="stylesheet" href="https://code.highcharts.com/dashboards/css/dashboards.css">

<x-layout :title="'Dashboard'">
    <x-page-heading>Bienvenido al dashboard que te permite ejercer un control total sobre tu empresa</x-page-heading>

    <div id="midashboard" style="width: 100%; height: 100%"></div>

    <script>
        async function fetchData() {
            const [
                empRes,
                hoursRes,
                sectionHoursRes,
                sectionSatisfactionRes,
                employeesPerSectionRes
            ] = await Promise.all([
                fetch('/total-employees', { credentials: 'same-origin' }),
                fetch('/total-shift-hours-accumulated', { credentials: 'same-origin' }),
                fetch('/shifthours-per-section-2025', { credentials: 'same-origin' }),
                fetch('/satisfaction-per-section-per-month', { credentials: 'same-origin' }),
                fetch('/employees-per-section', { credentials: 'same-origin' })
            ]);

            const totalEmployees = await empRes.json();
            const totalShiftHoursObj = await hoursRes.json();
            const totalShiftHours = totalShiftHoursObj.total_shift_hours;
            const shiftHoursData = await sectionHoursRes.json();
            const satisfactionData = await sectionSatisfactionRes.json();
            const employeesPerSection = await employeesPerSectionRes.json();

            return {
                totalEmployees: totalEmployees.total_employees,
                totalShiftHours,
                shiftHoursData,
                satisfactionData,
                employeesPerSection
            };
        }


        document.addEventListener("DOMContentLoaded", async function () {
            const meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];

            const {
                totalEmployees,
                totalShiftHours,
                shiftHoursData,
                satisfactionData,
                employeesPerSection
            } = await fetchData();

            const sectionNames = Object.keys(shiftHoursData);

            const dashboardData = sectionNames.map(name => {
                const empleados = employeesPerSection.find(e => e.section_id === sectionNames.indexOf(name));
                return {
                    name,
                    empleados: empleados ? empleados.total : 0,
                    horas: shiftHoursData[name] || 0,
                    satisfaccion: meses.map((_, i) => {
                        const key = `2025-${String(i + 1).padStart(2, '0')}`;
                        return satisfactionData[name]?.[key] ?? null;
                    })
                };
            });

            Dashboards.board('midashboard', {
                dataPool: {
                    connectors: [{
                        id: 'data',
                        type: 'JSON',
                        options: {
                            data: dashboardData
                        }
                    }]
                },
                gui: {
                    layouts: [{
                        rows: [
                            {
                                cells: [
                                    { id: 'kpi-empleados' },
                                    { id: 'kpi-horas' },
                                    { id: 'grafico-derecha-superior' }
                                ]
                            },
                            {
                                cells: [
                                    { id: 'grafico-inferior-izquierda' },
                                    { id: 'grafico-inferior-derecha' }
                                ]
                            },
                            {
                                cells: [
                                    { id: 'grafico-satisfaccion', colspan: 3 }
                                ]
                            }
                        ]
                    }]
                },
                components: [
                    {
                        renderTo: 'kpi-empleados',
                        type: 'KPI',
                        value: totalEmployees,
                        valueFormatter: (value) => `
                            <div style="text-align:center;">
                                <div style="font-size:3.5em; font-weight:bold; color:#4B0082;">${value} 👥</div>
                                <div style="font-size:1.4em; font-weight:600; color:#333;">Empleados totales</div>
                            </div>
                        `
                    },
                    {
                        renderTo: 'kpi-horas',
                        type: 'KPI',
                        value: totalShiftHours,
                        valueFormatter: (value) => `
                            <div style="text-align:center;">
                                <div style="font-size:3.5em; font-weight:bold; color:#4B0082;">${value} ⏱️</div>
                                <div style="font-size:1.4em; font-weight:600; color:#333;">Horas trabajadas</div>
                            </div>
                        `
                    },
                    {
                        renderTo: 'grafico-derecha-superior',
                        type: 'Highcharts',
                        linkedTo: 'data',
                        chartOptions: {
                            chart: { type: 'pie' },
                            title: { text: 'Distribución de empleados' },
                            series: [{
                                name: 'Empleados',
                                colorByPoint: true,
                                data: dashboardData.map(d => ({
                                    name: d.name,
                                    y: d.empleados
                                }))
                            }]
                        }
                    },
                    {
                        renderTo: 'grafico-inferior-izquierda',
                        type: 'Highcharts',
                        linkedTo: 'data',
                        chartOptions: {
                            chart: { type: 'bar' },
                            title: { text: 'Empleados por sección' },
                            xAxis: { categories: ['Empleados'] },
                            yAxis: {
                                min: 0,
                                allowDecimals: false,
                                title: { text: 'Número de empleados' },
                                labels: {
                                    formatter: function () {
                                        return Number.isInteger(this.value) ? this.value : '';
                                    }
                                }
                            },
                            series: dashboardData.map(d => ({
                                name: d.name,
                                data: [d.empleados]
                            }))
                        }
                    }
                    ,
                    {
                        renderTo: 'grafico-inferior-derecha',
                        type: 'Highcharts',
                        linkedTo: 'data',
                        chartOptions: {
                            chart: { type: 'column' },
                            title: { text: 'Horas trabajadas por sección en 2025' },
                            xAxis: { categories: ['2025'], crosshair: true },
                            yAxis: {
                                min: 0,
                                title: { text: 'Horas trabajadas' }
                            },
                            series: dashboardData.map(d => ({
                                name: d.name,
                                data: [d.horas]
                            }))
                        }
                    },
                    {
                        renderTo: 'grafico-satisfaccion',
                        type: 'Highcharts',
                        linkedTo: 'data',
                        chartOptions: {
                            chart: { type: 'line' },
                            title: { text: 'Satisfacción media por sección y mes' },
                            xAxis: { categories: meses },
                            yAxis: {
                                title: { text: 'Satisfacción media' }
                            },
                            series: dashboardData.map(d => ({
                                name: d.name,
                                data: d.satisfaccion
                            }))
                        }
                    }
                ]
            });
        });
    </script>
</x-layout>
