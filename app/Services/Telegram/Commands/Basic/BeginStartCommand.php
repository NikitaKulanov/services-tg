<?php

namespace App\Services\Telegram\Commands\Basic;

use App\Services\Telegram\Bot\Bot;
use App\Services\Telegram\Commands\Command;
use App\Services\Telegram\DTO\Chat;
use App\Services\Telegram\Payloads\MessagePayload;

class BeginStartCommand extends Command
{
    /**
     * @param Bot $bot
     * @param Chat $chat
     * @return void
     */
    public function execute(Bot $bot, Chat $chat): void
    {
        $bot->sendMessage(
            MessagePayload::create($chat->id, 'Пожалуйста, начни со /start')
        );
    }

    /**
     * @return string
     */
    static function nameToCall(): string
    {
        return '/beginStart';
    }

    /**
     * @return string|null
     */
    public static function getPastCommand(): ?string
    {
        return null;
    }
}
