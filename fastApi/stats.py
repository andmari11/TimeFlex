from io import BytesIO
from fastapi.responses import StreamingResponse
import matplotlib.pyplot as plt
import numpy as np

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