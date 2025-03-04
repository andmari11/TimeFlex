@vite(['resources/js/app.js'])

<x-layout :title="'Estadisticas'">
    <x-page-heading>Bienvenido a la página de estadísticas</x-page-heading>
    <div style="display: flex; justify-content: space-around; align-items: center; flex-wrap: wrap;">
        <div id="grafico1" style="width: 50%; height: 400px;"></div>
        <div id="grafico2" style="width: 50%; height: 400px;"></div>
    </div>
    <div style="display: flex; justify-content: space-around; align-items: center; flex-wrap: wrap;">
        <div id="grafico3" style="width: 50%; height: 400px;"></div>
        <div id="grafico4" style="width: 50%; height: 400px;"></div>
    </div>
    <div style="display: flex; justify-content: space-around; align-items: center; flex-wrap: wrap;">
        <div id="grafico6" style="width: 50%; height: 400px;"></div>
    </div>
</x-layout>
