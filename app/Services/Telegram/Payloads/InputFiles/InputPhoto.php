<?php

namespace App\Services\Telegram\Payloads\InputFiles;

use Illuminate\Support\Facades\Storage;

class InputPhoto extends InputFile
{
    const TYPE = 'photo';

    public function __construct(string $filename)
    {
        if (Storage::exists('img/' . $filename)) {
            $this->contents = Storage::get('img/' . $filename);
            $this->filename = $filename;
        } else throw new \RuntimeException('File not found in storage');

        $this->title = self::TYPE;
    }

    /**
     * @param string $filename
     * @return InputPhoto
     */
    public static function create(string $filename): InputPhoto
    {
        return new self($filename);
    }

    /**
     * @return array
     */
    public function toArrayForAttach(): array
    {
        return [$this->title, $this->contents, $this->filename];
    }
}
