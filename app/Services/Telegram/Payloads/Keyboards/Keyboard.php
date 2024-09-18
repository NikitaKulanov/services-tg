<?php

namespace App\Services\Telegram\Payloads\Keyboards;

use App\Services\Telegram\Payloads\Keyboards\Buttons\Button;

abstract class Keyboard
{
    /**
     * @var Button[][]
     */
    protected array $keyboard;

    /**
     * @param Button[][] $keyboard
     * @return Keyboard
     */
    public function setKeyboardButton(array $keyboard): Keyboard
    {
        foreach ($keyboard as $array) {
            if (is_array($array)) {
                foreach ($array as $item) {
                    if (!($item instanceof Button))
                        throw new \RuntimeException('Not all elements of the Button class are in the array');
                }
            } else throw new \RuntimeException('Array does not contain arrays');
        }
        $this->keyboard = $keyboard;
        return $this;
    }

    /**
     * @param Keyboard $keyboard
     * @return array
     * Получить массив с копками в виде массивов
     */
    public static function getWholeArrayKeyboard(Keyboard $keyboard): array
    {
        if (isset($keyboard->keyboard)) {
            return array_map(function($arrayKeyboard) {
                return array_map(function($item) {
                    return $item->toArray();
                }, $arrayKeyboard);
            }, $keyboard->keyboard);
        } else throw new \RuntimeException(
            'Keyboard has no buttons, Payload is not ready to send'
        );
    }

    /**
     * @return Keyboard
     */
    abstract public static function create(): Keyboard;

    /**
     * @return array
     */
    abstract public function toArray(): array;
}
