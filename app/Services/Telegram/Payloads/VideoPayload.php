<?php

namespace App\Services\Telegram\Payloads;

use App\Contracts\Telegram\InputFilePayload;
use App\Services\Telegram\HttpClient\TGClient;
use App\Services\Telegram\Payloads\InputFiles\InputVideo;

class VideoPayload extends Payload implements InputFilePayload
{
    private InputVideo|string $inputVideo;
    private string $caption;

    const METHOD_API = TGClient::SEND_VIDEO;

    public function __construct(int $chatId, InputVideo|string $inputVideo)
    {
        $this->chatId = $chatId;
        $this->inputVideo = $inputVideo;
    }

    /**
     * @param int $chatId
     * @param InputVideo|string $inputVideo
     * @return VideoPayload
     */
    public static function create(int $chatId, InputVideo|string $inputVideo): VideoPayload
    {
        return new self($chatId, $inputVideo);
    }

    /**
     * @param string $caption
     * @return VideoPayload
     */
    public function setCaption(string $caption): VideoPayload
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
            'thumbnail' => 'attach://' . $this->inputVideo->getThumbnail()->getFilename(),
            'chat_id' => $this->chatId,
            'parse_mode' => $this->parseMode,
        ];

        if ($this->hasFile()) {
            if ($caption = $this->inputVideo->getCaption()) {
                $this->caption = $caption;
            }
            $array['reply_markup'] = json_encode($this->keyboard);
        } else {
            $array['video'] = $this->inputVideo;
            $array['reply_markup'] = $this->keyboard;

        }

        if (isset($this->caption)) $array['caption'] = $this->caption;

        return $array;
    }

    /**
     * @return array|null
     */
    public function getContentForAttach(): ?array
    {
        if ($this->hasFile()) {
            $array = [];
            $array[] = $this->inputVideo->toArrayForAttach();
            $this->inputVideo->getThumbnail()->setTitle($this->inputVideo->getThumbnail()->getFilename());
            $array[] = $this->inputVideo->getThumbnail()->toArrayForAttach();
            return $array;
        } else {
            return null;
        }
    }

    /**
     * @return bool
     */
    public function hasFile(): bool
    {
        return $this->inputVideo instanceof InputVideo;
    }
}
