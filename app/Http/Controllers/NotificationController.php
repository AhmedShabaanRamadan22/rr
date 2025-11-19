<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;

class NotificationController extends Controller
{
     // use Notifiable;
     public function index(){
        $notifications = User::find(auth()->user()->id)->notifications()
        ->orderBy('read_at', 'asc')
        ->orderBy('created_at', 'desc')
        ->get();
        return response()->json(['notifications' => $notifications], 200);
    }

    public function markAsRead()
    {
        $notifications = User::find(auth()->user()->id)->notifications()->get();
        foreach($notifications as $notification){
            $notification->markAsRead();
        }
        return response()->json(['message' => 'Read successfully.'], 200);
    }
}
