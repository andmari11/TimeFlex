from fastapi import FastAPI
from pydantic import BaseModel
from z3 import *
import httpx
import asyncio
import json
from model.shift import *
from model.workerPreference import *
from optimize1 import *
import logging
import time
import tracemalloc

app = FastAPI()

class ScheduleData(BaseModel):
    id: int
    section_id: int
    name: str
    usersJSON: str
    shiftsJSON: str
    minHoursPerWorker: int
    maxHoursPerWorker: int
    minShiftsPerWorker: int
    maxShiftsPerWorker: int



@app.post("/api/schedule")
async def root(params: ScheduleData):

    input_data = {
        "id":params.id,
	    "workers":json.loads(params.usersJSON),
        "shifts": json.loads(params.shiftsJSON),
        "minHoursPerWorker": params.minHoursPerWorker,
        "maxHoursPerWorker": params.maxHoursPerWorker,
        "minShiftsPerWorker": params.minShiftsPerWorker,
        "maxShiftsPerWorker": params.maxShiftsPerWorker,
    }
    asyncio.create_task(send_schedule(input_data))

    return {"message": "Todo correcto, proceso iniciado"}



def nWork (i,j):
    return "user"+str(i)+"works"+str(j)

async def send_schedule(data):
    async with httpx.AsyncClient() as client:

        logging.basicConfig(filename="logs/app.log", level=logging.DEBUG, format="%(asctime)s - %(levelname)s - %(message)s")
        logging.debug(f"Received data: {data}")
        start_time = time.perf_counter()
        tracemalloc.start()


        solution_to_send = optimize(data, logging)

        end_time = time.perf_counter()
        elapsed_time = end_time - start_time
        current, peak = tracemalloc.get_traced_memory()
        tracemalloc.stop()
        info = (f"Elapsed time: {elapsed_time:.2f} seconds\n"
                 f"Current memory usage: {current / 10**6:.2f} MB; Peak: {peak / 10**6:.2f} MB\n")
        logging.info(info)
        if "message" not in solution_to_send or not solution_to_send["message"]:
            solution_to_send["message"] = [info]
        else:
            solution_to_send["message"].append(info)

        try:
            response = await client.post("http://timeflex.test/fastapi-schedule", json=solution_to_send)
            #response = await client.post("http://127.0.0.1:8000/fastapi-schedule", json=solution_to_send)

            logging.debug(f"Solution to send: {json.dumps(solution_to_send, indent=4)}\n")
            logging.info(
                f"Response status code: {response.status_code}\n"
                f"Response headers: {response.headers}\n"
                f"Response content: {response}"
            )
        except Exception as e:
            logging.error(f"Error during POST request: {e}", exc_info=True)
            response = None


        return response






