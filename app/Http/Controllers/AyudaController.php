<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Notification;
class AyudaController
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first-name' => 'required|string|max:255',
            'last-name' => 'required|string|max:255',
            'email' => 'required|email',
            'company' => 'nullable|string|max:255',
            'phone-number' => 'nullable|string|max:20',
            'message' => 'required|string',
        ]);

        // Puedes guardar en base de datos, enviar un email, etc.
        // Mail::to('admin@empresa.com')->send(new MensajeContacto($validated));
        $notification = new Notification();
        $notification->user_id = auth()->user()->id;
        $notification->tipo = 'duda';
        $notification->message = 'Se ha solicitado ayuda a Administración';
        $notification->save();

        $notification = new Notification();
        $notification->user_id = '11';
        $notification->message = 'Recibida nueva petición de ayuda';
        $notification->save();
        return back()->with('success', 'Gracias por contactarnos. Te responderemos en el menor tiempo posible a tu consulta.');
    }
}
