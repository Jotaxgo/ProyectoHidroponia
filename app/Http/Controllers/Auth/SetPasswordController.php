<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Auth;

class SetPasswordController extends Controller
{
    public function create(Request $request, string $token)
    {
        return view('auth.set-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

   public function store(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();

                event(new PasswordReset($user));

                // --- CAMBIO CLAVE #1: Iniciar Sesión ---
                Auth::login($user);
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            // --- CAMBIO CLAVE #2: Redirigir al Dashboard ---
            return redirect()->route('dashboard')
                             ->with('status', '¡Contraseña establecida! Has iniciado sesión.');
        }

        return back()->withInput($request->only('email'))
                     ->withErrors(['email' => __($status)]);
    }
}