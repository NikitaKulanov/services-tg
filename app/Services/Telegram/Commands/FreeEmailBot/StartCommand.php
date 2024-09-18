<?php

namespace App\Services\Telegram\Commands\FreeEmailBot;

use App\Services\Telegram\Bot\Bot;
use App\Services\Telegram\Commands\Command;
use App\Services\Telegram\DTO\Chat;
use App\Services\Telegram\HttpClient\TGClientHelper;
use App\Services\Telegram\Payloads\Keyboards\Buttons\InlineButton;
use App\Services\Telegram\Payloads\Keyboards\Buttons\ReplyButton;
use App\Services\Telegram\Payloads\Keyboards\InlineKeyboard;
use App\Services\Telegram\Payloads\Keyboards\ReplyKeyboard;
use App\Services\Telegram\Payloads\MessagePayload;

class StartCommand extends Command
{
    protected string $description = 'This is the starting command';

    /**
     * @param Bot $bot
     * @param Chat $chat
     * @return void
     */
    public function execute(Bot $bot, Chat $chat): void
    {
        $bot->sendMessage(
            MessagePayload::create($chat->id, "Привет! ✉ Бесплатный почтовый ящик ждёт тебя 🤝\n".
                "Получи доступ к другому боту БЕСПЛАТНО. Ты сможешь получить бесплатный email-адрес без регистрации. Всё легко и удобно — воспользуйся прямо сейчас! 📮"
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
