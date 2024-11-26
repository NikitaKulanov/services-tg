<?php

namespace App\Services\Telegram\Commands\Basic;

use App\Services\Telegram\Bot\MiniBot;
use App\Services\Telegram\Commands\Command;
use App\Services\Telegram\DTO\UpdateMessage\Chat;
use App\Services\Telegram\Payloads\MessagePayload;

class UserAnswerCommand extends Command
{
    /**
     * @param MiniBot $bot
     * @param Chat $chat
     * @return void
     */
    public function execute(MiniBot $bot, Chat $chat): void
    {
        $bot->sendMessage(
            MessagePayload::create($chat->id, $bot->getUpdate()->getPayload())
        );
        $bot->storageHelper->setWaitingBotAnswer(false);
    }

    /**
     * @return string
     */
    static function nameToCall(): string
    {
        return '/userAnswer';
    }

    /**
     * @return string|null
     */
    public static function getPastCommand(): ?string
    {
        return null;
    }
}
