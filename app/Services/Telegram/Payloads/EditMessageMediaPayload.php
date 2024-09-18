<?php

namespace App\Services\Telegram\Payloads;

use App\Contracts\Telegram\InputFilePayload;
use App\Services\Telegram\HttpClient\TGClient;
use App\Services\Telegram\Payloads\InputFiles\InputFile;
use App\Services\Telegram\Payloads\InputFiles\InputVideo;

class EditMessageMediaPayload extends Payload implements InputFilePayload
{
    /**
     * @var InputFile
     */
    private InputFile $inputFile;

    private int $messageId;

    const METHOD_API = TGClient::EDIT_MESSAGE_MEDIA;

    public function __construct(int $chatId, int $messageId, InputFile $inputFile)
    {
        $this->chatId = $chatId;
        $this->inputFile = $inputFile;
        $this->messageId = $messageId;
    }

    /**
     * @param int $chatId
     * @param int $messageId
     * @param InputFile $inputFile
     * @return EditMessageMediaPayload
     */
    public static function create(int $chatId, int $messageId, InputFile $inputFile): EditMessageMediaPayload
    {
        return new self($chatId, $messageId, $inputFile);
    }

    /**
     * @return InputFile[]|null
     */
    public function getContentForAttach(): ?array
    {
        $array = [];

        $this->inputFile->setTitle($this->inputFile->getFilename());
        $array[] = $this->inputFile->toArrayForAttach();
        if ($this->inputFile instanceof InputVideo) {
            $this->inputFile->getThumbnail()->setTitle($this->inputFile->getThumbnail()->getFilename());
            $array[] = $this->inputFile->getThumbnail()->toArrayForAttach();
        }

        return $array;
    }

    /**
     * @return bool
     */
    public function hasFile(): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function getArrayForRequest(): array
    {
        $media = [
            'type' => $this->inputFile::TYPE,
            'media' => 'attach://' . $this->inputFile->getFilename(),
        ];

        if ($caption = $this->inputFile->getCaption()) {
            $media['caption'] = $caption;
            $media['parse_mode'] = $this->inputFile->getParseMode();
        }

        if ($this->inputFile instanceof InputVideo)
            $media['thumbnail'] = 'attach://' . $this->inputFile->getThumbnail()->getFilename();

        return [
            'chat_id' => $this->chatId,
            'message_id' => $this->messageId,
            'media' => json_encode($media),
            'reply_markup' => json_encode($this->keyboard),
        ];
    }
}
