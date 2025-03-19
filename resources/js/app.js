import './bootstrap';
import Highcharts from 'highcharts';
import heatmap from 'highcharts/modules/heatmap';
window.Highcharts = Highcharts;

document.addEventListener("DOMContentLoaded", function () {

    iniciarGraficosPaginaEstadisticas();
    iniciarGraficosBarraLateral();

});

function iniciarGraficosPaginaEstadisticas(){
    if (document.getElementById('grafico1')) {
        Highcharts.chart('grafico1', {
            chart: { type: 'column' }, // Cambiado de 'bar' a 'column'
            title: { text: 'Horas Trabajadas' },
            xAxis: { categories: ['Enero', 'Febrero', 'Marzo'] },
            yAxis: { title: { text: 'Horas' } },
            series: [{ name: 'Horas', data: [158, 143, 172] }]
        });

    }
    if (document.getElementById('grafico2')) {
        Highcharts.chart('grafico2', {
            chart: {
                type: 'line'
            },
            title: {
                text: 'Evolución de la satisfacción del empleado con sus horarios'
            },
            xAxis: {
                categories: ['Enero', 'Febrero', 'Marzo']
            },
            yAxis: {
                title: {
                    text: 'Nivel de Satisfacción'
                },
                min: 0,
                max: 10
            },
            series: [{
                name: 'Satisfacción del Empleado',
                data: [2, 9, 6],
                marker: {
                    symbol: 'circle'
                }
            }, {
                name: 'Media de la Unidad',
                data: [5, 6.75, 4],
                marker: {
                    symbol: 'square'
                }
            }]
        });
    }

    /*
    Highcharts.chart('grafico4', {
        chart: { type: 'line' },
        title: { text: 'Cambios de turno aceptados' },
        xAxis: { categories: ['Enero', 'Febrero', 'Marzo'] },
        series: [{ name: 'Horas', data: [1, 0, 4] }]
    });
    */
    /*
    Highcharts.chart('grafico4', {
        chart: { type: 'line' },
        title: { text: 'Cambios de turno aceptados' },
        xAxis: { categories: ['Enero', 'Febrero', 'Marzo'] },
        yAxis: { title: { text: 'Empleados del Departamento' } },
        series: [
            { name: 'Javier', data: [2,6,1] },
            { name: 'Marta', data: [8,0,1] }
        ]
    });
    */
    if (document.getElementById('grafico3')) {
        Highcharts.chart('grafico3', {
            chart: {
                type: 'bar'
            },
            title: {
                text: 'Evolución de las solicitudes de cambio de turno por mes'
            },
            xAxis: {
                categories: ['Enero', 'Febrero', 'Marzo', 'Abril'],
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
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -40,
                y: 80,
                floating: true,
                borderWidth: 1,
                shadow: true
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'Cambios de turno solicitados',
                data: [12, 6, 3, 10]
            }, {
                name: 'Cambios de turno obtenidos',
                data: [5, 2, 3, 5]
            }]
        });
        if (document.getElementById('grafico4')) {
            Highcharts.chart('grafico4', {
                chart: {type: 'column'},
                title: {text: 'Días de vacaciones obtenidos por mes'},
                xAxis: {categories: ['Enero', 'Febrero', 'Marzo']},
                yAxis: { title: { text: 'Días' } },
                series: [{name: 'Días', data: [7, 1, 4]}]
            });
        }
    }
}
function iniciarGraficosBarraLateral(){
    if (document.getElementById('statsuser')){
        Highcharts.chart('statsuser', {
            chart: {
                type: 'pie',
                custom: {},
                events: {
                    render() {
                        const chart = this,
                            series = chart.series[0];
                        let customLabel = chart.options.chart.custom.label;

                        if (!customLabel) {
                            customLabel = chart.options.chart.custom.label =
                                chart.renderer.label(
                                    'Total<br/>' +
                                    '<strong>2 877 820</strong>'
                                )
                                    .css({
                                        color: '#000',
                                        textAnchor: 'middle'
                                    })
                                    .add();
                        }

                        const x = series.center[0] + chart.plotLeft,
                            y = series.center[1] + chart.plotTop -
                                (customLabel.attr('height') / 2);

                        customLabel.attr({
                            x,
                            y
                        });
                        // Set font size based on chart diameter
                        customLabel.css({
                            fontSize: `${series.center[2] / 12}px`
                        });
                    }
                }
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            title: {
                text: 'Distribución de turnos'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.0f}%</b>'
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    borderRadius: 8,
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
                    }],
                    showInLegend: true
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
    }

    if (document.getElementById('statsuser2')){
        Highcharts.chart('statsuser2', {
            chart: {
                type: 'column'
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
    }


}
