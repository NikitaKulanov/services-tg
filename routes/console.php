<?php

use App\Http\Controllers\TGController;
use App\Services\Telegram\HttpClient\TGClient;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

//Artisan::command('set:webhook', function () {
//    $action = $this->ask('What mode? [activation/shutdown]', 'activation');
//    $this->comment('Start installing webhook...');
//    $response = app(TGController::class)->setWebhookBot(
//        app(\App\Services\Telegram\HttpClient\TGClient::class),
//        $action
//    );
//    $this->info('Status: ' . $response->status() . '. Payload: ' . $response->content());
//})->purpose('Set webhook for bot TG');

Artisan::command('set:webhooks', function () {
    $this->comment('Start installing webhook...');
    $response = app(TGController::class)->setWebhooksBots(
        app(TGClient::class),
    );
    foreach ($response as $item) {
        $this->info('Status: ' . $item->status() . '. Payload: ' . $item->content());
    }
})->purpose('Set webhooks for bot TG');

Artisan::command('update:news', function () {
    app(\App\Services\Telegram\Bot\SmiBot::class)->updateNews();
    $this->info('Successful');
})->purpose('Update news channel');
