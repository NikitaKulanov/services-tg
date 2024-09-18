<?php

namespace App\Services\Telegram\Payloads\InputFiles;

use Illuminate\Support\Facades\Storage;

class InputVideo extends InputFile
{
    private InputPhoto $thumbnail;

    const TYPE = 'video';

    public function __construct(string $filename, InputPhoto $thumbnail)
    {
        if (Storage::exists('video/' . $filename)) {
            $this->contents = Storage::get('video/' . $filename);
            $this->filename = $filename;
        } else throw new \RuntimeException('File not found in storage');

        $this->thumbnail = $thumbnail;
        $this->title = self::TYPE;
    }

    /**
     * @param string $filename
     * @param string $thumbnail
     * @return InputVideo
     */
    public static function create(string $filename, string $thumbnail = 'default_video.jpg'): InputVideo
    {
        return new self($filename, InputPhoto::create($thumbnail));
    }

    /**
     * @return array
     */
    public function toArrayForAttach(): array
    {
        return [$this->title, $this->contents, $this->filename];
    }

    public function getThumbnail(): InputPhoto
    {
        return $this->thumbnail;
    }
}
