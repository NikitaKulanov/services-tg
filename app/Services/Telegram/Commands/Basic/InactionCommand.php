<?php

namespace App\Services\Telegram\Commands\Basic;

use App\Services\Telegram\Bot\Bot;
use App\Services\Telegram\Commands\Command;
use App\Services\Telegram\DTO\Chat;

class InactionCommand extends Command
{
    /**
     * @param Bot $bot
     * @param Chat $chat
     * @return void
     */
    public function execute(Bot $bot, Chat $chat): void
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
