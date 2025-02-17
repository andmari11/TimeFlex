from io import BytesIO
from fastapi.responses import StreamingResponse
import matplotlib.pyplot as plt
import numpy as np
from collections import Counter
import datetime

class Stats:

    def get_example():
        # Crear datos
        x = np.linspace(0, 10, 100)
        y = np.sin(x)

        # Crear el gráfico
        plt.plot(x, y)

        img_io = BytesIO()
        plt.savefig(img_io, format="png")
        img_io.seek(0)

        # Retornar el gráfico como una respuesta HTTP
        return StreamingResponse(img_io, media_type="image/png")


    def generate_holiday_statistics(worker_preferences):
        print("Generando estadísticas de vacaciones...")
        day_count = Counter()

        for preference in worker_preferences:
            current_date = preference.holiday_start
            while current_date <= preference.holiday_end:
                day_count[current_date.date()] += 1
                current_date += datetime.timedelta(days=1)

        print("Días contados:", day_count)
        days = list(day_count.keys())
        counts = list(day_count.values())

        # Ordenar días para el gráfico
        days, counts = zip(*sorted(zip(days, counts)))
        print("Días ordenados para gráfico:", days)

        plt.figure(figsize=(10, 5))
        plt.bar(days, counts, color='blue')
        plt.xlabel('Fechas')
        plt.ylabel('Numero de solicitudes de vacaciones')
        plt.title('Solicitudes de vacaciones por fecha')
        plt.xticks(rotation=45)
        plt.tight_layout()

        img_io = BytesIO()
        plt.savefig(img_io, format='png', bbox_inches='tight')
        img_io.seek(0)
        print("Gráfico generado correctamente.")
        return StreamingResponse(img_io, media_type='image/png')

    def generate_holiday_statistics2(worker_preferences):
            print("Generando estadísticas de vacaciones...")
            day_count = Counter()

            for preference in worker_preferences:
                current_date = preference.holiday_start
                while current_date <= preference.holiday_end:
                    day_count[current_date.date()] += 1
                    current_date += datetime.timedelta(days=1)

            print("Días contados:", day_count)
            days = sorted(day_count.keys())
            counts = [day_count[day] for day in days]

            # Crear una matriz para el mapa de calor (usaremos solo una fila)
            heatmap_data = np.array([counts])  # Matriz 1xN

            fig, ax = plt.subplots(figsize=(10, 2))  # Ajustamos tamaño del gráfico
            cax = ax.imshow(heatmap_data, cmap='coolwarm', aspect="auto")

            # Configurar las etiquetas del eje X con las fechas
            ax.set_xticks(np.arange(len(days)))
            ax.set_xticklabels([day.strftime("%Y-%m-%d") for day in days], rotation=45, ha="right")

            # Eliminar el eje Y (ya que solo hay una fila)
            ax.set_yticks([])

            # Agregar una barra de color (leyenda)
            fig.colorbar(cax, orientation="horizontal", label="Número de solicitudes")

            plt.tight_layout()

            # Guardar la imagen en un buffer y devolverla como una respuesta HTTP
            img_io = BytesIO()
            plt.savefig(img_io, format='png', bbox_inches='tight')
            img_io.seek(0)
            print("Mapa de calor generado correctamente.")
            return StreamingResponse(img_io, media_type='image/png')


    def generate_holiday_statistics3(worker_preferences):
        print("Generando estadísticas de vacaciones...")
        day_count = defaultdict(lambda: [0]*7)  # 7 días por semana

        for preference in worker_preferences:
            start_date = preference.holiday_start
            end_date = preference.holiday_end
            current_date = start_date
            while current_date <= end_date:
                day_count[current_date.strftime("%Y-%W")][current_date.weekday()] += 1
                current_date += datetime.timedelta(days=1)

        # Preparar datos para el mapa de calor
        weeks = sorted(day_count.keys())
        data = np.array([day_count[week] for week in weeks])

        # Crear el mapa de calor
        plt.figure(figsize=(10, 5))
        plt.imshow(data, cmap='hot', interpolation='nearest', aspect='auto')
        plt.colorbar(label='Numero de solicitudes de vacaciones')
        plt.xticks(range(7), ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'])
        plt.yticks(range(len(weeks)), weeks)
        plt.title('Mapa de calor de solicitudes de vacaciones por semana')
        plt.xlabel('Día de la semana')
        plt.ylabel('Semana del año')

        img_io = BytesIO()
        plt.savefig(img_io, format='png', bbox_inches='tight')
        img_io.seek(0)
        print("Mapa de calor generado correctamente.")
        return StreamingResponse(img_io, media_type='image/png')

    def generate_holiday_heatmap_calendar(worker_preferences, year, month):
        print("Generando mapa de calor del calendario de vacaciones...")

        # Inicializar conteo de días
        day_count = Counter()

        # Contar las solicitudes por cada día en las preferencias dadas
        for preference in worker_preferences:
            current_date = preference.holiday_start
            while current_date <= preference.holiday_end:
                if current_date.year == year and current_date.month == month:
                    day_count[current_date.day] += 1
                current_date += datetime.timedelta(days=1)

        # Preparar el calendario del mes
        days_in_month = calendar.monthrange(year, month)[1]
        calendar_matrix = np.zeros(days_in_month)

        # Llenar el calendario con los datos de solicitudes
        for day in range(1, days_in_month + 1):
            calendar_matrix[day - 1] = day_count[day]

        # Reshape la matriz para que tenga una forma semanal (aproximadamente)
        weeks = (days_in_month // 7) + (1 if days_in_month % 7 else 0)
        calendar_matrix = np.pad(calendar_matrix, (0, weeks * 7 - days_in_month), 'constant').reshape((weeks, 7))

        # Crear el mapa de calor
        fig, ax = plt.subplots()
        cax = ax.imshow(calendar_matrix, cmap='coolwarm', aspect='auto')

        # Establecer las etiquetas de los días de la semana
        ax.set_xticklabels([''] + list(calendar.day_abbr))
        ax.set_yticklabels([''])

        # Configurar las etiquetas de las semanas
        ax.set_yticks(np.arange(weeks))

        # Añadir una barra de color para interpretar los valores
        plt.colorbar(cax, ax=ax, orientation='vertical', label='Número de solicitudes de vacaciones')

        plt.title(f'Calendario de solicitudes de vacaciones {year}-{month}')
        plt.xlabel('Día de la semana')
        plt.ylabel('Semana del mes')

        plt.grid(False)  # Desactivar la cuadrícula para evitar confusión visual
        plt.tight_layout()

        # Guardar y enviar el gráfico
        img_io = BytesIO()
        plt.savefig(img_io, format='png', bbox_inches='tight')
        img_io.seek(0)

        print("Actividad terminada")

        return StreamingResponse(img_io, media_type='image/png')
