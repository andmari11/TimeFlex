@vite(['resources/js/app.js'])
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script src="https://code.highcharts.com/dashboards/dashboards.js"></script>
<script src="https://code.highcharts.com/dashboards/modules/layout.js"></script>
<link rel="stylesheet" href="https://code.highcharts.com/css/highcharts.css">
<link rel="stylesheet" href="https://code.highcharts.com/dashboards/css/datagrid.css">
<link rel="stylesheet" href="https://code.highcharts.com/dashboards/css/dashboards.css">

<x-layout :title="'Dashboard'">
    <x-page-heading>Bienvenido al dashboard que te permite ejercer un control total sobre tu empresa</x-page-heading>
    <div id="midashboard" style="width:100%; height:100%;">Hola</div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Highcharts.setOptions({
                chart: {
                    styledMode: true
                }
            });

            // Datos para los gráficos
            const data = {
                'Sin sección': { empleados: 6, horas: 420, satisfaccion: [3.2, 3.5, 4.0, 4.5, 4.0, 4.2, 4.5, 4.6, 3.9, 3.4, 4.0, 3.8] },
                'Admin': { empleados: 3, horas: 580, satisfaccion: [4.5, 4.0, 4.2, 4.6, 4.7, 4.9, 5.0, 4.8, 4.7, 4.6, 4.8, 4.9] },
                'IT': { empleados: 4, horas: 440, satisfaccion: [3.0, 3.1, 3.2, 3.5, 3.6, 3.7, 3.8, 4.0, 4.2, 4.4, 4.2, 3.9] },
                'Marketing': { empleados: 3, horas: 590, satisfaccion: [4.2, 4.3, 4.4, 4.5, 4.6, 4.7, 4.8, 4.9, 5.0, 4.9, 4.8, 4.7] }
            };

            // Convertir los datos en formatos adecuados para cada tipo de gráfico
            const categories = Object.keys(data);
            const empleadosData = categories.map(key => data[key].empleados);
            const horasData = categories.map(key => data[key].horas);
            const meses = [
                'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
            ];


            // Inicializar el dashboard
            Dashboards.board('midashboard', {
                dataPool: {
                    connectors: [{
                        id: 'data',
                        type: 'JSON',
                        options: {
                            data: categories.map(key => ({
                                name: key,
                                y: data[key].empleados,
                                horas: data[key].horas,
                                satisfaccion: data[key].satisfaccion
                            }))
                        }
                    }]
                },
                gui: {
                    layouts: [{
                        rows: [{
                            cells: [{ id: 'top-left' }, { id: 'top-right' }]
                        }, {
                            cells: [{ id: 'bottom-left' }, { id: 'bottom-right' }]
                        }]
                    }]
                },
                components: [{
                    renderTo: 'top-left',
                    type: 'Highcharts',
                    linkedTo: 'data',
                    chartOptions: {
                        chart: {
                            type: 'bar'
                        },
                        title: {
                            text: 'Empleados por sección'
                        },
                        xAxis: {
                            categories: ['Empleados']
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: 'Número de empleados'
                            }
                        },
                        series: categories.map(key => ({
                            name: key,
                            data: [data[key].empleados]
                        })),
                        legend: {
                            enabled: true
                        }
                    }
                }

                    , {
                    renderTo: 'top-right',
                    type: 'Highcharts',
                    linkedTo: 'data',
                    chartOptions: {
                        chart: {
                            type: 'pie'
                        },
                        title: {
                            text: 'Distribución de empleados'
                        },
                        series: [{
                            name: 'Distribución',
                            colorByPoint: true,
                            data: categories.map(key => ({
                                name: key,
                                y: data[key].empleados
                            }))
                        }]
                    }
                }, {
                        renderTo: 'bottom-left',
                        type: 'Highcharts',
                        linkedTo: 'data',
                        chartOptions: {
                            chart: {
                                type: 'column'
                            },
                            title: {
                                text: 'Horas trabajadas por sección en 2025'
                            },
                            xAxis: {
                                categories: ['2025'],  // Esto agrupa todas las barras bajo un solo año o categoría general
                                crosshair: true
                            },
                            yAxis: {
                                min: 0,
                                title: {
                                    text: 'Horas trabajadas'
                                }
                            },
                            series: categories.map(key => ({
                                name: key,
                                data: [data[key].horas]  // Datos de horas trabajadas en un array para cada sección
                            })),
                            legend: {
                                enabled: true,
                                layout: 'vertical',
                                align: 'right',
                                verticalAlign: 'top'
                            }
                        }
                    }
                    , {
                    renderTo: 'bottom-right',
                    type: 'Highcharts',
                    linkedTo: 'data',
                    chartOptions: {
                        chart: {
                            type: 'line'
                        },
                        title: {
                            text: 'Satisfacción media por sección y mes'
                        },
                        xAxis: {
                            categories: meses
                        },
                        yAxis: {
                            title: {
                                text: 'Satisfacción media'
                            }
                        },
                        series: categories.map(key => ({
                            name: key,
                            data: data[key].satisfaccion
                        }))
                    }
                }]
            });
        });
    </script>
    </div>
</x-layout>
