<?php

namespace App\Services\Telegram\Commands\MillionaireBot;

use App\Exceptions\TGApiException;
use App\Services\Telegram\Bot\MiniBot;
use App\Services\Telegram\Commands\Command;
use App\Services\Telegram\DTO\UpdateMessage\Chat;
use App\Services\Telegram\Payloads\MessagePayload;

class StartCommand extends Command
{
    protected string $description = 'This is the starting command';

    private string $text = "Добро пожаловать в игру \"<b>Кто хочет стать миллионером</b>\"! 🤑 \n\n" .

"У вас есть шанс выиграть ответив на <b>10 вопросов</b>. Каждый вопрос имеет 4 варианта ответа, но <b>только один</b> из них правильный.\n".

"Вашей задачей будет выбрать <b>правильный</b> ответ на каждый вопрос.\n\n".

"Удачи в игре и пусть победит сильнейший! 🫡";

    /**
     * @param MiniBot $bot
     * @param Chat $chat
     * @return void
     * @throws TGApiException
     */
    public function execute(MiniBot $bot, Chat $chat): void
    {
        $bot->sendMessage(
            MessagePayload::create($chat->id, $this->text)
        );

        $bot->executeCommand(GameBarCommand::nameToCall());
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
