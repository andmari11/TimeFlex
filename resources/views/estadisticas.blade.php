@vite(['resources/js/app.js'])

<x-layout :title="'Estadísticas'">
    <x-page-heading>Bienvenido a la página de estadísticas</x-page-heading>
    <div style="display: flex; justify-content: space-around; align-items: center; flex-wrap: wrap;">
        <div id="totalShiftsHours" style="width:50%; height:400px;"></div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                fetch('/total-shifts-hours', {
                    credentials: 'same-origin'
                })
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
        <div id="solicitudescambioturno" style="width:50%; height:400px;"></div>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                fetch('/user-shift-exchanges', {
                    credentials: 'same-origin'
                })
                    .then(response => response.json())
                    .then(data => {
                        Highcharts.chart('solicitudescambioturno', {
                            chart: {
                                type: 'column'
                            },
                            title: {
                                text: 'Evolución de las solicitudes de cambio de turno por mes'
                            },
                            xAxis: {
                                categories: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                                    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                                title: {
                                    text: null
                                },
                                gridLineWidth: 1,
                                lineWidth: 0
                            },
                            yAxis: {
                                min: 0,
                                title: {
                                    text: 'Número de cambios de turno',
                                    align: 'high'
                                },
                                labels: {
                                    overflow: 'justify'
                                },
                                gridLineWidth: 0
                            },
                            tooltip: {
                                valueSuffix: ' cambios de turno'
                            },
                            plotOptions: {
                                bar: {
                                    borderRadius: '50%',
                                    dataLabels: {
                                        enabled: true
                                    },
                                    groupPadding: 0.1
                                }
                            },
                            legend: {
                                layout: 'horizontal',
                                align: 'center',
                                verticalAlign: 'bottom'
                            }
                            ,
                            credits: {
                                enabled: false
                            },
                            series: [{
                                name: 'Cambios de turno solicitados',
                                data: data.solicitados,
                                color: '#00aaff'
                            }, {
                                name: 'Cambios de turno aceptados',
                                data: data.aceptados,
                                color: '#5f5fff'
                            }]
                        });
                    })
                    .catch(error => console.error('Error al cargar los datos de solicitudes de cambios de turno', error));
            });
        </script>
        <div id="solicitudesvacaciones" style="width:50%; height:400px;"></div>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                fetch('/user-holidays-evolution', {
                    credentials: 'same-origin'
                })
                    .then(response => response.json())
                    .then(data => {
                        Highcharts.chart('solicitudesvacaciones', {
                            chart: {
                                type: 'column'
                            },
                            title: {
                                text: 'Evolución de las solicitudes de vacaciones por mes'
                            },
                            xAxis: {
                                categories: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                                    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                                title: {
                                    text: null
                                },
                                gridLineWidth: 1,
                                lineWidth: 0
                            },
                            yAxis: {
                                min: 0,
                                title: {
                                    text: 'Número de vacaciones',
                                    align: 'high'
                                },
                                labels: {
                                    overflow: 'justify'
                                },
                                gridLineWidth: 0
                            },
                            tooltip: {
                                valueSuffix: ' vacaciones'
                            },
                            plotOptions: {
                                bar: {
                                    borderRadius: '50%',
                                    dataLabels: {
                                        enabled: true
                                    },
                                    groupPadding: 0.1
                                }
                            },
                            legend: {
                                layout: 'horizontal',
                                align: 'center',
                                verticalAlign: 'bottom'
                            }
                            ,
                            credits: {
                                enabled: false
                            },
                            series: [{
                                name: 'Vacaciones solicitadas',
                                data: data.solicitadas,
                                color: '#ffa500'
                            }, {
                                name: 'Vacaciones aceptadas',
                                data: data.aceptadas,
                                color: '#00cc66'
                            }]
                        });
                    })
                    .catch(error => console.error('Error al cargar los datos de solicitudes de vacaciones', error));
            });
        </script>
        <div id="vacio" style="width:50%; height:400px;"></div>
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
