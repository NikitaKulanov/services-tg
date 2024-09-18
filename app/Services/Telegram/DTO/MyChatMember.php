<?php

namespace App\Services\Telegram\DTO;

class MyChatMember
{
    /**
     * @param Sender $sender From. Отправитель. Бот или пользователь. Может быть пустым в каналах.
     * @param Chat $chat Диалог, в котором было отправлено сообщение
     * @param int $date Дата отправки сообщения (Unix time)
     * @param NewChatMember $newChatMember Новая информация об участнике чата
     */
    public function __construct(
        public readonly Sender $sender,
        public readonly Chat $chat,
        public readonly int $date,
        public readonly NewChatMember $newChatMember,
    )
    {
    }
}
