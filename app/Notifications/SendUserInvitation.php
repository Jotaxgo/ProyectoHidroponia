<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendUserInvitation extends Notification
{
    use Queueable;

    protected $token;

    /**
     * Create a new notification instance.
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // El enlace que irá en el botón del correo
        $url = url(route('invitation.set-password', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
                    ->subject('¡Has sido invitado a Hidrofrutilla!')
                    ->line('Un administrador te ha creado una cuenta en nuestro sistema.')
                    ->line('Por favor, haz clic en el botón de abajo para establecer tu contraseña y activar tu cuenta.')
                    ->action('Establecer Contraseña', $url)
                    ->line('Este enlace de invitación expirará en 60 minutos.')
                    ->line('Si no has solicitado esta invitación, no se requiere ninguna acción.');
    }
}