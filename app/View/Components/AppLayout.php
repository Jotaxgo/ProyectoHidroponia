<?php

namespace App\View\Components;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        $user = Auth::user();
        $notifications = $user ? $user->unreadNotifications : collect();

        return view('layouts.app', [
            'notifications' => $notifications
        ]);
    }
}
