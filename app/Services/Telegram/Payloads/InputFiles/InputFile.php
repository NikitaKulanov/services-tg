<?php

namespace App\Services\Telegram\Payloads\InputFiles;

abstract class InputFile
{
    protected string $filename;

    protected string $contents;

    protected string $caption;

    protected string $parseMode = 'HTML';

    const TYPE = null;

    protected string $title;

    /**
     * @param string $title
     * @return InputFile
     */
    public function setTitle(string $title): InputFile
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getParseMode(): string
    {
        return $this->parseMode;
    }

    /**
     * @param string $parseMode
     * @return InputFile
     */
    public function setParseMode(string $parseMode): InputFile
    {
        $this->parseMode = $parseMode;
        return $this;
    }

    /**
     * @param string|null $default
     * @return string|null
     */
    public function getCaption(?string $default = null): ?string
    {
        return $this->caption ?? $default;
    }

    /**
     * @param string $caption
     * @return InputFile
     */
    public function setCaption(string $caption): InputFile
    {
        $this->caption = $caption;
        return $this;
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @return string
     */
    public function getContents(): string
    {
        return $this->contents;
    }

    /**
     * @return array
     */
    abstract function toArrayForAttach(): array;
}
