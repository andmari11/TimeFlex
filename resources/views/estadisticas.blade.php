@vite(['resources/js/app.js'])

<x-layout :title="'Estadísticas'">
    <x-page-heading>Bienvenido a la página de estadísticas</x-page-heading>
    <div style="display: flex; justify-content: space-around; align-items: center; flex-wrap: wrap;margin-top: 2rem;">
        @if(auth()->user()->role !== 'admin')
            <div id="totalShiftsHours" style="width:49%; height:400px;"></div>
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
                                const monthIndex = parseInt(entry.month) - 1; // restamos uno para pasar el mes a indice
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
                                    data: monthlyHours,
                                    color: '#00bfae'
                                }]
                            });
                        })
                        .catch(error => console.error('Error al cargar los datos de horas trabajadas: ', error));
                });
            </script>
       @endif
        <div id="solicitudescambioturno" style="width:49%; height:400px;"></div>
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
    </div>

    <div style="display: flex; justify-content: space-around; align-items: center; flex-wrap: wrap;margin-top: 2rem;">
        <div id="solicitudesvacaciones" style="width:49%; height:400px;"></div>
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

        @if(auth()->user()->role !== 'admin')
            <div id="burbujasMesTurnos" style="width:49%; height:400px;"></div>
                <script>
                document.addEventListener("DOMContentLoaded", function () {
                    fetch('/monthly-shift-satisfaction', {
                        credentials: 'same-origin'
                    })
                        .then(response => response.json())
                        .then(data => {
                            Highcharts.chart('burbujasMesTurnos', {
                                chart: {
                                    type: 'bubble',
                                    plotBorderWidth: 1,
                                    zoomType: 'xy'
                                },
                                title: {
                                    text: 'Satisfacción mensual respecto a la cantidad y tipo de turnos'
                                },
                                xAxis: {
                                    title: { text: 'Mes' },
                                    min: -0.5,
                                    max: 11.5,
                                    tickInterval: 1,
                                    labels: {
                                        formatter: function () {
                                            return ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'][this.value];
                                        }
                                    }
                                },
                                yAxis: {
                                    title: {
                                        text: 'Satisfacción media'
                                    },
                                    max: 10,
                                    min: 0
                                },
                                tooltip: {
                                    useHTML: true,
                                    headerFormat: '<b>{point.name}</b><br>',
                                    pointFormat: 'Mes: {point.x}<br>Satisfacción: {point.y}<br>Turnos: {point.z}'
                                },
                                plotOptions: {
                                    bubble: {
                                        minSize: 10,
                                        maxSize: 60
                                    }
                                },
                                credits: { enabled: false },
                                series: data
                            });
                        })
                        .catch(error => console.error('Error al cargar el gráfico de burbujas:', error));
                });
            </script>
       @endif
    </div>
        <div style="display: flex; justify-content: center; margin-top: 20px;">
            <button onclick="window.history.back()" style="padding: 10px 20px; font-size: 16px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
                Volver
            </button>
        </div>
</x-layout>
