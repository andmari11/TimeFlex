<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Schedules\ScheduleController;
use App\Models\Notification;
use App\Models\Schedule;
use App\Models\Shift;
use App\Models\ShiftExchange;
use App\Models\User;
use Illuminate\Http\Request;

class ShiftExchangeController extends Controller
{
    public function selectAdmin($id_schedule,$workerSelected_id, $id_shift_someone, $id_shift_mine)
    {
        $scheduleData = ScheduleController::prepareScheduleData($id_schedule);
        $workerSelected = User::find($workerSelected_id);
        if($workerSelected){
            $userShifts = $workerSelected->shifts->filter(function ($shift) use ($id_schedule, $id_shift_someone) {
                return $shift->schedule->id == $id_schedule && $shift->id != $id_shift_someone;
            });
        }
        else{
            $userShifts = [];
        }
        $days=$scheduleData['months'][0]['days'] ?? [];
        $availableShifts = Schedule::find($id_schedule)->shifts->filter(fn($shift) => !in_array(auth()->user()->id, $shift->users->pluck('id')->toArray()));
        $workers = Schedule::find($id_schedule)->section->users;
        return view('schedules.shift-exchange-admin', array_merge($scheduleData, compact('userShifts', 'id_shift_mine', 'id_shift_someone', 'availableShifts', 'workers', 'workerSelected', 'days')));
    }
    public function selectAssign($id_schedule,$workerSelected_id, $id_shift_someone){
        $scheduleData = ScheduleController::prepareScheduleData($id_schedule);
        $workerSelected = User::find($workerSelected_id);
        if($workerSelected){
            $userShifts = $workerSelected->shifts->filter(function ($shift) use ($id_schedule, $id_shift_someone) {
                return $shift->schedule->id == $id_schedule && $shift->id != $id_shift_someone;
            });
        }
        else{
            $userShifts = [];
        }
        $days=$scheduleData['months'][0]['days'] ?? [];
        $availableShifts = Schedule::find($id_schedule)->shifts->filter(fn($shift) => !in_array(auth()->user()->id, $shift->users->pluck('id')->toArray()));
        $workers = Schedule::find($id_schedule)->section->users;
        return view('schedules.shift-exchange-assign', array_merge($scheduleData, compact('userShifts', 'id_shift_someone', 'availableShifts','workers', 'workerSelected', 'days')));
    }
    public function select($id_schedule, $id_shift_someone)
    {

        $scheduleData = ScheduleController::prepareScheduleData($id_schedule);
        $userShifts = auth()->user()->shifts->filter(function ($shift) use ($id_schedule, $id_shift_someone) {
            return $shift->schedule->id == $id_schedule && $shift->id != $id_shift_someone;
        });
        $days=$scheduleData['months'][0]['days'] ?? [];
        $availableShifts = Schedule::find($id_schedule)->shifts->filter(fn($shift) => !in_array(auth()->user()->id, $shift->users->pluck('id')->toArray()));
        return view('schedules.shift-exchange', array_merge($scheduleData, compact('userShifts', 'id_shift_someone', 'availableShifts', 'days')));

    }

    public function exchange($id_schedule, $id_shift_someone, $id_shift_mine)
    {

        $scheduleData = ScheduleController::prepareScheduleData($id_schedule);
        $userShifts = auth()->user()->shifts->filter(function ($shift) use ($id_schedule, $id_shift_someone) {
            return $shift->schedule->id == $id_schedule && $shift->id != $id_shift_someone;
        });
        $days=$scheduleData['months'][0]['days'] ?? [];
        $availableShifts = Schedule::find($id_schedule)->shifts->filter(fn($shift) => !in_array(auth()->user()->id, $shift->users->pluck('id')->toArray()));
        return view('schedules.shift-exchange', array_merge($scheduleData, compact('userShifts', 'id_shift_mine', 'id_shift_someone', 'availableShifts','days')));

    }

    public function assignShift()
    {
        $data = request()->validate([
            'schedule_id' => 'required',
            'shift_id_someone' => 'required',
            'worker_id' => 'required',
        ]);

        $worker = User::find($data['worker_id']);
        $shift = Shift::find($data['shift_id_someone']);
        $shift->users()->attach($worker->id);
        if ($worker != null) {
            $notification = new Notification();
            $notification->user_id = $worker->id;
            $notification->tipo = 'turno';
            $notification->message = 'Administración te ha asignado un turno';
            $notification->url = '/horario/' . $data['schedule_id'] . '/turno/' . $data['shift_id_someone'];
            $notification->save();
            $worker->shifts()->attach($data['shift_id_someone']);
        }

       return redirect('/horario/' . $data['schedule_id'] . '/turno/' . $data['shift_id_someone']);
    }

    public function createExchange()
    {
        $data = request()->validate([
            'schedule_id' => 'required',
            'shift_id_someone' => 'required',
            'shift_id_mine' => 'required',
            'reason' => 'required',
        ]);

        $user_receiver = Shift::find($data['shift_id_someone'])->users;



        foreach ($user_receiver as $user) {
            if ($user_receiver != null) {
                $exchange = ShiftExchange::create([
                    'demander_id' => auth()->user()->id,
                    'receiver_id' => $user->id ?? null,
                    'shift_receiver_id' => $data['shift_id_someone'],
                    'shift_demander_id' => $data['shift_id_mine'],
                    'reason' => $data['reason'],
                ]);
                $exchange->save();
                $notification = new Notification();
                $notification->user_id = $user->id;
                $notification->tipo = 'turno';
                $notification->shift_exchange_id = $exchange->id;
                $notification->message = 'Solicitud de cambio de turno';
                $notification->url = '/horario/' . $data['schedule_id'] . '/turno/' . $data['shift_id_someone'];
                $notification->save();

            } else {
            $this->acceptExchange($exchange->id);
            }
        }
        $notification = new Notification();
        $notification->tipo = 'turno';
        $notification->user_id = auth()->user()->id;
        $notification->message = 'Has solicitado un cambio de turno';
        $notification->url = '/horario/' . $data['schedule_id'] . '/turno/' . $data['shift_id_someone'];


        $notification->save();


        return redirect('/menu');
    }

