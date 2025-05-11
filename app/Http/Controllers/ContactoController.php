<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Notification;
class ContactoController extends Controller
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

        // notificación para el admin (ID 11)
        $notificacionAdmin = new Notification();
        $notificacionAdmin->user_id = 11;
        $notificacionAdmin->tipo = 'ayuda';
        $notificacionAdmin->message = 'Se ha recibido una nueva petición de ayuda o información de contacto de un usuario externo';
        $notificacionAdmin->email = $validated['email'];
        $notificacionAdmin->nombre = $validated['first-name'];
        $notificacionAdmin->apellidos = $validated['last-name'];
        $notificacionAdmin->duda = $validated['message'];
        $notificacionAdmin->save();
        return back()->with('success', 'Gracias por contactarnos. Responderemos a tu consulta cuanto antes');
    }
}
