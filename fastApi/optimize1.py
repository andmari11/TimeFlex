from z3 import *
from model.shift import *
from model.workerPreference import *
import json

PREFERRED_SHIFTS_WEIGHT = 10
HOLIDAYS_WEIGHT = 10
N_MAX_SHIFTS_PER_WORKER = 999999
N_MAX_HOURS_PER_WORKER = 999999
N_MIN_HOURS_PER_WORKER = 0
N_MIN_SHIFTS_PER_WORKER = 0


def read_file(file_path):
    with open(file_path, 'r', encoding='utf-8') as file:
        return json.load(file)
    
def satisfaction_score(all_assigned_shifts, workers, shifts, m):
    scores = []
    max_score = 0
    min_score = float('inf')
    
    for i in range(len(workers)):
        holidays_worked = 0
        preferred_shifts = 0
        for j in range(len(all_assigned_shifts[i])):
            if m.eval(all_assigned_shifts[i][j]):
                for holiday in workers[i].holidays:
                    holidays_worked += (shifts[j].start.date() == holiday.date() or shifts[j].end.date() == holiday.date())
                    preferred_shifts += (shifts[j].type in workers[i].preferred_shift_types)
        # print(f"Worker {workers[i].user_id} worked {holidays_worked} holidays and {preferred_shifts} preferred shifts")
        score = (holidays_worked * -HOLIDAYS_WEIGHT) + (preferred_shifts * PREFERRED_SHIFTS_WEIGHT)
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