    public function cancelExchange($id)
    {
        $exchange = ShiftExchange::find($id);
        $exchange->update(['status' => 'accepted']);

        $notification = new Notification();
        $notification->tipo = 'turno';
        $notification->user_id = $exchange->demander_id ?? null;
        $notification->message = 'Han cancelado tu solicitud de cambio de turno';
        $notification->url = '/horario/' . $exchange->shiftReceiver->schedule->id . '/turno/' . $exchange->shift_receiver_id;
        $notification->save();

        Notification::where('shift_exchange_id', $id)->delete();

        return redirect('/menu');

    }

    public function acceptExchange($id)
    {
        $exchange = ShiftExchange::find($id);
        if(auth()->user()->role == 'admin'){
            $this->acceptExchangeAdmin($id);
            return redirect('/menu');
        }
        auth()->user()->notifications->where('shift_exchange_id', $id)->each->delete();
        //TODO: mirar config
        if(true){
            $user_demander = User::find($exchange->demander_id);
            $section =User::find($exchange->demander_id)->section;
            foreach ($section->company->admins as $user) {
                $notification1 = new Notification();
                $notification1->tipo = 'turno';
                $notification1->message = 'Confirma el cambio de ' . $user_demander->name;
                $notification1->url = '/horario/' . $exchange->shiftReceiver->schedule->id . '/turno/' . $exchange->shift_receiver_id;
                $notification1->shift_exchange_id = $exchange->id;
                $notification1->user_id = $user->id;
                $notification1->save();
            }
            $receiver = User::find($exchange->receiver_id);
            $emitent = User::find($exchange->demander_id);

            $notificationReceiver = new Notification();
            $notificationReceiver->tipo = 'turno';
            $notificationReceiver->user_id = $receiver->id ?? null;
            $notificationReceiver->message = 'Falta la confirmación del administrador para el cambio de turno';
            $notificationReceiver->url = '/horario/' . $exchange->shiftReceiver->schedule->id . '/turno/' . $exchange->shift_receiver_id;
            $notificationReceiver->save();

            $notificationEmitent = new Notification();
            $notificationEmitent->tipo = 'turno';
            $notificationEmitent->user_id = $emitent->id ?? null;
            $notificationEmitent->message = 'Falta la confirmación del administrador para el cambio de turno';
            $notificationEmitent->url = '/horario/' . $exchange->shiftReceiver->schedule->id . '/turno/' . $exchange->shift_receiver_id;
            $notificationEmitent->save();

        }
        else{
            $this->acceptExchangeAdmin();
        }
        return redirect('/menu');

    }

    public function acceptExchangeAdmin($id)
    {
        $exchange = ShiftExchange::find($id);
        $receiver = User::find($exchange->receiver_id);
        $emitent = User::find($exchange->demander_id);


        if($receiver){
            $receiver->shifts()->attach($exchange->shift_demander_id);
            $receiver->shifts()->detach($exchange->shift_receiver_id);
        }
        $emitent->shifts()->attach($exchange->shift_receiver_id);
        $emitent->shifts()->detach($exchange->shift_demander_id);

        $notification = new Notification();
        $notification->tipo = 'turno';
        $notification->user_id = $exchange->demander_id ?? null;
        $notification->message = 'Han aceptado tu solicitud de cambio de turno';
        $notification->url = '/horario/' . $exchange->shiftReceiver->schedule->id . '/turno/' . $exchange->shift_receiver_id;
        $notification->save();

        $notification2 = new Notification();
        $notification2->tipo = 'turno';
        $notification2->user_id = $exchange->receiver_id ?? null;
        $notification2->message = 'Han aceptado tu solicitud de cambio de turno';
        $notification2->url = '/horario/' . $exchange->shiftReceiver->schedule->id . '/turno/' . $exchange->shift_receiver_id;
        $notification2->save();

        $exchange->update(['status' => 'accepted']);

        Notification::where('shift_exchange_id', $id)->delete();

        return redirect('/menu');

    }

    public function createExchangeAdmin()
    {
        $data = request()->validate([
            'schedule_id' => 'required',
            'shift_id_someone' => 'required',
            'shift_id_mine' => 'required',
            'worker_id' => 'required',
            'reason' => 'required',
        ]);

        $user_receiver = Shift::find($data['shift_id_someone'])->users->first();

        if ($user_receiver != null) {
            $notification = new Notification();
            $notification->user_id = $user_receiver->id;
            $notification->tipo = 'turno';
            $notification->message = 'Administración ha cambiado un turno';
            $notification->url = '/horario/' . $data['schedule_id'] . '/turno/' . $data['shift_id_mine'];
            $notification->save();
            $user_receiver->shifts()->attach($data['shift_id_mine']);
            $user_receiver->shifts()->detach($data['shift_id_someone']);
        }
        $notification = new Notification();
        $notification->user_id = $data['worker_id'];
        $notification->tipo = 'turno';
        $notification->message = 'Administración ha cambiado un turno';
        $notification->url = '/horario/' . $data['schedule_id'] . '/turno/' . $data['shift_id_someone'];
        $notification->save();
        $emitent = User::find($data['worker_id']);
        $emitent->shifts()->attach($data['shift_id_someone']);
        $emitent->shifts()->detach($data['shift_id_mine']);

        return redirect('/menu');
    }

}
