<?php

namespace App\Services\Telegram\Commands\HeroBot;

use App\Services\Telegram\Bot\Bot;
use App\Services\Telegram\Commands\Command;
use App\Services\Telegram\DTO\Chat;
use App\Services\Telegram\Payloads\InputFiles\InputPhoto;
use App\Services\Telegram\Payloads\Keyboards\Buttons\InlineButton;
use App\Services\Telegram\Payloads\Keyboards\InlineKeyboard;
use App\Services\Telegram\Payloads\PhotoPayload;

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
        $bot->sendPhoto(
            PhotoPayload::create($chat->id, InputPhoto::create('HeroBot/mst.png')
                ->setCaption("Кто ТЫ из героев комиксов <b>Marvel</b>? 😎
Пройди наш тест, чтобы понять, каким супергероем ты мог бы быть в киновселенной <b>Marvel</b> ⚡")
            )
                ->setKeyboard(
                    InlineKeyboard::create()->setKeyboardButton([
                        [
                            InlineButton::create()->setText('Пройти тест 🔥')->setCallbackData(TestBarCommand::nameToCall()),
                        ]
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
