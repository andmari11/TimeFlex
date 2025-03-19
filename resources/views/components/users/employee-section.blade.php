@vite(['resources/js/app.js'])
@props(['employee', 'showGraphs' => true])
<script>
    console.log('El valor de showGraphs es', {{ $showGraphs ? 'true' : 'false' }});
</script>

<div class="p-4  bg-blue-50 shadow rounded-xl my-1 w-full max-w-sm mx-auto">
    <div class="flex items-center justify-center">
        <img class="h-20 w-20 rounded-full" src="https://static.vecteezy.com/system/resources/previews/004/274/186/non_2x/person-icon-user-interface-icon-silhouette-of-man-simple-symbol-a-glyph-symbol-in-your-web-site-design-logo-app-ui-webinar-video-chat-ect-vector.jpg" alt="">
    </div>
    <div class="flex flex-col items-center">
        <h3 class="text-xl text-gray-700 my-2 font-bold">{{$employee->name}}</h3>
        <h3 class="text-lg text-gray-700 my-2 bg-white/10 hover:bg-white/35 px-3 py-1 rounded-xl">{{$employee->section->name}}</h3>
        <a class="text-lg text-gray-700 my-2 hover:underline">{{$employee->email}}</a>
        <a class="bg-sky-700 hover:bg-sky-900 px-4 py-3 rounded-xl text-xl text-white font-bold my-3">Ver horario</a>
    </div>
    @if(auth()->user()->role === 'admin')
        <a href="/sections/{{$employee->section->id}}/edit" class="bg-blue-500 hover:bg-blue-400 px-2 py-1 rounded-xl text-xs text-white">Editar</a>
        <button onclick="confirmDeleteSection(event, {{$employee->section->id}})" class="bg-red-600 hover:bg-red-400 px-2 py-1 rounded-xl text-xs text-white">Eliminar</button>
        <form method="POST" action="/sections/{{$employee->section->id}}/delete" id="delete-form-{{$employee->section->id}}" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    @endif
</div>
@if ($showGraphs)
    <div id="statsuser-{{ $employee->id }}" style="width: 100%; height: 400px;"></div>
    <script>
            document.addEventListener("DOMContentLoaded", function () {
                Highcharts.chart('statsuser-{{ $employee->id }}', {
                    chart: {
                        type: 'pie',
                        backgroundColor: '#f0f9ff',
                        events: {
                            render() {
                                const chart = this,
                                    series = chart.series[0];
                                let customLabel = chart.renderer.label(
                                    'Total<br/><strong>2 877 820</strong>'
                                )
                                    .css({
                                        color: '#000',
                                        textAnchor: 'middle'
                                    })
                                    .add();

                                const x = series.center[0] + chart.plotLeft,
                                    y = series.center[1] + chart.plotTop - (customLabel.attr('height') / 2);

                                customLabel.attr({x, y});
                                customLabel.css({
                                    fontSize: `${series.center[2] / 12}px`
                                });
                            }
                        }
                    },
                    title: {
                        text: 'Distribución de turnos',
                        align: 'center',
                        verticalAlign: 'top',
                        y:60
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.percentage:.0f}%</b>'
                    },
                    plotOptions: {
                        series: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: [{
                                enabled: true,
                                distance: 20,
                                format: '{point.name}'
                            }, {
                                enabled: true,
                                distance: -15,
                                format: '{point.percentage:.0f}%',
                                style: {
                                    fontSize: '0.9em'
                                }
                            }]
                        }
                    },
                    series: [{
                        name: 'Turnos',
                        colorByPoint: true,
                        innerSize: '75%',
                        data: [{
                            name: '8:00-11:00',
                            y: 23.9
                        }, {
                            name: '11:00-14:00',
                            y: 42.6
                        }, {
                            name: '14:00-17:00',
                            y: 7.2
                        }, {
                            name: '17:00-20:00',
                            y: 26.3
                        }]
                    }]
                });
            });
    </script>
    <div id="statsuser2-{{ $employee->id }}" style="width: 100%; height: 400px;"></div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            Highcharts.chart('statsuser2-{{ $employee->id }}', {
                chart: {
                    type: 'column',
                    backgroundColor: '#f0f9ff',
                },
                title: {
                    text: 'Horas trabajadas vs esperadas'
                },
                xAxis: {
                    categories: [
                        'Mañana',
                        'Tarde',
                    ]
                },
                yAxis: [{
                    min: 0,
                    title: {
                        text: 'Horas'
                    }
                }],
                legend: {
                    shadow: false
                },
                tooltip: {
                    shared: true
                },
                plotOptions: {
                    column: {
                        grouping: false,
                        shadow: false,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: 'Esperadas',
                    color: 'rgba(165,170,217,1)',
                    data: [170, 73],
                    pointPadding: 0.3,
                    pointPlacement: -0.2
                }, {
                    name: 'Trabajadas',
                    color: 'rgba(126,86,134,.9)',
                    data: [145, 50],
                    pointPadding: 0.4,
                    pointPlacement: -0.2
                }]
            });
        });
    </script>
@endif
<script>
    function confirmDeleteSection(event, sectionID) {
        event.preventDefault(); // Evita que se envíe el formulario inmediatamente
        const confirmation = confirm("¿Estás seguro de que deseas eliminar esta sección?");
        if (confirmation) {
            document.getElementById('delete-form-' + sectionID).submit(); // Envía el formulario si el usuario confirma
        }
    }
</script>
