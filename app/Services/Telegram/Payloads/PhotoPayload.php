<?php

namespace App\Services\Telegram\Payloads;

use App\Contracts\Telegram\InputFilePayload;
use App\Services\Telegram\HttpClient\TGClient;
use App\Services\Telegram\Payloads\InputFiles\InputPhoto;

class PhotoPayload extends Payload implements InputFilePayload
{
    private InputPhoto|string $inputPhoto;
    private string $caption;

    const METHOD_API = TGClient::SEND_PHOTO;

    public function __construct(int $chatId, InputPhoto|string $inputPhoto)
    {
        $this->inputPhoto = $inputPhoto;
        $this->chatId = $chatId;
    }

    /**
     * @param int $chatId
     * @param InputPhoto|string $inputPhoto
     * @return PhotoPayload
     */
    public static function create(int $chatId, InputPhoto|string $inputPhoto): PhotoPayload
    {
        return new self($chatId, $inputPhoto);
    }

    /**
     * @param string $caption
     * @return PhotoPayload
     */
    public function setCaption(string $caption): PhotoPayload
    {
        $this->caption = $caption;
        return $this;
    }

    /**
     * @return array
     */
    public function getArrayForRequest(): array
    {
        $array = [
            'chat_id' => $this->chatId,
            'parse_mode' => $this->parseMode,
        ];

        if ($this->hasFile()) {
            if ($caption = $this->inputPhoto->getCaption()) {
                $this->caption = $caption;
            }
            $array['reply_markup'] = json_encode($this->keyboard);
        } else {
            $array['photo'] = $this->inputPhoto;
            if (isset($this->keyboard)) $array['reply_markup'] = $this->keyboard;
        }

        if (isset($this->caption)) $array['caption'] = $this->caption;

        return $array;
    }

    /**
     * @return array|null
     */
    public function getContentForAttach(): ?array
    {
        return $this->hasFile() ? [$this->inputPhoto->toArrayForAttach()] : null;
    }

    /**
     * @return bool
     */
    public function hasFile(): bool
    {
        return $this->inputPhoto instanceof InputPhoto;
    }
}
