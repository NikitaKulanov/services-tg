<?php

namespace App\Services\Telegram\Commands\Basic;

use App\Exceptions\TGApiException;
use App\Services\Telegram\Bot\Bot;
use App\Services\Telegram\Commands\Command;
use App\Services\Telegram\DTO\Chat;
use App\Services\Telegram\HttpClient\TGClientHelper;
use App\Services\Telegram\Payloads\MessagePayload;

class ConfirmSubscriptionCommand extends Command
{

    /**
     * @param Bot $bot
     * @param Chat $chat
     * @return void
     * @throws TGApiException
     */
    public function execute(Bot $bot, Chat $chat): void
    {
        if ($bot->checkChannelSubscriptions()) {

            if (is_array($messagesId = $bot->getIdMessagesForDelete())) {
                foreach ($messagesId as $messageId) {
                    $bot->deleteMassage($chat->id, $messageId, $chat->id);
                }
                $bot->setDeleteForMessages();
            }

            $bot->sendMessage(
                MessagePayload::create($chat->id, 'Спасибо за подписку! 😊')
            );
            TGClientHelper::info("Кто-то подписался!");
            // Выполнить желаемую команду, до просьбы подписаться
            $bot->executeCommand(
                $bot->getDesiredCommand(BeginStartCommand::nameToCall())
            );
        } else {
            $result = $bot->sendMessage(
                MessagePayload::create($chat->id, 'Простите, но вы не подписались 🥲')
            );
            $bot->executeBasicCommand(SubscribeCommand::nameToCall());

            $bot->addMessageIdForDelete($result['message_id']);
        }
    }

    /**
     * @return string
     */
    static function nameToCall(): string
    {
        return '/confirmSubscription';
    }

    /**
     * @return string|null
     */
    static public function getPastCommand(): ?string
    {
        return null;
    }
}
