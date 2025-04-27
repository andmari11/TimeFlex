<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
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

        // aplicar filtro si se selecciona y no es 'todas'
        if ($request->has('tipo') && $request->tipo !== 'todas') {
            $query->where('tipo', $request->tipo);
        }

        // obtenemos las notificaciones adecuadas
        $notifications = $query->get();
        // obtenemos los tipos de notificaciones que dejar elegir al usuario
        $tipos = auth()->user()->notifications()
            ->select('tipo')
            ->whereNotNull('tipo')
            ->distinct()
            ->pluck('tipo');

        return view('notificationspanel', compact('notifications', 'tipos'));
    }

}
