<?php

namespace App\Services\Telegram\Payloads\Keyboards;

use App\Services\Telegram\Payloads\Keyboards\Buttons\ReplyButton;

class ReplyKeyboard extends Keyboard
{
    /**
     * @var bool
     * Всегда показывать клавиатуру
     */
    private bool $isPersistent = false;

    /**
     * @var bool
     * Подогнать высоту клавиатуры под количество кнопок
     */
    private bool $resizeKeyboard = false;

    /**
     * @var bool
     * Скрыть клавиатуру, как только она будет использована
     */
    private bool $oneTimeKeyboard = false;

    /**
     * @var string
     * Заполнитель, который будет отображаться в поле ввода, когда клавиатура активна; 1–64 символа
     */
    private string $inputFieldPlaceholder;

    /**
     * @return ReplyKeyboard
     */
    public static function create(): ReplyKeyboard
    {
        return new self();
    }

    /**
     * @param bool $isPersistent
     * @return ReplyKeyboard
     */
    public function setIsPersistent(bool $isPersistent): ReplyKeyboard
    {
        $this->isPersistent = $isPersistent;
        return $this;
    }

    /**
     * @param bool $resizeKeyboard
     * @return ReplyKeyboard
     */
    public function setResizeKeyboard(bool $resizeKeyboard): ReplyKeyboard
    {
        $this->resizeKeyboard = $resizeKeyboard;
        return $this;
    }

    /**
     * @param bool $oneTimeKeyboard
     * @return ReplyKeyboard
     */
    public function setOneTimeKeyboard(bool $oneTimeKeyboard): ReplyKeyboard
    {
        $this->oneTimeKeyboard = $oneTimeKeyboard;
        return $this;
    }

    /**
     * @param string $inputFieldPlaceholder
     * @return ReplyKeyboard
     */
    public function setInputFieldPlaceholder(string $inputFieldPlaceholder): ReplyKeyboard
    {
        $this->inputFieldPlaceholder = $inputFieldPlaceholder;
        return $this;
    }

    /**
     * @param ReplyButton[][] $keyboard
     * @return ReplyKeyboard
     */
    public function setKeyboardButton(array $keyboard): ReplyKeyboard
    {
        foreach ($keyboard as $array) {
            if (is_array($array)) {
                foreach ($array as $item) {
                    if (!($item instanceof ReplyButton))
                        throw new \RuntimeException(
                            'Not all array elements belong to the ReplyButton class'
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
            $array =  [
                'keyboard' => Keyboard::getWholeArrayKeyboard($this),
                'is_persistent' => $this->isPersistent,
                'resize_keyboard' => $this->resizeKeyboard,
                'one_time_keyboard' => $this->oneTimeKeyboard,
            ];

            if (isset($this->inputFieldPlaceholder)) $array['input_field_placeholder'] = $this->inputFieldPlaceholder;

            return $array;
        } else throw new \RuntimeException(
            'Not all required properties are set for Keyboard, Payload is not ready to send'
        );
    }
}
