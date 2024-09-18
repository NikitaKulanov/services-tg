<?php

namespace App\Services\Telegram\Payloads;

use App\Contracts\Telegram\InputFilePayload;
use App\Services\Telegram\HttpClient\TGClient;
use App\Services\Telegram\Payloads\InputFiles\InputFile;
use App\Services\Telegram\Payloads\InputFiles\InputVideo;

class MediaGroupPayload extends Payload implements InputFilePayload
{
    /**
     * @var InputFile[]
     */
    private array $inputFiles;

    const METHOD_API = TGClient::SEND_MEDIA_GROUP;

    public function __construct(int $chatId, array $inputFiles)
    {
        $this->chatId = $chatId;
        empty($inputFiles)?
            throw new \RuntimeException('There are no files to transfer, the array is empty') :
            $this->inputFiles = $inputFiles;
    }

    /**
     * @param int $chatId
     * @param InputFile[] $inputFiles
     * @return MediaGroupPayload
     */
    public static function create(int $chatId, array $inputFiles): MediaGroupPayload
    {
        return new self($chatId, $inputFiles);
    }

    /**
     * @return array
     */
    public function getArrayForRequest(): array
    {
        $media = [];

        foreach ($this->inputFiles as $file) {
            $array = [
                'type' => $file::TYPE,
                'media' => 'attach://' . $file->getFilename(),
            ];

            if ($caption = $file->getCaption()) {
                $array['caption'] = $caption;
            }

            if ($file instanceof InputVideo)
                $array['thumbnail'] = 'attach://' . $file->getThumbnail()->getFilename();

            $media[] = $array;
        }

        return [
            'chat_id' => $this->chatId,
            'reply_markup' => json_encode($this->keyboard),
            'media' => json_encode($media)
        ];
    }

    /**
     * @return InputFile[]
     */
    public function getContentForAttach(): array
    {
        $array = [];

        foreach ($this->inputFiles as $file) {
            $file->setTitle($file->getFilename());
            $array[] = $file->toArrayForAttach();
            if ($file instanceof InputVideo) {
                $file->getThumbnail()->setTitle($file->getThumbnail()->getFilename());
                $array[] = $file->getThumbnail()->toArrayForAttach();
            }
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
}
