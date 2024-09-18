<?php

namespace App\Services\Telegram\Payloads\Keyboards\Buttons;

class InlineButton extends Button
{
    protected ?string $url = null;
    protected ?string $callbackData = null;

    /**
     * @return InlineButton
     */
    public static function create(): InlineButton
    {
        return new self();
    }

    /**
     * @param string|null $url
     * @return InlineButton
     */
    public function setUrl(?string $url): InlineButton
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @param string|null $callbackData
     * @return InlineButton
     */
    public function setCallbackData(?string $callbackData): InlineButton
    {
        $this->callbackData = $callbackData;
        return $this;
    }

    public function toArray(): array
    {
        if (isset($this->text) and isset($this->callbackData)) { // Обязательные параметры
            $array = [
                'text' => $this->text,
                'callback_data' => $this->callbackData,
            ];

            if ($this->url) $array['url'] = $this->url;

            return $array;
        } else throw new \RuntimeException('Not all required properties are set for Button, Payload is not ready to send');
    }
}
