<?php

use App\Http\Controllers\TGController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/setWebhookBot/{botName}/{action}', [TGController::class, 'setWebhookBot']);
Route::get('/setWebhooksBots', [TGController::class, 'setWebhooksBots']);
Route::any('/shutdownBot/{botName}', [TGController::class, 'shutdownBot'])->middleware('rules.tg.bot');
Route::any('/bot/{botName}', [TGController::class, 'messageBot'])
    ->middleware('rules.tg.bot')->name('webhook');
