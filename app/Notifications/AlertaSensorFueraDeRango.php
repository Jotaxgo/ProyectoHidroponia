<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AlertaSensorFueraDeRango extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public array $messages)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // Enviaremos un email y también lo guardaremos en la base de datos
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mailMessage = (new MailMessage)
            ->subject('Alerta de Sensores en tu Cultivo')
            ->greeting('Hola,')
            ->line('Hemos detectado una o más condiciones fuera del rango ideal en uno de tus módulos de cultivo:');

        foreach ($this->messages as $message) {
            $mailMessage->line($message);
        }

        $mailMessage->action('Ir al Dashboard', url('/dashboard'))
            ->line('Te recomendamos revisar tu sistema lo antes posible.');

        return $mailMessage;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'messages' => $this->messages,
        ];
    }
}
