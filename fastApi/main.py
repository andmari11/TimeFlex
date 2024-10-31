from fastapi import FastAPI
from pydantic import BaseModel
from z3 import *
import httpx
import asyncio


app = FastAPI()

class Params(BaseModel):
    name: str
    company_id: str
    id: str

@app.post("/api/")
async def root(params: Params):

    input_data = {
        "id":params.id,
        "name": params.name,
        "company_id": params.company_id
    }
    asyncio.create_task(send_post_to_laravel(input_data))

    return {"message": "Todo correcto, proceso iniciado"}




async def send_post_to_laravel(data):

    async with httpx.AsyncClient() as client:

        s=Solver()
        sol = [Int(f"day_{i}") for i in range(7)]

        working_days=[]
        for i in range(7):
            s.add(Or(sol[i]==0, sol[i]==1))

        s.add(Sum(sol)==5)

        if s.check()==sat:
            model = s.model()
            data['scheduleJSON'] = {f"day_{i}": model.eval(sol[i]).as_long() for i in range(7)}
        else:
            data['scheduleJSON'] = {"error": "No solution found"}


        #response = await client.post("http://timeflex.test/pruebaAPI", json=data)
        response = await client.post("http://127.0.0.1:8000/pruebaAPI", json=data)
        print(response)

        return response



