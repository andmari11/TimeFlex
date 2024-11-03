from fastapi import FastAPI
from pydantic import BaseModel
from typing import List, Optional
from z3 import *
import httpx
import asyncio
import json



app = FastAPI()


class Params(BaseModel):
    id: int
    company_id: int
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



def nWork (i,j):
    return "works"+str(i)+"_"+str(j)

async def send_post_to_laravel(data):


    async with httpx.AsyncClient() as client:

        users = data.get("users", [])
        num_users = len(users)
        num_days = 7
        min_working_days = 5
        max_working_days = 5

        ids=[]
        vacations =[]

        for i in range(num_users):
            ids.append(users[i].get('user_id'))

            user_dictionary = users[i].get('request', {}).get('holidays', [])
            user_vacations=[]
            for day in range(num_days):
                if day in user_dictionary:
                    user_vacations.append(1)   #  vacaciones
                else:
                    user_vacations.append(0)   # no vacaciones
            vacations.append(user_vacations)
        s=Solver()

        sol=[]
        for i in range(num_users):
            worker=[]
            for j in range(num_days):
                worker.append(Int(nWork(i,j)))
                s.add(Or(worker[j]==0, And(worker[j]==1, vacations[i][j]==0)))


            sol.append(worker)
            s.add(Sum(sol[i])>=min_working_days)
            s.add(Sum(sol[i])<=max_working_days)




        if s.check()==sat:
            model = s.model()
            data['status'] = "success"
            data['scheduleJSON'] = {}

            for i in range(num_users):
                user_schedule = []
                for j in range(num_days):
                    user_schedule.append(model.eval(sol[i][j]).as_long())
                data['scheduleJSON'][f"user_{ids[i]}"] = user_schedule

        else:
            data['status'] = "failed"



        response = await client.post("http://timeflex.test/pruebaAPI", json=data)
        #response = await client.post("http://127.0.0.1:8000/pruebaAPI", json=data)
        print(response)

        return response



