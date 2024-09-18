<?php

namespace App\Services\Telegram\DTO;

class Message
{
    /**
     * @param int $messageId Уникальный идентификатор сообщения
     * @param Sender|null $sender From. Отправитель. Бот или пользователь. Может быть пустым в каналах.
     * @param Chat $chat Диалог, в котором было отправлено сообщение
     * @param int $date Дата отправки сообщения (Unix time)
     * @param string|null $text Для текстовых сообщений: текст сообщения, 0-4096 символов
     */
    public function __construct(
        public readonly int $messageId,
        public readonly ?Sender $sender,
        public readonly Chat $chat,
        public readonly int $date,
        public readonly ?string $text
    )
    {
    }
}
