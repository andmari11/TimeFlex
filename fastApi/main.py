from fastapi import FastAPI

app = FastAPI()
a = 0  # Contador global

@app.get("/api/")
async def root():
    global a  # Para modificar la variable global 'a'
    a += 1
    return {"message": "¡Hola, FastAPI está funcionando en Linux!", "llamadas": a}

