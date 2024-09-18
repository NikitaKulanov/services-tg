<?php

namespace App\Services\Telegram\DTO;

class CallbackQuery
{
    public function __construct(
        public readonly int $id,
        public readonly ?Sender $sender,
        public readonly ?Message $message,
        public readonly ?string $data
    )
    {
    }
}
