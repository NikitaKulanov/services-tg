<?php

namespace App\Services\Telegram\Payloads\Keyboards\Buttons;

class ReplyButton extends Button
{
    /**
     * @return ReplyButton
     */
    public static function create(): ReplyButton
    {
        return new self();
    }

    public function toArray(): array
    {
        if (isset($this->text)) { // Обязательные параметры
            return [
                'text' => $this->text,
            ];
        } else throw new \RuntimeException('Not all required properties are set for Button, Payload is not ready to send');
    }
}
