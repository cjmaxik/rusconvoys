<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewComment extends Notification implements ShouldQueue
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
     * @var bool
     */
    private $in_timeout;

    /**
     * Create a new notification instance.
     *
     * @param \App\Models\User   $user
     * @param \App\Models\Convoy $convoy
     * @param  bool              $in_timeout
     */
    public function __construct($user, $convoy, $in_timeout)
    {
        $this->user       = $user;
        $this->convoy     = $convoy;
        $this->in_timeout = $in_timeout;
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
        if ($this->in_timeout) {
            return ['database'];
        }

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
        $url = route('convoy_show', ['slug' => $this->convoy->slug]) . '#commentsForConvoy';

        return (new MailMessage)
            ->success()
            ->subject("Новый комментарий к конвою \"{$this->convoy->getNormTitle()}\"")
            ->line("К конвою \"{$this->convoy->getNormTitle()}\", на который ты подписан, появился как минимум один новый комментарий.")
            ->action('Прочитать новые комментарии', $url)
            ->line('Удачи! И да прибудет с тобой конвой :)');
    }

    /**
     * Get the data representation of the notification.
     *
     * @param  mixed $notifiable
     *
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return new DatabaseMessage([
            'title' => $this->convoy->getNormTitle(),
            'slug'  => $this->convoy->slug,
        ]);
    }
}
