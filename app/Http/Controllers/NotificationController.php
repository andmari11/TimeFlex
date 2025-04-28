<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class NotificationController
{
    public function index(Request $request)
    {
        // si no se esta autenticado se redirige a login mostrando mensaje de error
        if (!auth()->check()) {
            return redirect('/login')->withErrors(['message' => 'Debes iniciar sesión para ver las notificaciones.']);
        }

        // todas las notificaciones del usuario
        $query = auth()->user()->notifications()->latest();
        auth()->user()->unreadNotifications()->update(['read' => true]);

        // aplicar filtro si se selecciona y no es 'todas'
        if ($request->has('tipo') && $request->tipo !== 'todas') {
            $query->where('tipo', $request->tipo);
        }

        // obtenemos las notificaciones adecuadas
        $notifications = $query->get();

        // marcamos como leidas las notificaciones mostradas
        auth()->user()->notifications()
            ->where('read', false)
            ->when($request->has('tipo') && $request->tipo !== 'todas', function ($b) use ($request) {
                $b->where('tipo', $request->tipo);
            })
            ->update(['read' => true]);

        // obtenemos los tipos de notificaciones que dejar elegir al usuario
        $tipos = auth()->user()->notifications()
            ->select('tipo')
            ->whereNotNull('tipo')
            ->distinct()
            ->pluck('tipo');

        return view('notificationspanel', compact('notifications', 'tipos'));
    }


    public function getUnreadNotifications()
    {
        // obtenemos el usuario actual y si no esta logueado devolvemos array vacio
        $user = auth()->user();
        if (!$user) {
            return response()->json([]);
        }

        // obtenemos las preferencias del usuario y si tiene devolvemos array vacio
        $preferences = $user->notificationPreferences;
        if (!$preferences) {
            return response()->json([]);
        }

        //obtenemos las notificaciones no leidas del usuario
        $notifications = $user->allNotifications()
            ->where('read', false)
            ->get();

        // filtramos las notificaciones segun las preferencias del usuario
        $filteredNotifications = $notifications->filter(function ($notification) use ($preferences) {
            if ($notification->tipo === 'ayuda' && !$preferences->ayuda) {
                return false;
            }
            if ($notification->tipo === 'turno' && !$preferences->turno) {
                return false;
            }
            if ($notification->tipo === 'sistema' && !$preferences->sistema) {
                return false;
            }
            if (!in_array($notification->tipo, ['ayuda', 'turno', 'sistema']) && !$preferences->otras) {
                return false;
            }
            return true;
        });

        return response()->json($filteredNotifications->values());
    }

}
