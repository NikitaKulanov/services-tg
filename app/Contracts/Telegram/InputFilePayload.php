<?php

namespace App\Contracts\Telegram;

use App\Services\Telegram\Payloads\InputFiles\InputFile;

interface InputFilePayload
{
    /**
     * @return InputFile[]|null
     */
    public function getContentForAttach(): ?array;

    /**
     * @return bool
     */
    public function hasFile(): bool;
}
