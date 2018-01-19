<?php

namespace App\Listeners\PostInSocial;

use App\Events\ConvoyHasBeenPublished;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Log;
use Slack;

/**
 * Class Slack
 *
 * @package App\Listeners\PostInSocial
 */
class SlackDev implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ConvoyHasBeenPublished $event
     *
     * @return void
     */
    public function handle(ConvoyHasBeenPublished $event)
    {
        $text = "Опубликован новый конвой: <{$event->slack_link}|`{$event->title}`> от {$event->nickname}";

        try {
            Slack::send($text);
        } catch (\RuntimeException $e) {
            Log::error($e);
        }
    }
}
