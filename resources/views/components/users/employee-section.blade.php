@vite(['resources/js/app.js'])
@props(['employee', 'showGraphs' => true])
@php
    use Carbon\Carbon;
    $mesActual = Carbon::now()->format('F');
    // obtenemos el horario para el mes actual de la seccion del empleado seleccionado
    $horario = $employee->section->schedules()->where('name', 'like', '%' . $mesActual . '%')
        ->latest()
        ->first();
@endphp

<div class="p-4 bg-blue-50 shadow rounded-xl my-1 {{ !isset($showGraphs) or $showGraphs ? 'my-1' : 'my-5' }} w-full max-w-sm mx-auto ">
    <div class="flex items-center justify-center">
        <img class="h-20 w-20 rounded-full" src="https://static.vecteezy.com/system/resources/previews/004/274/186/non_2x/person-icon-user-interface-icon-silhouette-of-man-simple-symbol-a-glyph-symbol-in-your-web-site-design-logo-app-ui-webinar-video-chat-ect-vector.jpg" alt="">
    </div>
    <div class="flex flex-col items-center">
        <h3 class="text-xl text-gray-700 my-2 font-bold">{{ $employee->name }}</h3>
        <h3 class="text-lg text-gray-700 my-2 bg-white/10 hover:bg-white/35 px-3 py-1 rounded-xl">{{ $employee->section->name }}</h3>
        <a class="text-lg text-gray-700 my-2 hover:underline">{{ $employee->email }}</a>
        <div class="min-h-16 flex items-center justify-center mb-3">
            @if($horario)
                <a href="/horario/personal/{{ $horario->id }}" class="bg-sky-700 hover:bg-sky-900 px-4 py-3 rounded-xl text-xl text-white font-bold">Ver horario</a>
            @endif
        </div>
    </div>
    @if(auth()->user()->role === 'admin')
        <a href="{{ url('/users/'.$employee->id.'/edit') }}?redirect_to={{ urlencode(url()->current()) }}" class="bg-blue-500 hover:bg-blue-400 px-2 py-1 rounded-xl text-xs text-white">Editar</a>
        @if($employee->role != 'admin')
            <button onclick="confirmDeleteSection(event, {{ $employee->id }})" class="bg-red-600 hover:bg-red-400 px-2 py-1 rounded-xl text-xs text-white">Eliminar</button>
        @endif
        <form method="POST" action="/users/{{$employee->id}}/delete" id="delete-form-{{ $employee->id }}" class="hidden">
            @csrf
            @method('DELETE')
            <input type="hidden" name="redirect_to" value="{{ url()->current() }}">
        </form>
    @endif
</div>

