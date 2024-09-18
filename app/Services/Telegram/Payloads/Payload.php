<?php

namespace App\Services\Telegram\Payloads;

use App\Services\Telegram\Payloads\Keyboards\Keyboard;

abstract class Payload
{
    const METHOD_API = null;

    protected int $chatId;

    protected string $parseMode = 'HTML';

    protected bool $disableWebPagePreview = false;

    protected array $keyboard = ['remove_keyboard' => true];

    /**
     * @return array
     */
    abstract public function getArrayForRequest(): array;

    /**
     * @param int $chatId
     * @return Payload
     */
    public function setChatId(int $chatId): Payload
    {
        $this->chatId = $chatId;
        return $this;
    }

    /**
     * @param string $parseMode
     * @return Payload
     */
    public function setParseMode(string $parseMode): Payload
    {
        $this->parseMode = $parseMode;
        return $this;
    }

    /**
     * @param bool $disableWebPagePreview
     * @return Payload
     */
    public function setDisableWebPagePreview(bool $disableWebPagePreview): Payload
    {
        $this->disableWebPagePreview = $disableWebPagePreview;
        return $this;
    }

    /**
     * @param Keyboard $keyboard
     * @return Payload
     */
    public function setKeyboard(Keyboard $keyboard): Payload
    {
        $this->keyboard = $keyboard->toArray();
        return $this;
    }
}
