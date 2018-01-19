<?php

namespace App\Listeners\PostInSocial;

use App\Events\ConvoyHasBeenPublished;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Log;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram as TelegramBot;

/**
 * Class Telegram
 *
 * @package App\Listeners\PostInSocial
 */
class Telegram implements ShouldQueue
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
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function handle(ConvoyHasBeenPublished $event)
    {
        $text = "Опубликован конвой от {$event->nickname}: {$event->link}";

        try {
            self::telegram($text);
        } catch (\RuntimeException $e) {
            Log::error($e);
        }
    }


    /**
     * @param $text
     *
     * @return string
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    private static function telegram($text)
    {
        new TelegramBot(config('social-sharing.telegram.api_key'), config('social-sharing.telegram.bot_name'));

        $data = [
            'chat_id' => config('social-sharing.telegram.chat_id'),
            'text'    => $text,
        ];

        $result = Request::sendMessage($data);
        if (!$result->isOk()) {
            throw new \RuntimeException("There is an error with Telegram request");
        } else {
            return "Telegram is OK";
        }
    }
}
