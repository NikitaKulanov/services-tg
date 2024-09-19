<?php

namespace App\Services\Telegram\Commands\Basic;

use App\Services\Telegram\Bot\MiniBot;
use App\Services\Telegram\Commands\Command;
use App\Services\Telegram\DTO\UpdateMessage\Chat;
use App\Services\Telegram\Payloads\Keyboards\Buttons\InlineButton;
use App\Services\Telegram\Payloads\Keyboards\InlineKeyboard;
use App\Services\Telegram\Payloads\MessagePayload;

class SubscribeCommand extends Command
{
    /**
     * @param MiniBot $bot
     * @param Chat $chat
     * @return void
     */
    public function execute(MiniBot $bot, Chat $chat): void
    {
        if (array_key_exists('channel_subscriptions', $bot->config)) {
            $keyboardButton = [];
            foreach ($bot->config['channel_subscriptions'] as $channel) {
                $keyboardButton[] = [
                    InlineButton::create()
                        ->setText('Подписаться на канал: ' . $channel['title'])
                        ->setCallbackData('подписаться')
                        ->setUrl($channel['url'])
                ];
            }
            $keyboardButton[] = [
                InlineButton::create()
                    ->setText('Я подписался ✅')
                    ->setCallbackData(ConfirmSubscriptionCommand::nameToCall())
            ];
            $result = $bot->sendMessage(
                MessagePayload::create($chat->id, 'Для продолжения, пожалуйста, подпишись! 🙏')
                    ->setKeyboard(InlineKeyboard::create()->setKeyboardButton($keyboardButton))
            );

            $bot->addMessageIdForDelete($result['message_id']);

        } else {
            $bot->sendMessage(
                MessagePayload::create($chat->id, 'Вы уже на всё подписаны')
            );
        }
    }

    /**
     * @return string
     */
    static function nameToCall(): string
    {
        return '/subscribe';
    }

    /**
     * @return string|null
     */
    public static function getPastCommand(): ?string
    {
        return null;
    }
}
