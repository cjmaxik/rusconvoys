<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConvoyIsWaiting extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var \App\Models\User
     */
    protected $user;

    /**
     * @var \App\Models\Convoy
     */
    protected $convoy;

    /**
     * Create a new notification instance.
     *
     * @param \App\Models\User   $user
     * @param \App\Models\Convoy $convoy
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
        return ($this->user->subscribe and isset($this->user->email)) ? ['database', 'mail'] : ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     *
     * @return \App\Notifications\ConvoyIsWaiting|\Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $title = $this->convoy->getNormTitle();
        $line  = 'Оповещаем тебя о том, что менее чем через 30 минут начнется сбор на ';

        if (str_contains($title, ['конвой', 'Конвой'])) {
            $subject = html_entity_decode($title);
            $line    .= '**' . $title . '**';
        } else {
            $subject = 'Конвой "' . html_entity_decode($title) . '"';
            $line    .= 'конвой **' . $title . '**';
        }

        $line .= '. Не забудь зайти на страницу конвоя, чтобы уточнить свое присутствие, а также ознакомиться c возможными изменениями.';

        $url = route('convoy_show', ['slug' => $this->convoy->slug]);

        return (new MailMessage)
            ->success()
            ->subject("{$subject} - совсем скоро")
            ->line($line)
            ->action('Перейти к конвою', $url)
            ->line('Удачной дороги! И да прибудет с тобой конвой :)');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     *
     * @return array|\Illuminate\Notifications\Messages\DatabaseMessage
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
            'title' => $subject,
            'slug'  => $this->convoy->slug,
        ]);
    }
}
