<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()->latest()->paginate(15);
        return view('notifications.index', compact('notifications'));
    }

    public function markRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->update(['is_read' => true]);
        
        return back()->with('success', 'تم تحديد الإشعار كمقروء.');
    }
    
    public function markAllRead()
    {
        auth()->user()->notifications()->where('is_read', false)->update(['is_read' => true]);
        return back()->with('success', 'تم تحديد جميع الإشعارات كمقروءة.');
    }
}
