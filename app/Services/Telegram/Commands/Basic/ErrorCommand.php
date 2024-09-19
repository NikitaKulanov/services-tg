<?php

namespace App\Services\Telegram\Commands\Basic;

use App\Services\Telegram\Bot\MiniBot;
use App\Services\Telegram\Commands\Command;
use App\Services\Telegram\DTO\UpdateMessage\Chat;
use App\Services\Telegram\Payloads\MessagePayload;

class ErrorCommand extends Command
{
    /**
     * @param MiniBot $bot
     * @param Chat $chat
     * @return void
     */
    public function execute(MiniBot $bot, Chat $chat): void
    {
        $bot->sendMessage(
            MessagePayload::create($chat->id, config(
                'bot.settings.error_message_to_the_user',
                'Произошла ошибка, приносим свои извинения, уже исправляем!'
            ))
        );
    }

    /**
     * @return string
     */
    static function nameToCall(): string
    {
        return '/error';
    }

    /**
     * @return string|null
     */
    static public function getPastCommand(): ?string
    {
        return null;
    }
}
