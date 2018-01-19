<?php

namespace App\Listeners\PostInSocial;

use App\Events\ConvoyHasBeenPublished;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Log;

/**
 * Class Discord
 *
 * @package App\Listeners\PostInSocial
 */
class Discord implements ShouldQueue
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
        $text = "Опубликован конвой от {$event->nickname}: {$event->link}";

        try {
            self::discord($text);
        } catch (\RuntimeException $e) {
            Log::error($e);
        }
    }

    /**
     * @param $text
     *
     * @return bool
     */
    private static function discord($text)
    {
        $url = config('social-sharing.discord.webhook_url');

        $data = [
            'content' => $text,
        ];

        $data_string = json_encode($data);

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

        $output = curl_exec($curl);
        $output = json_decode($output, true);

        if (curl_getinfo($curl, CURLINFO_HTTP_CODE) != 204) {
            throw new \RuntimeException($output['message']);
        }

        curl_close($curl);

        return true;

    }

}
