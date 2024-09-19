<?php

namespace App\Services\Telegram\DTO\Builder;

use App\Services\Telegram\DTO\UpdateMessage\CallbackQuery;
use App\Services\Telegram\DTO\UpdateMessage\Chat;
use App\Services\Telegram\DTO\UpdateMessage\Message;
use App\Services\Telegram\DTO\UpdateMessage\MyChatMember;
use App\Services\Telegram\DTO\UpdateMessage\NewChatMember;
use App\Services\Telegram\DTO\UpdateMessage\Sender;
use App\Services\Telegram\DTO\UpdateMessage\Update;

class DTOUpdateBuilder
{
    static public function buildUpdateDTO(array $payload): Update
    {
        return new Update(
            updateId: $payload['update_id'],
            message: isset($payload['message']) ? self::buildMessageDTO($payload['message']) : null,
            callbackQuery: isset($payload['callback_query']) ? self::buildCallbackQueryDTO($payload['callback_query']) : null,
            myChatMember: isset($payload['my_chat_member']) ? self::buildMyChatMemberDTO($payload['my_chat_member']) : null,
        );
    }

    static public function buildCallbackQueryDTO(array $payload): CallbackQuery
    {
        return new CallbackQuery(
            id: $payload['id'],
            sender: isset($payload['from']) ? self::buildSenderDTO($payload['from']) : null,
            message: isset($payload['message']) ? self::buildMessageDTO($payload['message']) : null,
            data: $payload['data'] ?? null,
        );
    }

    static public function buildMessageDTO(array $payload): Message
    {
        return new Message(
            messageId: $payload['message_id'],
            sender: isset($payload['from']) ? self::buildSenderDTO($payload['from']) : null,
            chat: self::buildChatDTO($payload['chat']),
            date: $payload['date'],
            text: ($payload['text'] ?? false) ? $payload['text'] : null,
        );
    }

    static public function buildMyChatMemberDTO(array $payload): MyChatMember
    {
        return new MyChatMember(
            sender: isset($payload['from']) ? self::buildSenderDTO($payload['from']) : null,
            chat: self::buildChatDTO($payload['chat']),
            date: $payload['date'],
            newChatMember: self::builNewChatMemberDTO($payload['new_chat_member']),
        );
    }

    static public function buildSenderDTO(array $payload): Sender
    {
        return new Sender(
            id: $payload['id'],
            isBot: $payload['is_bot'],
            firstName: $payload['first_name'],
            lastName: $payload['last_name'] ?? null,
            userName: $payload['username'] ?? null,
            languageCode: $payload['language_code'] ?? null
        );
    }

    static public function buildChatDTO(array $payload): Chat
    {
        return new Chat(
            id: $payload['id'],
            type: $payload['type'],
            title: $payload['title'] ?? null,
            userName: $payload['username'] ?? null,
            firstName: $payload['first_name'] ?? null,
            lastName: $payload['last_name'] ?? null,
        );
    }

    static public function builNewChatMemberDTO(array $payload): NewChatMember
    {
        return new NewChatMember(
            status: $payload['status'],
        );
    }
}
