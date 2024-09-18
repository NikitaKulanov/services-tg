<?php

namespace App\Services\Telegram\Commands\Basic;

use App\Exceptions\TGApiException;
use App\Services\Telegram\Bot\Bot;
use App\Services\Telegram\Commands\Command;
use App\Services\Telegram\DTO\Chat;

class BackCommand extends Command
{
    /**
     * @param Bot $bot
     * @param Chat $chat
     * @return void
     * @throws TGApiException
     */
    public function execute(Bot $bot, Chat $chat): void
    {
        $bot->executeCommand($bot->getPastCommand(BeginStartCommand::nameToCall()));
    }

    /**
     * @return string
     */
    static function nameToCall(): string
    {
        return '/back';
    }

    /**
     * @return string|null
     */
    public static function getPastCommand(): ?string
    {
        return null;
    }
}
