<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConvoyHasBeenCancelled extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var \App\Models\User
     */
    private $user;

    /**
     * @var \App\Models\Convoy
     */
    private $convoy;

    /**
     * Create a new notification instance.
     *
     * @param $user
     * @param $convoy
     */
    public function __construct($user, $convoy)
    {
        $this->user   = $user;
        $this->convoy = $convoy;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return !$this->user->subscribe ? ['database'] : ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $title = $this->convoy->getNormTitle();
        $line  = 'Оповещаем тебя о том, что ';

        if (str_contains($title, ['конвой', 'Конвой'])) {
            $subject = $title;
            $line    .= '**' . $title . '**';
        } else {
            $subject = 'Конвой "' . $title . '"';
            $line    .= 'конвой **' . $title . '**';
        }

        $line .= '. был отменен.';

        $cancelledMessage = $this->convoy->cancelled_message;

        return (new MailMessage)
            ->error()
            ->subject("{$subject} отменен")
            ->line($line)
            ->line('Причина: "' . $cancelledMessage . '"')
            ->line('На сайте еще есть конвои, в которых можно принять участие :)');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     *
     * @return array
     */
    public function toDatabase($notifiable)
    {
        $title = $this->convoy->getNormTitle();
        if (str_contains($title, ['конвой', 'Конвой'])) {
            $subject = $title;
        } else {
            $subject = 'Конвой "' . $title . '"';
        }

        return new DatabaseMessage([
            'title'   => $subject,
            'message' => $this->convoy->cancelled_message,
        ]);
    }
}
