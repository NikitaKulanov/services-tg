<?php

namespace App\Services\Telegram\Payloads\Keyboards\Buttons;

abstract class Button
{
    protected string $text;

    /**
     * @param string $text
     * @return Button
     */
    public function setText(string $text): Button
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return Button
     */
    abstract public static function create(): Button;

    /**
     * @return array
     */
    abstract public function toArray(): array;
}
