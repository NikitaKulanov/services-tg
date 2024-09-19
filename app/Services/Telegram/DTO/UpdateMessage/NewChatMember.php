<?php

namespace App\Services\Telegram\DTO\UpdateMessage;

class NewChatMember
{
    /**
     * @param string $status Статус участника в чате
     */
    public function __construct(
        public readonly string $status,
    )
    {
    }
}
