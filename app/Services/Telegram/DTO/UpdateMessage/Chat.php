<?php

namespace App\Services\Telegram\DTO\UpdateMessage;

class Chat
{
    /**
     * @param int $id Уникальный идентификатор чата
     * @param string $type Тип чата: “private”, “group”, “supergroup” или “channel”
     * @param string|null $title Название, для каналов или групп
     * @param string|null $userName Username, для чатов и некоторых каналов
     * @param string|null $firstName Имя собеседника в чате
     * @param string|null $lastName Фамилия собеседника в чате
     */
    public function __construct(
        public readonly int $id,
        public readonly string $type,
        public readonly ?string $title,
        public readonly ?string $userName,
        public readonly ?string $firstName,
        public readonly ?string $lastName,
    )
    {
    }
}
