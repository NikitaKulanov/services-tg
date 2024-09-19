<?php

namespace App\Services\Telegram\Commands\FileCheckBot;

use App\Services\Telegram\Bot\MiniBot;
use App\Services\Telegram\Commands\Command;
use App\Services\Telegram\DTO\UpdateMessage\Chat;
use App\Services\Telegram\Payloads\Keyboards\Buttons\InlineButton;
use App\Services\Telegram\Payloads\Keyboards\InlineKeyboard;
use App\Services\Telegram\Payloads\MessagePayload;

class StartCommand extends Command
{
    protected string $description = 'This is the starting command';

    /**
     * @param MiniBot $bot
     * @param Chat $chat
     * @return void
     */
    public function execute(MiniBot $bot, Chat $chat): void
    {
        $bot->sendMessage(
            MessagePayload::create($chat->id, "Привет! Я твой помощник в борьбе с вирусами! 🦠\n".
                "Здесь ты можешь получить доступ к другому боту, где сможешь проверить свой файл абсолютно <b>БЕСПЛАТНО!</b> 📄\n"
            )->setKeyboard(
                InlineKeyboard::create()->setKeyboardButton([
                    [
                        InlineButton::create()->setText("Получить доступ БЕСПЛАТНО ✨")->setCallbackData('/access'),
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
        return '/start';
    }

    /**
     * @return string|null
     */
    public static function getPastCommand(): ?string
    {
        return null;
    }
}
