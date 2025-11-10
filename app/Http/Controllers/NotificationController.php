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

        // 1. Calcular la página ANTES de marcarla como leída (el orden no cambia)
        $perPage = 10; // El mismo valor que usas en paginate() en el método index
        $allNotifications = $user->notifications()->latest()->pluck('id');
        $position = $allNotifications->search($notification->id);
        $page = $position !== false ? floor($position / $perPage) + 1 : 1;

        // 2. Marcar la notificación como leída
        $notification->markAsRead();

        if (request()->wantsJson()) {
            return response()->json(['status' => 'success']);
        }

        // 3. Redirigir a la página y ancla correctas
        return redirect()->route('notifications.index', ['page' => $page, '_fragment' => 'notification-' . $notification->id])
            ->with('status', 'Notificación marcada como leída.')
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
