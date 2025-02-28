import './bootstrap';
import Highcharts from 'highcharts';
document.addEventListener("DOMContentLoaded", function () {
    Highcharts.chart('grafico1', {
        chart: { type: 'line' },
        title: { text: 'Horas Trabajadas' },
        xAxis: { categories: ['Enero', 'Febrero', 'Marzo'] },
        series: [{ name: 'Horas', data: [1, 3, 2] }]
    });
    Highcharts.chart('grafico2', {
        chart: { type: 'line' },
        title: { text: 'Cambios de turno solicitados' },
        xAxis: { categories: ['Enero', 'Febrero', 'Marzo'] },
        series: [{ name: 'Horas', data: [5, 2, 1] }]
    });
    Highcharts.chart('grafico3', {
        chart: { type: 'bar' },
        title: { text: 'Días de vacaciones obtenidos' },
        xAxis: { categories: ['Enero', 'Febrero', 'Marzo'] },
        series: [{ name: 'Horas', data: [7, 0, 2] }]
    });
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
    Highcharts.chart('grafico4', {
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
            backgroundColor:
                Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
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
});
