<?php

namespace App\Providers;

use App\Models\Convoy;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\ConvoyHasBeenPublished' => [
            'App\Listeners\PostInSocial\SlackDev',
            'App\Listeners\PostInSocial\Telegram',
            'App\Listeners\PostInSocial\Discord',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        // Convoy Observers
        Convoy::observe(Convoy\ConvoySlugObserver::class);
        Convoy::observe(Convoy\ConvoyImageObserver::class);

        // User Observers
        User::observe(User\AvatarDownloadObserver::class);
        User::observe(User\UserSlugObserver::class);

        parent::boot();
    }
}