def optimize(data, logging):
    # workers = process_worker_preferences(read_file('z3_pruebas/preferencias.txt'))
    # shifts = process_shifts_from_json(read_file("z3_pruebas/turnos.txt"))
    workers = process_worker_preferences(data.get("workers", []))
    shifts = process_shifts_from_json(data.get('shifts', []))
    PREFERRED_SHIFTS_WEIGHT = data.get("shiftsWeightConfig", 10)
    HOLIDAYS_WEIGHT = data.get("holidaysWeightConfig", 10)
    N_MAX_SHIFTS_PER_WORKER = data.get("maxShiftsPerWorker", 999999)
    N_MAX_HOURS_PER_WORKER = data.get("maxHoursPerWorker", 999999)
    N_MIN_HOURS_PER_WORKER = data.get("minHoursPerWorker", 0)
    N_MIN_SHIFTS_PER_WORKER = data.get("minShiftsPerWorker", 0)

    n_shifts = len(shifts)
    n_workers = len(workers)


    def nWork (i,j):
        return "worker"+str(i)+"works"+str(j)
    def satisfaction_desviation (i):
        return "satisfaction_desviation"+str(i)
    def satisfaction (i):
        return "satisfaction"+str(i)
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

    #asignamos los turnos predefinidos
    for j in range(n_shifts):
        for i in range(n_workers):
            if workers[i].user_id in shifts[j].users:
                s.add(all_workers_shifts[i][j] == True)

    #comprobamos cuantos turnos trabaja cada trabajador
    for i in range(n_workers):
        worker_takes_shift=[]
        for j in range(n_shifts):
            worker_takes_shift.append(bool2int(all_workers_shifts[i][j]))
        s.assert_and_track(addsum(worker_takes_shift) <= N_MAX_SHIFTS_PER_WORKER, f"%worker_{workers[i].user_id}% supera el máximo de turnos {N_MAX_SHIFTS_PER_WORKER} a trabajar")	
        s.assert_and_track(addsum(worker_takes_shift) >= N_MIN_SHIFTS_PER_WORKER, f"%worker_{workers[i].user_id}% no alcanza el mínimo de turnos {N_MIN_SHIFTS_PER_WORKER} a trabajar")


    #comprobamos cuantos horas trabaja cada trabajador
    for i in range(n_workers):
        worker_hours=[]
        for j in range(n_shifts):
            worker_hours.append(If(all_workers_shifts[i][j], countHours(shifts[j]), 0))
        s.assert_and_track(addsum(worker_hours) <= N_MAX_HOURS_PER_WORKER, f"%worker_{workers[i].user_id}% supera el máximo de horas {N_MAX_HOURS_PER_WORKER} a trabajar")
        s.assert_and_track(addsum(worker_hours) >= N_MIN_HOURS_PER_WORKER, f"%worker_{workers[i].user_id}% no alcanza el mínimo de horas {N_MIN_HOURS_PER_WORKER} a trabajar")

    #comprobamos que cada turno tiene suficientes trabajadores
    for i in range(n_shifts):
        workers_in_shift = []
        for j in range(n_workers):
            workers_in_shift.append(bool2int(all_workers_shifts[j][i]))
        s.assert_and_track(addsum(workers_in_shift) >= shifts[i].users_needed, f"%shift_{shifts[i].shift_id}% no alcanza el mínimo de {shifts[i].users_needed} trabajadores")


    #comprobamos que los trabajadores no trabajen en sus días de vacaciones
    for i in range(n_workers):
        for j in range(n_shifts):
            for index, holiday in enumerate(workers[i].holidays):
                holiday_shift=(Or(
                    shifts[j].start.date() == holiday.date(), 
                    shifts[j].end.date() == holiday.date()))
                s.add_soft(Or(all_workers_shifts[i][j]==False, And(all_workers_shifts[i][j], Not(holiday_shift))), weight = workers[i].holidays_weight[index])

    #tipo de turno ej:(mañana:0, tarde:1, noche:2)
    for i in range(n_workers):
        for j in range(n_shifts):
            s.add_soft(Implies(all_workers_shifts[i][j], shifts[j].type in workers[i].preferred_shift_types), weight = workers[i].preferred_shift_weight)


    # calcular la satisfacción de cada trabajador
    this_calendar_satisfaction = []
    for i in range(n_workers):
        holidays_worked = []
        preferred_shifts = []
        for j in range(n_shifts):
            for holiday in workers[i].holidays:
                holidays_worked.append(If(Or(shifts[j].start.date() == holiday.date(), shifts[j].end.date() == holiday.date()), bool2int(all_workers_shifts[i][j]), 0))
                preferred_shifts.append(If(shifts[j].type in workers[i].preferred_shift_types, bool2int(all_workers_shifts[i][j]), 0))

        worker_satisfaction=(addsum(holidays_worked) * HOLIDAYS_WEIGHT + addsum(preferred_shifts) * PREFERRED_SHIFTS_WEIGHT)
        this_calendar_satisfaction.append(worker_satisfaction)

    #solo puedes trabajar un turno diario
    for i in range(n_workers):
        for day in set(shift.start for shift in shifts):  # dias de turnos
            daily_shifts = [all_workers_shifts[i][j] for j in range(n_shifts) if shifts[j].start.date() == day.date()]
            s.assert_and_track(Sum([If(shift, 1, 0) for shift in daily_shifts]) <= 1, f"%worker_{workers[i].user_id}% trabaja más de un turno en el día {day.date()}")




    #calcular la satisfacción media teniendo en cuenta calendarios anteriores
    all_averages=[]
    for i in range(n_workers):
        all_averages.append((sum(workers[i].past_satisfaction) + this_calendar_satisfaction[i]) / (len(workers[i].past_satisfaction) + 1))


    #calcular la desviación de la satisfacción media
    avg_satisfaction = Sum(all_averages) / n_workers
    deviations=[]
    for i in range(n_workers):
        
        deviations.append(Real(satisfaction_desviation(i)) == all_averages[i] - avg_satisfaction)
    s.maximize(avg_satisfaction)
    s.minimize(Sum(deviations))


    #horas equilibradas entre usuarios

    #Imrpimir resultados TODO recibir de api
    solution_to_send={}
    solution_to_send['id']=data['id']
    log=""

    if s.check() == sat:
        solution_to_send['status'] = "success"
        solution_to_send['scheduleJSON'] = {}
        solution_to_send['satisfabilityJSON'] = {}

        m = s.model()
        log+=("\n---------- Horario por empleado ----------\n")
        for i in range(n_workers):
            worker_schedule = []
            for j in range(n_shifts):
                if m.eval(all_workers_shifts[i][j]):
                    worker_schedule.append((shifts[j].start.date(),shifts[j].type ))
                    
            log+=(f"Worker {workers[i].user_id} works shifts {worker_schedule}")

        log+=("\n---------- Horario por turno ----------\n")
        for j in range(n_shifts):
            workers_in_shift = []
            for i in range(n_workers):
                workers_in_shift.append(m.eval(all_workers_shifts[i][j]))
            log+=(f"Shift {shifts[j].start.date()} ({shifts[j].type}) has workers {workers_in_shift}")
        log+=("\n---------- Satisfacción ----------\n")
        this_satisfaction_score=satisfaction_score(all_workers_shifts, workers, shifts, m)
        log+=("Satisfaction score this calendar: " + str(this_satisfaction_score))
        # last_calendar_scores = [sum(workers[i].past_satisfaction) / len(workers[i].past_satisfaction) for i in range(n_workers)]
        # log+=("Satisfaction score last calendars: " + str(last_calendar_scores))
        # all_calendar_scores = [sum(workers[i].past_satisfaction)+ this_satisfaction_score[i] / len(workers[i].past_satisfaction)+1 for i in range(n_workers)]
        # log+=("Satisfaction score all calendars including last: " + str(all_calendar_scores))

        for i in range(n_workers):
            user_schedule = [] 
            solution_to_send['satisfabilityJSON'][workers[i].user_id] = this_satisfaction_score[i]
            for j in range(n_shifts):
                if m.eval(all_workers_shifts[i][j]):
                    user_schedule.append(shifts[j].shift_id) 
                    
            solution_to_send['scheduleJSON'][workers[i].user_id] = user_schedule
        

    else:

        solution_to_send['status'] = "failed"
        unsat_core_str = [str(c) for c in s.unsat_core()]
        solution_to_send['message'] = unsat_core_str

        log=("No solution found", unsat_core_str)

    logging.debug(log)
    return solution_to_send


