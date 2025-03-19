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

                let date = new Date(Date.UTC(2025, 2, 1)); // Marzo de 2025
                let week = 0;

                while (date.getMonth() === 2) { // Mientras sea marzo
                    let dayIndex = date.getDay() - 1;
                    if (dayIndex === -1) dayIndex = 6; // Ajuste para que el lunes sea index 0 y el domingo 6

                    const value = Math.floor(Math.random() * 10);
                    data.push({
                        x: dayIndex,
                        y: week,
                        value: value,
                        day: monthDay
                    });

                    if (date.getDay() === 0) week++; // Incrementar la semana cada domingo
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
                                return this.point.options.day; // Mostrar el número del día en la celda
                            }
                        }
                    }]
                });
            });
        </script>
        <div id="mejoresPeoresDiasMes" style="width:50%; height:400px;"></div>
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
                            labels: {
                                format: '{value} Marzo',
                                style: {
                                    fontSize: '14px'
                                }
                            },
                            tickInterval: 1,
                            minPadding: 0.2,
                            maxPadding: 0.2,
                            lineWidth: 0 // Ocultar línea del eje X
                        },
                        yAxis: {
                            visible: false
                        },
                        legend: {
                            enabled: false
                        },
                        tooltip: {
                            formatter: function () {
                                return `<b>${this.point.percentage} de turnos libres</b><br>Día ${this.point.name} de Marzo`;
                            }
                        },
                        series: [{
                            name: 'Días más y menos libres',
                            data: [
                                { name: '8', percentage: '62%', color: '#00cc00' },  // Verde más oscuro
                                { name: '6', percentage: '51%', color: '#6eea8e' },
                                { name: '11', percentage: '42%', color: '#ccff99' },
                                { name: '9', percentage: '33%', color: '#e6f0DC' },  // Verde más claro
                                { name: '17', percentage: '12%', color: '#ff9ea2' }, // Rojo más claro
                                { name: '4', percentage: '9%', color: '#f1666d' },
                                { name: '27', percentage: '3%', color: '#dc143c' },
                                { name: '12', percentage: '1%', color: '#ed2024' }  // Rojo más oscuro
                            ],
                            marker: {
                                symbol: 'M -10,-10 a 10,10 0 1,0 20,0 a 10,10 0 1,0 -20,0',
                                radius: 20, // Ajusta el tamaño del óvalo
                                lineWidth: 2,
                                lineColor: '#000'
                            }
                            ,
                            lineWidth: 12,
                            lineColor: '#808080',
                            dataLabels: {
                                enabled: true,
                                allowOverlap: true,
                                formatter: function () {
                                    return `<span style="font-size: 16px; font-weight: bold;">${this.point.percentage}</span>`; // Solo el %
                                },
                                useHTML: true,
                                style: {
                                    fontSize: '16px',
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
