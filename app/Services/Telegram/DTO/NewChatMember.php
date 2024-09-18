<?php

namespace App\Services\Telegram\DTO;

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
