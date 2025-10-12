<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = \App\Models\Notification::latest()->paginate(10);
        return view('notifications.index', compact('notifications'));
    }

    public function markAllAsRead()
    {
        \App\Models\Notification::where('is_read', false)->update(['is_read' => true]);
        return redirect()->back()->with('status', 'تم تحديد كل الإشعارات كمقروءة.');
    }


}
