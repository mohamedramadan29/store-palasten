<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Mark all notifications as read for the current admin user
     */
    public function markAllAsRead()
    {
        try {
            $user = Auth::guard('admin')->user();

            // Mark all unread notifications as read
            $user->unreadNotifications->markAsRead();

            return response()->json([
                'success' => true,
                'message' => 'تم تحديد جميع الإشعارات كمقروءة'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث الإشعارات'
            ], 500);
        }
    }

    /**
     * Get notification count for AJAX requests
     */
    public function getNotificationCount()
    {
        try {
            $user = Auth::guard('admin')->user();
            $unreadCount = $user->unreadNotifications->count();

            return response()->json([
                'success' => true,
                'count' => $unreadCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'count' => 0
            ], 500);
        }
    }
}
