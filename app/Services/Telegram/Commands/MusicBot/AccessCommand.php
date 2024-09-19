<?php

namespace App\Services\Telegram\Commands\MusicBot;

use App\Services\Telegram\Bot\MiniBot;
use App\Services\Telegram\Commands\Command;
use App\Services\Telegram\DTO\UpdateMessage\Chat;
use App\Services\Telegram\Payloads\Keyboards\Buttons\InlineButton;
use App\Services\Telegram\Payloads\Keyboards\InlineKeyboard;
use App\Services\Telegram\Payloads\MessagePayload;

class AccessCommand extends Command
{
    protected string $description = 'null';

    public function execute(MiniBot $bot, Chat $chat): void
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
