from fastapi import FastAPI, Request, HTTPException
from pydantic import BaseModel
from z3 import *
from datetime import datetime, timedelta
import httpx
import asyncio
import json
from shift import *
from workerPreference import *
from stats import *
from typing import List, Optional

app = FastAPI()

class StatsData(BaseModel):
    id: int

class ScheduleData(BaseModel):
    id: int
    section_id: int
    name: str
    usersJSON: str
    shiftsJSON: str
'''
@app.post("/api/stats")

async def generate_stats(params: StatsData):
    img=Stats.get_example()
    return img
'''

@app.post("/api/stats")
async def vacation_stats(params: StatsData):
    try:
        print("Llamando a procesar preferencias...")
        preferences = process_worker_preferences('pruebas_stats/preferencias_vacaciones.txt')
        #stats_instance = Stats()  # Crear una instancia de Stats si es un método de instancia
        #return stats_instance.generate_holiday_statistics(preferences)  # Llama al método
        img=Stats.generate_holiday_statistics2(preferences)
        #img=generate_holiday_heatmap_calendar(preferences, 2024,12)
        return img
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

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

        min_working_days=5
        max_working_days=6
        workers = process_worker_preferences(data.get("workers", []))
        shifts = process_shifts_from_json(data.get('shifts', []))
        n_shifts = len(shifts)
        n_workers = len(workers)

        s=Solver()
        sol=[]

        for i in range(n_workers):
            worker_takes_shift=[]
            for j in range(n_shifts):
                worker_takes_shift.append(Int(nWork(i,j)))
                holiday_constraints = []
                for holiday in workers[i].holidays:
                    holiday_constraints.append(And(
                        shifts[j].start.date() != holiday.date(),
                        shifts[j].end.date() != holiday.date()))

                    print(f"Checking if shift {shifts[j].shift_id} on {shifts[j].start.date} to {shifts[j].end.date} conflicts with holiday on {holiday.date}")
                # coge ese día de vacaciones o trabaja y no ha pedido vacación
                s.add(Or(worker_takes_shift[j]==0, And(worker_takes_shift[j]==1, *holiday_constraints )))


            sol.append(worker_takes_shift)
            s.add(Sum(sol[i])>=min_working_days)
            s.add(Sum(sol[i])<=max_working_days)



        solution_to_send={}
        solution_to_send['id']=data['id']

        if s.check()==sat:
            model = s.model()
            solution_to_send['status'] = "success"
            solution_to_send['scheduleJSON'] = {}

            for i in range(n_workers):
                user_schedule = []
                for j in range(n_shifts):
                    if model.eval(sol[i][j]).as_long() != 0:
                        user_schedule.append(shifts[j].shift_id)

                solution_to_send['scheduleJSON'][workers[i].user_id] = user_schedule


        else:
            solution_to_send['status'] = "failed"

        try:
            print(json.dumps(solution_to_send, indent=4))

            response = await client.post("http://timeflex.test/fastapi-schedule", json=solution_to_send)
            #response = await client.post("http://127.0.0.1:8000/fastapi-schedule", json=solution_to_send)

            print(response.json())
        except Exception as e:
            print(f"Error during POST request: {e}")
            response = None


        return response






