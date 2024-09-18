<?php

use App\Models\User;
use App\Services\Telegram\Payloads\Keyboards\Buttons\InlineButton;
use App\Services\Telegram\Payloads\Keyboards\Buttons\ReplyButton;
use App\Services\Telegram\Payloads\Keyboards\InlineKeyboard;
use App\Services\Telegram\Payloads\Keyboards\ReplyKeyboard;
use App\Services\Telegram\Payloads\MessagePayload;
use App\Services\Telegram\Payloads\PhotoPayload;
use Illuminate\Http\File;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return new Response(' Здесь живёт бот');
});
