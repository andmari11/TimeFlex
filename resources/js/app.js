import './bootstrap';
import Highcharts from 'highcharts';
import heatmap from 'highcharts/modules/heatmap';
import Timeline from 'highcharts/modules/timeline';
import Gantt from 'highcharts/modules/gantt';
import Accessibility from 'highcharts/modules/accessibility';
import HighchartsMore from 'highcharts/highcharts-more';
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
                type: 'column'
            },
            title: {
                text: 'Evolución de las solicitudes de cambio de turno por mes'
            },
            xAxis: {
                categories: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                crosshair: true,
                gridLineWidth: 0
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Número de cambios de turno'
                },
                gridLineWidth: 1
            },
            tooltip: {
                shared: true,
                valueSuffix: ' cambios de turno'
            },
            plotOptions: {
                column: {
                    borderRadius: 5,
                    dataLabels: {
                        enabled: true
                    },
                    groupPadding: 0.1,
                    pointPadding: 0.1
                }
            },
            legend: {
                layout: 'horizontal',
                align: 'center',
                verticalAlign: 'bottom',
                borderWidth: 0
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'Cambios de turno solicitados',
                data: [12, 6, 3, 10, 8, 9, 7, 11, 6, 4, 5, 8]
            }, {
                name: 'Cambios de turno obtenidos',
                data: [5, 2, 3, 5, 6, 6, 5, 7, 3, 3, 4, 6]
            }]
        });
    }
}
async function iniciarGraficosBarraLateral() {
    if (document.getElementById('statsuser')) {
        const res = await fetch('/shift-distribution');
        const data = await res.json();

        const seriesData = Object.entries(data).map(([label, count]) => ({
            name: label,
            y: count
        }));

        Highcharts.chart('statsuser', {
            chart: {
                type: 'pie'
            },
            title: {
                text: 'Distribución de turnos'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.0f}%</b>'
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            plotOptions: {
                pie: {
                    innerSize: '75%',
                    dataLabels: {
                        enabled: true,
                        format: '{point.name}: {point.y}'
                    }
                }
            },
            series: [{
                name: 'Turnos',
                colorByPoint: true,
                data: seriesData
            }]
        });
    }
}

