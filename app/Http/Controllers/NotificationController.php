<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->find($id);

        if ($notification) {
            $notification->markAsRead();
        }

        return back();
    }
    public function unreadCount()
{
    $unreadCount = auth()->user()->unreadNotifications->count();

    return response()->json(['unreadCount' => $unreadCount]);
}
}