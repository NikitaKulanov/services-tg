<?php

namespace App\Services\Telegram\Commands\Basic;

use App\Services\Telegram\Bot\MiniBot;
use App\Services\Telegram\Commands\Command;
use App\Services\Telegram\DTO\UpdateMessage\Chat;
use App\Services\Telegram\Payloads\MessagePayload;
use function config;


class UnknownCommand extends Command
{
    const NAME_TO_CALL = '/unknown';

    /**
     * @param MiniBot $bot
     * @param Chat $chat
     * @return void
     */
    public function execute(MiniBot $bot, Chat $chat): void
    {
//        Получить данные для обработки
//        $bot->getCommandToExecute();
//        if ($user->getFromStorage('completed_command')) {
//
//        }

        $bot->sendMessage(
            MessagePayload::create($chat->id, config(
                'bot.settings.message_unknown_command',
                'Простите, я вас не понял'
            ))
        );

    }

    /**
     * @return string
     */
    static function nameToCall(): string
    {
        return '/unknown';
    }

    /**
     * @return string|null
     */
    public static function getPastCommand(): ?string
    {
        return null;
    }
}
