<?php

namespace App\Services\Telegram\Commands\MusicBot;

use App\Services\Telegram\Bot\Bot;
use App\Services\Telegram\Commands\Command;
use App\Services\Telegram\DTO\Chat;
use App\Services\Telegram\Payloads\Keyboards\Buttons\InlineButton;
use App\Services\Telegram\Payloads\Keyboards\InlineKeyboard;
use App\Services\Telegram\Payloads\MessagePayload;

class AccessCommand extends Command
{
    protected string $description = 'null';

    public function execute(Bot $bot, Chat $chat): void
    {
        $bot->sendMessage(
            MessagePayload::create($chat->id, "Держи 😎 Наслаждайся музыкой ❤"
            )->setKeyboard(
                InlineKeyboard::create()->setKeyboardButton([
                    [
                        InlineButton::create()->setText("Finder Music ⚡ БОТ")->setCallbackData('переход')->setUrl('https://t.me/fmusbot'),
                    ],
                ])
            )
        );
    }

    /**
     * @return string
     */
    static function nameToCall(): string
    {
        return '/access';
    }

    /**
     * @return string|null
     */
    public static function getPastCommand(): ?string
    {
        return null;
    }
}
