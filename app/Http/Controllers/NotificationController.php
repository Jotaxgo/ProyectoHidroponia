<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $user = Auth::user();
        $notifications = $user->notifications()->paginate(10); // Get all notifications, paginate them

        return view('notifications.index', [
            'notifications' => $notifications,
        ]);
    }

    /**
     * Display the specified resource and mark it as read.
     */
    public function show(string $id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->findOrFail($id);

        $notification->markAsRead();

        if (request()->wantsJson()) {
            return response()->json(['status' => 'success']);
        }

        // Flash the specific notification data and its ID to the session to be displayed once.
        return redirect()->route('notifications.index')
            ->with('status', 'Notificación marcada como leída.')
            ->with('highlighted_notification', $notification->data)
            ->with('highlighted_id', $notification->id);
    }

    /**
     * Mark all unread notifications as read.
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();

        return Redirect::back()->with('status', 'Todas las notificaciones han sido marcadas como leídas.');
    }
}
