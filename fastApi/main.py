from fastapi import FastAPI
from pydantic import BaseModel
import httpx
import asyncio


app = FastAPI()

class Params(BaseModel):
    parametro1: str

async def send_post_to_laravel(data):
    async with httpx.AsyncClient() as client:
        print("before response")
        await asyncio.sleep(3)
        response = await client.post("http://timeflex.test/pruebaAPI", json=data)
        print(response)
        return response



@app.post("/api/")
async def root(params: Params):

    laravel_data = {
        "name": params.parametro1,
    }
    asyncio.create_task(send_post_to_laravel(laravel_data))

    return {"message": "Todo correcto, proceso iniciado"}
