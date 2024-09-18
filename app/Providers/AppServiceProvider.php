<?php

namespace App\Providers;

use App\Services\Telegram\HttpClient\TGClient;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
//        $this->app->bind(TGClient::class, function ($app) {
//            return new TGClient(
//                token : config('bot.settings.base_token')
//            );
//        });

//        $this->app->bind(Bot::class, function ($app) {
//            return new Bot(
//                config: config('bot'),
//                requestClient: $this->app->make(TGClient::class),
//                request: $app['request']
//            );
//        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