@if ($showGraphs)
    <div class="p-4 bg-blue-50 shadow rounded-xl my-6 w-full max-w-sm mx-auto">
        <div class="rounded-lg my-6 px-2" id="statsuser-{{ $employee->id }}" style="width: 100%; aspect-ratio: 1 / 1;"></div>
        <script>
            window.cargarGraficoEmpleado = function (id) {
                fetch(`/user/${id}/shift-distribution`) // sacamos los datos del endpoint definido
                    .then(res => res.json())
                    .then(data => {
                        const entradas = Object.entries(data);
                        const total = entradas.reduce((acc, [, value]) => acc + value, 0);

                        if (total < 1) {
                            document.getElementById(`statsuser-${id}`).innerHTML = '<div style="text-align:center; color:#666; font-weight:bold; font-size:1rem;">No dispones de turnos asignados</div>';
                            return;
                        }
                        const seriesData = Object.entries(data).map(([name, value]) => ({ name, y: value }));

                        const chart = Highcharts.chart(`statsuser-${id}`, {
                            chart: {
                                type: 'pie',
                                backgroundColor: '#f0f9ff',
                                events: {
                                    render() {
                                        const series = this.series[0];
                                        const total = series.points.reduce((acc, p) => acc + p.y, 0);
                                        const center = series.center;
                                        const cx = this.plotLeft + center[0];
                                        const cy = this.plotTop + center[1];

                                        if (this.customLabelGroup) this.customLabelGroup.destroy();
                                        this.customLabelGroup = this.renderer.g().add();

                                        const label1 = this.renderer.text('Total', 0, 0).css({
                                            fontSize: '16px',
                                            fontWeight: 'bold',
                                            color: '#333'
                                        }).add(this.customLabelGroup);

                                        const label2 = this.renderer.text(total.toLocaleString(), 0, 0).css({
                                            fontSize: '20px',
                                            fontWeight: 'bold',
                                            color: '#000'
                                        }).add(this.customLabelGroup);

                                        const bbox1 = label1.getBBox();
                                        const bbox2 = label2.getBBox();

                                        label1.attr({ x: cx - bbox1.width / 2, y: cy - 10 });
                                        label2.attr({ x: cx - bbox2.width / 2, y: cy + 15 });
                                    }
                                }
                            },
                            title: {
                                text: 'Distribución de turnos',
                                align: 'center',
                                verticalAlign: 'top',
                                y: 60
                            },
                            tooltip: {
                                pointFormat: '{series.name}: <b>{point.percentage:.0f}%</b>'
                            },
                            plotOptions: {
                                pie: {
                                    size: '55%',
                                    innerSize: '60%',
                                    borderRadius: 6,
                                    dataLabels: {
                                        enabled: true,
                                        useHTML: true,
                                        formatter: function () { // pongo aqui el contenido del grafico y su estilo
                                            return `
                                                <div style="text-align:center;">
                                                    <span style="font-weight:bold; color:black; font-size:1.25em;">${this.point.name}</span><br>
                                                    <span style="color:white; font-size:0.9em;">${Math.round(this.percentage)}%</span>
                                                </div>
                                            `;
                                        },
                                        style: {
                                            textOutline: '1px contrast'
                                        }
                                    }
                                }
                            },
                            series: [{
                                name: 'Turnos',
                                colorByPoint: true,
                                data: seriesData
                            }]
                        });
                    })
                    .catch(error => {   // mensaje de error por si algo falla
                        console.error("Error al cargar el gráfico:", error);
                        document.getElementById(`statsuser-${id}`).innerHTML = '<p style="color:red;">Error al cargar el gráfico</p>';
                    });
            }

            document.addEventListener("DOMContentLoaded", () => {
                cargarGraficoEmpleado({{ $employee->id }});
            });
        </script>
    </div>
    <div class="p-4 bg-blue-50 shadow rounded-xl my-6 w-full max-w-sm mx-auto">
        <div class="rounded-lg my-6 px-2" id="statsuser2-{{ $employee->id }}" style="width: 100%; height: 400px;"></div>
        <script>
            function cargarGraficoHorasVsEsperadas(id) {
                fetch(`/user/${id}/actual-vs-expected`)
                    .then(res => res.json())
                    .then(data => {
                        Highcharts.chart(`statsuser2-${id}`, {
                            chart: {
                                type: 'column',
                                backgroundColor: '#f0f9ff',
                            },
                            title: {
                                text: 'Horas trabajadas vs esperadas'
                            },
                            xAxis: {
                                categories: ['Mañana', 'Tarde', 'Noche']
                            },
                            yAxis: {
                                min: 0,
                                title: {
                                    text: 'Horas'
                                }
                            },
                            tooltip: {
                                shared: true
                            },
                            plotOptions: {
                                column: {
                                    grouping: true,
                                    shadow: false,
                                    borderWidth: 0
                                }
                            },
                            series: [
                                {
                                    name: 'Esperadas',
                                    color: 'rgba(165,170,217,1)',
                                    data: [
                                        data.expected.morning,
                                        data.expected.afternoon,
                                        data.expected.night
                                    ]
                                },
                                {
                                    name: 'Trabajadas',
                                    color: 'rgba(126,86,134,.9)',
                                    data: [
                                        data.worked.morning,
                                        data.worked.afternoon,
                                        data.worked.night
                                    ]
                                }
                            ]
                        });
                    })
                    .catch(err => {
                        console.error("Error al cargar el gráfico de horas:", err);
                        document.getElementById(`statsuser2-${id}`).innerHTML =
                            '<p style="color:red;">Error al cargar el gráfico</p>';
                    });
            }

            document.addEventListener("DOMContentLoaded", function () {
                cargarGraficoHorasVsEsperadas({{ $employee->id }});
            });
        </script>
    </div>


@endif

<script>
    function confirmDeleteSection(event, sectionID) {
        event.preventDefault();
        const confirmation = confirm("¿Estás seguro de que deseas eliminar este empleado?");
        if (confirmation) {
            document.getElementById('delete-form-' + sectionID).submit();
        }
    }
</script>
