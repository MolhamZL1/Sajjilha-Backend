<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::with('client') // تحميل العلاقة مسبقًا
        ->whereHas('client', function($query) {
            $query->where('user_id', auth()->id());
        })
            ->latest()
            ->paginate(10);

        return response_data($notifications, __('messages.notifications_retrieved'));
    }
    public function markAllAsRead()
    {
        // تحديد كل إشعارات المستخدم كمقروءة عبر العلاقة مع العملاء
        Notification::whereHas('client', function($query) {
            $query->where('user_id', auth()->id());
        })
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response_data([], __('messages.all_notifications_marked_read'));
    }

    public function markAsRead($id)
    {
        // تحديد إشعار محدد كمقروء مع التحقق من ملكيته للمستخدم
        $notification = Notification::whereHas('client', function($query) {
            $query->where('user_id', auth()->id());
        })
            ->where('id', $id)
            ->firstOrFail();

        $notification->update(['is_read' => true]);

        return response_data([], __('messages.notification_marked_read'));
    }

    public function unreadCount()
    {
        // عدد الإشعارات غير المقروءة للمستخدم
        $count = Notification::whereHas('client', function($query) {
            $query->where('user_id', auth()->id());
        })
            ->where('is_read', false)
            ->count();

        return response_data(['count' => $count], __('messages.unread_count_retrieved'));
    }
}
