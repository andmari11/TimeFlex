from fastapi import FastAPI, Request
from pydantic import BaseModel
from z3 import *
from datetime import datetime, timedelta
import httpx
import asyncio
import json
from model.shift import *
from model.workerPreference import *
from stats import *
from typing import List, Optional
from optimize1 import *


app = FastAPI()

class StatsData(BaseModel):
    id: int


@app.post("/api/stats")

async def root(params: StatsData):
    img=Stats.get_example()
    return img

class ScheduleData(BaseModel):
    id: int
    section_id: int
    name: str
    usersJSON: str
    shiftsJSON: str



@app.post("/api/schedule")
async def root(params: ScheduleData):

    input_data = {
        "id":params.id,
	    "workers":json.loads(params.usersJSON),
        "shifts": json.loads(params.shiftsJSON)
    }
    asyncio.create_task(send_schedule(input_data))

    return {"message": "Todo correcto, proceso iniciado"}



def nWork (i,j):
    return "user"+str(i)+"works"+str(j)

async def send_schedule(data):
    async with httpx.AsyncClient() as client:

        solution_to_send = optimize(data)

        try:
            print(json.dumps(solution_to_send, indent=4))

            response = await client.post("http://timeflex.test/fastapi-schedule", json=solution_to_send)
            #response = await client.post("http://127.0.0.1:8000/fastapi-schedule", json=solution_to_send)

            print(f"Response status code: {response.status_code}")
            print(f"Response headers: {response.headers}")
            print(f"Response content: {response.json()}")
        except Exception as e:
            print(f"Error during POST request: {e}")
            response = None


        return response






