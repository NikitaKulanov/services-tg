<?php

namespace App\Providers;

use App\Services\Parser\TheGuardianParser;
use App\Services\Telegram\Bot\SmiBot;
use App\Services\Translator\GoogleTranslateForFree;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(SmiBot::class, function ($app) {
            return new SmiBot(
                theGuardianParser: $this->app->make(TheGuardianParser::class),
                googleTranslate: $this->app->make(GoogleTranslateForFree::class),
                botName: 'SMI',
            );
        });

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
