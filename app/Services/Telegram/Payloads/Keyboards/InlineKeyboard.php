<?php

namespace App\Services\Telegram\Payloads\Keyboards;

use App\Services\Telegram\Payloads\Keyboards\Buttons\InlineButton;

class InlineKeyboard extends Keyboard
{
    /**
     * @return Keyboard
     */
    public static function create(): Keyboard
    {
        return new self();
    }

    /**
     * @param InlineButton[][] $keyboard
     * @return InlineKeyboard
     */
    public function setKeyboardButton(array $keyboard): InlineKeyboard
    {
        foreach ($keyboard as $array) {
            if (is_array($array)) {
                foreach ($array as $item) {
                    if (!($item instanceof InlineButton))
                        throw new \RuntimeException(
                            'Not all array elements belong to the InlineButton class'
                        );
                }
            } else throw new \RuntimeException('Array does not contain arrays');
        }
        $this->keyboard = $keyboard;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        if (isset($this->keyboard)) { // Обязательные параметры
            return [
                'inline_keyboard' => Keyboard::getWholeArrayKeyboard($this),
            ];
        } else throw new \RuntimeException(
            'Not all required properties are set for Keyboard, Payload is not ready to send'
        );
    }


}
