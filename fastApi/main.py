from fastapi import FastAPI
from pydantic import BaseModel
from typing import List, Optional
from z3 import *
import httpx
import asyncio
import json



app = FastAPI()


class Params(BaseModel):
    id: str
    company_id: str	
    name: str
    usersJSON: str



@app.post("/api/")
async def root(params: Params):

    input_data = {
        "id":params.id,
	"users":json.loads(params.usersJSON)
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
            data['scheduleJSON'] = {
        	"process_status": "success",
        	**{f"day_{i}": model.eval(sol[i]).as_long() for i in range(7)}
	    }
        else:
            data['scheduleJSON'] = {"process_status": "failed"}


        #response = await client.post("http://timeflex.test/pruebaAPI", json=data)
        response = await client.post("http://127.0.0.1:8000/pruebaAPI", json=data)
        print(response)

        return response



