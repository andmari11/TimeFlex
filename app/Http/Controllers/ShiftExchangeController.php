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
    public function select($id_schedule, $id_shift_someone)
    {

        $scheduleData = ScheduleController::prepareScheduleData($id_schedule);
        $userShifts = auth()->user()->shifts->filter(function ($shift) use ($id_schedule, $id_shift_someone) {
            return $shift->schedule->id == $id_schedule && $shift->id != $id_shift_someone;
        });
        $availableShifts = Schedule::find($id_schedule)->shifts->filter(fn($shift) => !in_array(auth()->user()->id, $shift->users->pluck('id')->toArray()));
        return view('schedules.shift-exchange', array_merge($scheduleData, compact('userShifts', 'id_shift_someone', 'availableShifts')));

    }

    public function exchange($id_schedule, $id_shift_someone, $id_shift_mine)
    {

        $scheduleData = ScheduleController::prepareScheduleData($id_schedule);
        $userShifts = auth()->user()->shifts->filter(function ($shift) use ($id_schedule, $id_shift_someone) {
            return $shift->schedule->id == $id_schedule && $shift->id != $id_shift_someone;
        });
        $availableShifts = Schedule::find($id_schedule)->shifts->filter(fn($shift) => !in_array(auth()->user()->id, $shift->users->pluck('id')->toArray()));

        return view('schedules.shift-exchange', array_merge($scheduleData, compact('userShifts', 'id_shift_mine', 'id_shift_someone', 'availableShifts')));

    }

    public function createExchange()
    {
        $data = request()->validate([
            'schedule_id' => 'required',
            'shift_id_someone' => 'required',
            'shift_id_mine' => 'required',
            'reason' => 'required',
        ]);

        $user_receiver = Shift::find($data['shift_id_someone'])->user;

        $exchange = ShiftExchange::create([
            'demander_id' => auth()->user()->id,
            'receiver_id' => $user_receiver->id ?? null,
            'shift_receiver_id' => $data['shift_id_someone'],
            'shift_demander_id' => $data['shift_id_mine'],
            'reason' => $data['reason'],
        ]);

        $exchange->save();

        if ($user_receiver != null) {
            $notification = new Notification();
            $notification->user_id = $user_receiver->id;
            $notification->tipo = 'exchange';
            $notification->shift_exchange_id = $exchange->id;
            $notification->message = 'Solicitud de cambio de turno';
            $notification->url = '/horario/' . $data['schedule_id'] . '/turno/' . $data['shift_id_someone'];
            $notification->save();
        } else {
            $this->acceptExchange($exchange->id);
        }
        $notification = new Notification();
        $notification->user_id = auth()->user()->id;
        $notification->message = 'Has solicitado un cambio de turno';
        $notification->url = '/horario/' . $data['schedule_id'] . '/turno/' . $data['shift_id_someone'];


        $notification->save();


        return redirect('/horario/' . $data['schedule_id'] . '/turno/' . $data['shift_id_someone']);
    }

    public function cancelExchange($id)
    {
        $exchange = ShiftExchange::find($id);
        $exchange->delete();

        $notification = new Notification();
        $notification->user_id = $exchange->receiver_id ?? null;
        $notification->message = 'Han cancelado tu solicitud de cambio de turno';
        $notification->url = '/horario/' . $exchange->shiftReceiver->schedule->id . '/turno/' . $exchange->shift_receiver_id;
        $notification->save();

        return redirect('/horario/' . $exchange->shiftReceiver->schedule->id . '/turno/' . $exchange->shift_receiver_id);

    }

    public function acceptExchange($id)
    {
        $exchange = ShiftExchange::find($id);
        if(true){
            $user_receiver = User::find($exchange->receiver_id);
            $user_demander = User::find($exchange->demander_id);
            $section =User::find($exchange->demander_id)->section;
            foreach ($section->company->admins as $user) {
                $notification1 = new Notification();
                $notification1->message = 'Confirma el cambio de ' . $user_demander->name;
                $notification1->url = '/horario/' . $exchange->shiftReceiver->schedule->id . '/turno/' . $exchange->shift_receiver_id;
                $notification1->shift_exchange_id = $exchange->id;
                $notification1->user_id = $user->id;
                $notification1->save();
            }
        }
        else{
            $this->acceptExchangeAdmin();
        }
        return redirect('/horario/' . $exchange->shiftReceiver->schedule->id . '/turno/' . $exchange->shift_receiver_id);

    }

    public function acceptExchangeAdmin($id)
    {
        $exchange = ShiftExchange::find($id);
        $receiver = User::find($exchange->receiver_id);
        $emitent = User::find($exchange->demander_id);

        $receiver->shifts()->attach($exchange->shift_demander_id);
        $emitent->shifts()->attach($exchange->shift_receiver_id);

        $exchange->delete();


        return redirect('/horario/' . $exchange->shiftReceiver->schedule->id . '/turno/' . $exchange->shift_receiver_id);

    }
}
