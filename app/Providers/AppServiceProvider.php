<?php

namespace App\Providers;

use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;
use Schema;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Validator::extend('urlmime', 'App\Validators\UrlMimeValidator@validate');

//        DB::listen(function ($query) {
//             Log::debug($query->sql);
//        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(IdeHelperServiceProvider::class);
        }

        $this->app->bind('dateLoc', function () {
            /** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
            return new \App\Helpers\dateLoc;
        });

        if ($this->app->environment() === 'production') {
            $this->app->alias('bugsnag.multi', \Illuminate\Contracts\Logging\Log::class);
            $this->app->alias('bugsnag.multi', LoggerInterface::class);
        }
    }
}
