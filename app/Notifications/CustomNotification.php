<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CustomNotification extends Notification
{
    use Queueable;

    public $message;

    /**
     * Crée une nouvelle notification d'instance.
     *
     * @param  string  $message
     * @return void
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Définir la notification pour le canal.
     *
     * @param  mixed  $notifiable
     * @return void
     */
    public function via($notifiable)
    {
        return ['database']; // Vous pouvez ajouter 'mail', 'database', etc.
    }

    /**
     * Construire le message de notification en utilisant le canal de base.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line($this->message)
                    ->action('Voir', url('/'))
                    ->line('Merci de votre attention!');
    }

    /**
     * Notification vers le canal database.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->message,
        ];
    }
}
