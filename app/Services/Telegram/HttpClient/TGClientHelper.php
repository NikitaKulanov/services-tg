<?php

namespace App\Services\Telegram\HttpClient;

use Illuminate\Support\Facades\Http;

abstract class TGClientHelper
{
//    config('bot.settings.base_token');

    public static function info(string $text): void
    {
        self::sendMessageText(
            config('bot.settings.chat_id_for_info') ??
                throw new \RuntimeException('chat_id_for_info is not specified in the settings'),
            $text
        );
    }

    public static function error(string $text): void
    {
        self::sendMessageText(
            config('bot.settings.chat_id_for_errors') ??
                throw new \RuntimeException('chat_id_for_errors is not specified in the settings'),
            $text
        );
    }

    public static function sendText(string $token, string $chatId, string $text): void
    {
        Http::post(
            'https://api.telegram.org/bot' . $token . '/' . TGClient::SEND_MESSAGE,
            [
                'chat_id' => $chatId,
                'text' => $text,
            ],
        );
    }

    private static function sendMessageText(string $chatId, string $text): void
    {
        Http::post(
            'https://api.telegram.org/bot' . config('bot.settings.base_token') . '/' . TGClient::SEND_MESSAGE,
            [
                'chat_id' => $chatId,
                'text' => $text,
            ],
        );
    }
}
