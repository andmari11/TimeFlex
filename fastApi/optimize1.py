from z3 import *
from model.shift import *
from model.workerPreference import *
import json

PREFERRED_SHIFTS_WEIGHT = 10
HOLIDAYS_WEIGHT = -20


def read_file(file_path):
    with open(file_path, 'r', encoding='utf-8') as file:
        return json.load(file)
    
def satisfaction_score(all_assigned_shifts, workers, shifts):
    scores = []
    max_score = 0
    min_score = float('inf')
    
    for i in range(len(workers)):
        holidays_worked = 0
        preferred_shifts = 0
        for j in range(len(all_assigned_shifts[i])):
            if m.eval(all_assigned_shifts[i][j]):
                holidays_worked += (shifts[j].start.date() == holiday.date() or shifts[j].end.date() == holiday.date())
                preferred_shifts += (shifts[j].type in workers[i].preferred_shift_types)
        print(f"Worker {workers[i].user_id} worked {holidays_worked} holidays and {preferred_shifts} preferred shifts")
        score = (holidays_worked * HOLIDAYS_WEIGHT) + (preferred_shifts * PREFERRED_SHIFTS_WEIGHT)
        scores.append(score)

    # normalizar satisfabilidad
    
    return normalize_satisfaction(scores)


def normalize_satisfaction(satisfaction_scores):
    max_score = max(satisfaction_scores)
    min_score = min(satisfaction_scores)

    normalized_scores = []
    for score in satisfaction_scores:
        if max_score == min_score:
            normalized_scores.append(100) 
        else:
            normalized_score = 100 * (score - min_score) / (max_score - min_score)
            normalized_scores.append(normalized_score)
    
    return normalized_scores


workers = process_worker_preferences(read_file('z3_pruebas/preferencias.txt'))
shifts = process_shifts_from_json(read_file("z3_pruebas/turnos.txt"))

n_shifts = len(shifts)
n_workers = len(workers)

def nWork (i,j):
    return "worker"+str(i)+"works"+str(j)
def bool2int(b):
    return If(b, 1, 0)
def addsum(a):
    if len(a) == 0:
        return 0
    elif len(a) == 1:
        return a[0]
    else :
        x = a.pop()
        return x + addsum(a) 

def countHours(shift):
    return (shift.end - shift.start).total_seconds() / 3600 

s = Optimize()

#creamos solucion
all_workers_shifts=[]
for i in range(n_workers):
    worker_shifts = []
    for j in range(n_shifts):
        worker_shifts.append(Bool(nWork(workers[i].user_id, shifts[j].shift_id)))
    all_workers_shifts.append(worker_shifts)


#comprobamos cuantos turnos trabaja cada trabajador
for i in range(n_workers):
    worker_takes_shift=[]
    for j in range(n_shifts):
        worker_takes_shift.append(bool2int(all_workers_shifts[i][j]))
    s.add(addsum(worker_takes_shift) <= 4)


#comprobamos cuantos horas trabaja cada trabajador
for i in range(n_workers):
    worker_hours=[]
    for j in range(n_shifts):
        worker_hours.append(If(all_workers_shifts[i][j], countHours(shifts[j]), 0))
    s.add(addsum(worker_hours) <= 40)

#comprobamos que cada turno tiene suficientes trabajadores
for i in range(n_shifts):
    workers_in_shift = []
    for j in range(n_workers):
        workers_in_shift.append(bool2int(all_workers_shifts[j][i]))
    s.add(addsum(workers_in_shift) >= shifts[i].users_needed)


#comprobamos que los trabajadores no trabajen en sus días de vacaciones
for i in range(n_workers):
    for j in range(n_shifts):
        for holiday in workers[i].holidays:
            holiday_shift=(Or(
                shifts[j].start.date() == holiday.date(), 
                shifts[j].end.date() == holiday.date()))
            s.add_soft(Or(all_workers_shifts[i][j]==False, And(all_workers_shifts[i][j], Not(holiday_shift))))

#tipo de turno ej:(mañana:0, tarde:1, noche:2)
for i in range(n_workers):
    for j in range(n_shifts):
        s.add_soft(Implies(all_workers_shifts[i][j], shifts[j].type in workers[i].preferred_shift_types))


# calcular la satisfacción de cada trabajador
this_calendar_satisfaction = []
for i in range(n_workers):
    holidays_worked = []
    preferred_shifts = []
    for j in range(n_shifts):
        holidays_worked.append(If(Or(shifts[j].start.date() == holiday.date(), shifts[j].end.date() == holiday.date()), bool2int(all_workers_shifts[i][j]), 0))
        preferred_shifts.append(If(shifts[j].type in workers[i].preferred_shift_types, bool2int(all_workers_shifts[i][j]), 0))

    worker_satisfaction=(addsum(holidays_worked) * HOLIDAYS_WEIGHT + addsum(preferred_shifts) * PREFERRED_SHIFTS_WEIGHT)
    this_calendar_satisfaction.append(worker_satisfaction)

#calcular la satisfacción media teniendo en cuenta calendarios anteriores
all_averages=[]
for i in range(n_workers):
    all_averages.append((sum(workers[i].past_satisfaction) + this_calendar_satisfaction[i]) / (len(workers[i].past_satisfaction) + 1))
    

#calcular la desviación de la satisfacción media
avg_satisfaction = Sum(all_averages) / n_workers
deviations=[]
for i in range(n_workers):
    deviations.append(avg_satisfaction-all_averages[i])
s.minimize(Sum(deviations))

#Imrpimir resultados
shift_types = {0: 'm', 1: 't', 2: 'n'}
if s.check() == sat:
    m = s.model()
    print("\n---------- Horario por empleado ----------\n")
    for i in range(n_workers):
        worker_schedule = []
        for j in range(n_shifts):
            if m.eval(all_workers_shifts[i][j]):
                worker_schedule.append((shifts[j].start.date(), shift_types[shifts[j].type]))
                
        print(f"Worker {workers[i].user_id} works shifts {worker_schedule}")

    print("\n---------- Horario por turno ----------\n")
    for j in range(n_shifts):
        workers_in_shift = []
        for i in range(n_workers):
            workers_in_shift.append(m.eval(all_workers_shifts[i][j]))
        print(f"Shift {shifts[j].start.date()} ({shift_types[shifts[j].type]}) has workers {workers_in_shift}")

    print("\n---------- Satisfacción ----------\n")
    this_satisfaction_score=satisfaction_score(all_workers_shifts, workers, shifts)
    print("Satisfaction score this calendar: ", this_satisfaction_score)
    last_calendar_scores = [sum(workers[i].past_satisfaction) / len(workers[i].past_satisfaction) for i in range(n_workers)]
    print("Satisfaction score last calendars: ", last_calendar_scores)
    all_calendar_scores = [sum(workers[i].past_satisfaction)+ this_satisfaction_score[i] / len(workers[i].past_satisfaction)+1 for i in range(n_workers)]
    print("Satisfaction score all calendars including last: ", all_calendar_scores)

else:
    print("No solution found")




