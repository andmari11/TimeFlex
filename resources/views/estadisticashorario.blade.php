@vite(['resources/js/app.js'])

<x-layout :title="'Estadísticas de horario'">
    <x-page-heading>Bienvenido a la página de estadísticas de horario</x-page-heading>
    <div style="display: flex; justify-content: space-around; align-items: center; flex-wrap: wrap;">
        <div id="calendarioHeatmap" style="width:100%; height:100%;"></div>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const days = ['Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab', 'Dom'];
                const weeks = ['Semana 1', 'Semana 2', 'Semana 3', 'Semana 4', 'Semana 5', 'Semana 6'];
                const data = [];
                let monthDay = 1;

                let date = new Date(Date.UTC(2025, 2, 1)); // marzo de 2025
                let week = 0;

                while (date.getMonth() === 2) { // Mmarzo
                    let dayIndex = date.getDay() - 1;
                    if (dayIndex === -1) dayIndex = 6; // lunes index 0 y domingo 6

                    const value = Math.floor(Math.random() * 10);
                    data.push({
                        x: dayIndex,
                        y: week,
                        value: value,
                        day: monthDay
                    });

                    if (date.getDay() === 0) week++; // pasar a la sig semana cada domingo
                    date.setDate(date.getDate() + 1);
                    monthDay++;
                }

                Highcharts.chart('calendarioHeatmap', {
                    chart: {
                        type: 'heatmap',
                        marginTop: 40,
                        marginBottom: 80,
                        plotBorderWidth: 1
                    },
                    title: {
                        text: 'Días más demandados en marzo de 2025'
                    },
                    xAxis: {
                        categories: days,
                        title: null
                    },
                    yAxis: {
                        categories: weeks,
                        title: null,
                        reversed: true
                    },
                    colorAxis: {
                        min: 0,
                        minColor: '#FFFFFF',
                        maxColor: Highcharts.getOptions().colors[0]
                    },
                    legend: {
                        align: 'right',
                        layout: 'vertical',
                        margin: 0,
                        verticalAlign: 'top',
                        y: 25,
                        symbolHeight: 280
                    },
                    tooltip: {
                        formatter: function () {
                            return `<b>${this.series.yAxis.categories[this.point.y]}, ${days[this.point.x]}:</b> Valor: ${this.point.value}, Día: ${this.point.options.day}`;
                        }
                    },
                    series: [{
                        name: 'Actividad diaria',
                        borderWidth: 1,
                        data: data,
                        dataLabels: {
                            enabled: true,
                            color: '#000000',
                            formatter: function () {
                                return this.point.options.day; // nº de día en la celda
                            }
                        }
                    }]
                });
            });
        </script>
        <div id="mejoresPeoresDiasMes" style="width:100%; height:80%;"></div>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                if (document.getElementById('mejoresPeoresDiasMes')) {
                    Highcharts.chart('mejoresPeoresDiasMes', {
                        chart: {
                            type: 'timeline',
                            backgroundColor: '#f8f9fa',
                            borderRadius: 20,
                            marginTop: 40,
                            marginBottom: 80
                        },
                        title: {
                            text: 'Días más y menos libres de marzo',
                            align: 'center'
                        },
                        xAxis: {
                            type: 'category',
                            categories: ['8 de Marzo', '6 de Marzo', '11 de Marzo', '9 de Marzo', '17 de Marzo', '4 de Marzo', '27 de Marzo', '12 de Marzo'], // Días en el eje X
                            labels: {
                                style: {
                                    fontSize: '14px',
                                    fontWeight: 'bold'
                                }
                            },
                            tickInterval: 1,
                            lineWidth: 0
                        },
                        yAxis: {
                            visible: false
                        },
                        legend: {
                            enabled: false
                        },
                        tooltip: {
                            formatter: function () {
                                return `<b>${this.point.day} de Marzo</b> - ${this.point.name} turnos libres`;
                            }
                        },
                        series: [{
                            name: 'Días más y menos libres',
                            data: [
                                { name: '62%', day: '8', color: '#00cc00' },
                                { name: '51%', day: '6', color: '#6eea8e' },
                                { name: '42%', day: '11', color: '#ccff99' },
                                { name: '33%', day: '9', color: '#e6f0DC' },
                                { name: '12%', day: '17', color: '#ff9ea2' },
                                { name: '9%', day: '4', color: '#f1666d' },
                                { name: '3%', day: '27', color: '#dc143c' },
                                { name: '1%', day: '12', color: '#ed2024' }
                            ],
                            marker: {
                                symbol: 'rect',
                                width: 225,
                                height: 60,
                                lineWidth: 0
                            },
                            lineWidth: 5,
                            lineColor: '#d3d3d3', // oculto la línea poniendola en color blanco
                            dataLabels: {
                                enabled: true,
                                allowOverlap: true,
                                formatter: function () {
                                    return `<b>${this.point.name}</b>`;  //  %
                                },
                                style: {
                                    fontSize: '18px',
                                    fontWeight: 'bold',
                                    color: '#000',
                                    textOutline: 'none'
                                }
                            }
                        }]
                    });
                }
            });




        </script>
    </div>
</x-layout>
