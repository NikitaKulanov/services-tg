<?php

namespace App\Services\Telegram\Commands\AdapterBot;

use App\Services\Telegram\Bot\MiniBot;
use App\Services\Telegram\Commands\Command;
use App\Services\Telegram\DTO\UpdateMessage\Chat;

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
        // 🥰🥸🫡🤝🫶 р
        $bot->sendMessage(
            MessagePayload::create($chat->id, "Привет! Я - твой помощник для создания бота \"Переходника\" 🥰\n\n".
        "С моей помощью ты сможешь создать персонального бота, который будет направлять пользователей на указанный тобою ресурс. (Например группу)\n\n".
        "Просто напиши мне @ZagornovD, и я сделаю для тебя такого бота! 🫶\n\n".
        "Прайс:\n".
        "Если мы используем токен с твоего аккаунта: 200 руб.\n".
        "Если мы используем токен с моего аккаунта: 200 руб / месяц.\n\n".
                "Если ничего не понимаешь, напиши мне, я всё объясню! 🤝"
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
