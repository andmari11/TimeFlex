<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Notification;
class AyudaController
{
    public function store(Request $request)
    {
        $request->merge(['privacy' => $request->has('privacy')]);
        $validated = $request->validate([
            'first-name' => 'required|string|max:255',
            'last-name' => 'required|string|max:255',
            'email' => 'required|email',
            'company' => 'nullable|string|max:255',
            'phone-number' => 'nullable|string|max:20',
            'message' => 'required|string',
            'privacy' => 'accepted'
        ]);

        // notificación para el usuario
        $notificacionUsuario = new Notification();
        $notificacionUsuario->user_id = auth()->user()->id;
        $notificacionUsuario->tipo = 'ayuda';
        $notificacionUsuario->message = 'Has solicitado ayuda a Administración';
        $notificacionUsuario->email = $validated['email'];
        $notificacionUsuario->nombre = $validated['first-name'];
        $notificacionUsuario->apellidos = $validated['last-name'];
        $notificacionUsuario->duda = $validated['message'];
        $notificacionUsuario->save();

        // notificación para el admin (ID 11)
        $notificacionAdmin = new Notification();
        $notificacionAdmin->user_id = 11;
        $notificacionAdmin->tipo = 'ayuda';
        $notificacionAdmin->message = 'Se ha recibido una nueva petición de ayuda del usuario con ID ' . auth()->user()->id . ' (' . $validated['first-name'] . ' ' . $validated['last-name'] . ').';
        $notificacionAdmin->email = $validated['email'];
        $notificacionAdmin->nombre = $validated['first-name'];
        $notificacionAdmin->apellidos = $validated['last-name'];
        $notificacionAdmin->duda = $validated['message'];
        $notificacionAdmin->save();
        return back()->with('success', 'Gracias por contactarnos. Responderemos a tu consulta cuanto antes');
    }
}
