<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Form;
use App\Models\Holidays;
use App\Models\Notification;
use App\Models\Result;
use App\Models\Satisfaction;
use App\Models\Schedule;
use App\Models\Shift;
use App\Models\User;
use App\Models\WorkerPreference;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FastApiController extends Controller
{
    public function sendSchedule($id){
        $form_id = 1;
        $schedule = Schedule::find($id);

        $data=[
            "name" => $schedule->name,
            "section_id" => $schedule->section->id,
            "id" => $schedule->id,
        ];

        $worker_preferences = [];

        $holidays = Holidays::where('dia_vacaciones', '>', Carbon::today())->get();
        $max_days = max(abs(Carbon::now()->diffInDays($holidays->min('fecha_solicitud') ?? Carbon::today())), 1);
        foreach($schedule->section->users as $user){
            $turnoFav = $schedule->results()->where('id_user', $user->id)->where('id_question_type', 4)->first()->respuesta ?? null;

            $holidays = $user->holidays->filter(function ($holiday) use ($schedule) {
                return $holiday->dia_vacaciones >= $schedule->start_date && $holiday->dia_vacaciones <= $schedule->end_date;
            });
            $dates= [];
            $weights = [];

            foreach ($holidays as $holiday) {
                $dates[] = $holiday->dia_vacaciones;
                $weights[] = max((abs(Carbon::now()->diffInDays(Carbon::parse($holiday->fecha_solicitud)))/$max_days*$user->weight), 1);
            }
            $dates = $this->formatHolidaysWithTime($dates);
            $satisfactions = $user->satisfactions->pluck('score')->toArray();
            $worker_preference = [
                "form_id" => $form_id,
                "user_id" => $user->id,
                "holidays" => $dates != null ? json_encode($dates) : json_encode([]),
                "holidays_weight" => $weights != null ? json_encode($weights) : json_encode([]),
                "preferred_shift_types" => $turnoFav != null ? json_encode([$turnoFav]) : json_encode([]),
                "preferred_shift_types_weight" => $user->weight,
                "past_satisfaction" => $satisfactions != null ? json_encode($satisfactions) : json_encode([]),
            ];
            WorkerPreference::create($worker_preference);
            $worker_preferences[] = $worker_preference;
        }

        $data['usersJSON'] =json_encode($worker_preferences);
        $scheduleShifts=[];
        foreach($schedule->shifts as $shift){
            $shiftData = [
                "id" => $shift->id,
                "schedule_id" => $shift->schedule_id,
                "start" => $shift->start,
                "end" => $shift->end,
                "users_needed" => $shift->users_needed,
                "type" => $shift->type,
                "users" => $shift->users->pluck('id')->toArray()
            ];
            $scheduleShifts[] = $shiftData;
        }
        $data['shiftsJSON'] = json_encode($scheduleShifts);

        try{
            $response = Http::timeout(5)->post(config('services.fastApi.url') . 'api/schedule', $data);
            if ($response->failed()) {
                return redirect('/horario')->withErrors(['message' => 'Error sending data.']);
            }
            else{
                $schedule->update([
                    'status' => 'pending',
                    'simulation_message' => null
                ]);
            }
        }
        catch (\Illuminate\Http\Client\ConnectionException $e) {
            // errores de conexion
            return redirect('/horario')->withErrors(['message' => 'Request timed out. Please try again later.']);
        }
        catch (\Exception $e) {
            // otro tipos de errores
            return redirect('/horario')->withErrors(['message' => 'Error sending data.']);
        }

        return redirect('/horario');

    }
    function formatHolidaysWithTime($holidays)
    {
        return collect($holidays)->map(function ($date) {
            return Carbon::parse($date)->startOfDay()->toDateTimeString();
        });
    }

    public function receiveSchedule(): \Illuminate\Http\JsonResponse
    {
        Log::info('Datos recibidos:', request()->all());

        $data=request()->validate([
            "id"=>"required",
            "scheduleJSON"=>"array | nullable",
            "satisfabilityJSON"=>"array | nullable",
            "status"=>"required",
            "message"=>"nullable",
        ]);
        $schedule = Schedule::find($data['id']);

        if($data['status'] == 'failed'){
            $message= FastApiController::formatMessage($data['message'], $schedule->section->users, $schedule->shifts);
            $schedule->update([
                'status' => $data['status'],
                'simulation_message' => $message
            ]);
            return response()->json(['message' => 'Datos recibidos y guardados correctamente'], 200);
        }

        $schedule->shifts->each(function ($shift) {
            $shift->users()->detach();
        });
        foreach ($schedule->section->company->admins as $user) {
            Notification::create([
                'user_id' => $user->id,
                'message' => "Nuevo horario {$schedule->name} disponible.",
                'url' => "/horario/{$schedule->id}",
                'read' => false,
                'tipo' => 'normal',
                'shift_exchange_id' => $shiftExchange->id ?? null, // Asegurar que existe
            ]);

        }
        if($schedule) {
            $schedule->update([
                'status' => $data['status']
            ]);
            if (isset($data['scheduleJSON'])) {
                foreach ($data['scheduleJSON'] as $userId => $shiftIds) {
                    $user = User::find($userId);
                    if ($user) {
                        foreach ($shiftIds as $shiftId) {
                            $shift = Shift::find($shiftId);

                            if ($shift) {
                                $shift->users()->attach($user->id);
                            } else {
                                return response()->json(['message' => "Shift con ID {$shiftId} no encontrado."], 404);

                            }
                        }
                    } else {
                        return response()->json(['message' => "Usuario con ID {$userId} no encontrado."], 404);

                    }
                }
            }
            else{
                return response()->json(['message' => 'Se ha producido un error no hay Schedule json'], 404);
            }
        }
        else{
            return response()->json(['message' => 'Se ha producido un error schedule no encontrado'], 404);

        }
        if(isset($data['satisfabilityJSON'])){
            foreach ($data['satisfabilityJSON'] as $userId => $satisfability) {
                $user = User::find($userId);
                if ($user) {
                    Satisfaction::create([
                        'user_id' => $user->id,
                        'score' => $satisfability,
                        'schedule_id' => $schedule->id
                    ]);

                    foreach ($user->holidays as $holiday) {
                        $userShifts = $user->shifts->where('schedule_id', $schedule->id);
                        if($userShifts->every(fn($shift) =>
                            Carbon::parse($shift->start)->toDateString() != $holiday->dia_vacaciones
                            && Carbon::parse($shift->end)->toDateString() != $holiday->dia_vacaciones)){
                            $holiday->update(['estado' => 'accepted']);
                        }
                        else{
                            $holiday->update(['estado' => 'rejected']);
                        }
                    }
                } else {
                    return response()->json(['message' => "Usuario con ID {$userId} no encontrado."], 404);

                }
            }
        }
        else{
            return response()->json(['message' => 'Se ha producido un error no hay satisfability json'], 404);
        }

        return response()->json(['message' => 'Datos recibidos y guardados correctamente'], 200);

    }

    public static function sendStats(){
        $statsInfo['id']=1;
        try{

            $response = Http::timeout(5)->post(config('services.fastApi.url') . 'api/stats', $statsInfo);

        }
        catch (\Illuminate\Http\Client\ConnectionException $e) {
            // errores de conexion
            return false;
        }
        catch (\Exception $e) {
            // otro tipos de errores
            return false;
        }

        return $response;
    }


    function generateShifts($schedule, $year, $month, $day, $daysToGenerate = 5) {
        $shifts = [];
        $shiftTypes = [
            ['start' => '09:00:00', 'end' => '15:00:00', 'type' => 0], // Mañana
            ['start' => '15:00:00', 'end' => '21:00:00', 'type' => 1], // Tarde
            ['start' => '21:00:00', 'end' => '04:00:00', 'type' => 2], // Noche
        ];

        for ($i = 0; $i < $daysToGenerate; $i++) {
            $currentDate = Carbon::createFromDate($year, $month, $day);

            if ($currentDate->month != $month) {
                break;
            }

            $scheduleId = $schedule->id;

            foreach ($shiftTypes as $shift) {
                $start = Carbon::createFromFormat('Y-m-d H:i:s', "$year-$month-$day {$shift['start']}")->toDateTimeString();

                $end = Carbon::createFromFormat('Y-m-d H:i:s', "$year-$month-$day {$shift['end']}")->toDateTimeString();
                if ($shift['end'] == '04:00:00') {
                    $end = Carbon::createFromFormat('Y-m-d H:i:s', "$year-$month-" . ($day + 1) . " {$shift['end']}")->toDateTimeString();
                }

                $shift = Shift::create([
                    "schedule_id" => $scheduleId,
                    "start" => $start,
                    "end" => $end,
                    "users_needed" => 1,
                    "type" => $shift['type']
                ]);
                $shifts[] = $shift->toArray();
            }
            $day++;

        }

        return json_encode($shifts, JSON_PRETTY_PRINT);
    }


    static function formatMessage ($messages, $workers, $shifts) {
        $formattedMessages = [];

        foreach ($messages as $message) {
            $message = preg_replace_callback('/%worker_(\d+)%/', function($matches) use ($workers) {
                $workerId = $matches[1];
                $worker = User::find($workerId);
                return $worker ? $worker->name : '%worker_' . $workerId . '%';
            }, $message);

            $message = preg_replace_callback('/%shift_(\d+)%/', function($matches) use ($shifts) {
                $shiftId = $matches[1];
                $shift = Shift::find($shiftId);
                return $shift ? ("El turno del " . Carbon::parse($shift->start)->format('Y-m-d')) : '%shift_' . $shiftId . '%';
            }, $message);

            $formattedMessages[] = $message;
        }

        return implode(". \n", $formattedMessages);

    }
}
