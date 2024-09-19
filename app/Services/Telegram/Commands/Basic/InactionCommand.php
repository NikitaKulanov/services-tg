<?php

namespace App\Services\Telegram\Commands\Basic;

use App\Services\Telegram\Bot\MiniBot;
use App\Services\Telegram\Commands\Command;
use App\Services\Telegram\DTO\UpdateMessage\Chat;

class InactionCommand extends Command
{
    /**
     * @param MiniBot $bot
     * @param Chat $chat
     * @return void
     */
    public function execute(MiniBot $bot, Chat $chat): void
    {
        // Команда для бездействия
        return;
    }

    /**
     * @return string
     */
    static function nameToCall(): string
    {
        return '/inaction';
    }

    /**
     * @return string|null
     */
    public static function getPastCommand(): ?string
    {
        return null;
    }
}
