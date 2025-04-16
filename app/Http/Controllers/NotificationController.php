<?php

namespace App\Http\Controllers;

class NotificationController
{
    public function index()
    {
        $notifications = auth()->user()->notifications()->latest()->get();
        return view('notificationspanel', compact('notifications'));
    }

}
