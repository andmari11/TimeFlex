<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
class UserNotificationsPreferencesController extends Controller
{
    public function update(Request $request)
    {
        $data = $request->validate([
            'ayuda' => 'required|boolean',
            'turno' => 'required|boolean',
            'sistema' => 'required|boolean',
            'otras' => 'required|boolean',
        ]);

        $user = auth()->user();

        if (!$user->notificationPreferences()) {
            $user->notificationPreferences()->create($data);
        } else {
            $user->notificationPreferences()->update($data);
        }

        return response()->json(['success' => true]);
    }

    public function getPreferences()
    {
        $user = auth()->user();
        $preferences = $user->notificationPreferences;

        if (!$preferences) {
            return response()->json([
                'ayuda' => true,
                'turno' => true,
                'sistema' => true,
                'otras' => true,
            ]);
        }

        return response()->json([
            'ayuda' => $preferences->ayuda,
            'turno' => $preferences->turno,
            'sistema' => $preferences->sistema,
            'otras' => $preferences->otras,
        ]);
    }
}
