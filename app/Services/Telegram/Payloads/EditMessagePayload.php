<?php

namespace App\Services\Telegram\Payloads;

use App\Services\Telegram\HttpClient\TGClient;

class EditMessagePayload extends Payload
{
    private int $messageId;

    private string $text;

    const METHOD_API = TGClient::EDIT_MESSAGE_TEXT;

    /**
     * @param int $chatId
     * @param int $messageId
     * @param string $text
     */
    public function __construct(int $chatId, int $messageId, string $text)
    {
        $this->setChatId($chatId);
        $this->setMessageId($messageId);
        $this->setText($text);
    }

    /**
     * @param int $chatId
     * @param int $messageId
     * @param string $text
     * @return EditMessagePayload
     */
    public static function create(int $chatId, int $messageId, string $text = ''): EditMessagePayload
    {
        return new self($chatId, $messageId, $text);
    }

    /**
     * @param int $messageId
     * @return EditMessagePayload
     */
    public function setMessageId(int $messageId): EditMessagePayload
    {
        $this->messageId = $messageId;
        return $this;
    }

    /**
     * @param string $text
     * @return EditMessagePayload
     */
    public function setText(string $text): EditMessagePayload
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return array
     */
    public function getArrayForRequest(): array
    {
        return [
            'text' => $this->text,
            'chat_id' => $this->chatId,
            'message_id' => $this->messageId,
            'parse_mode' => $this->parseMode,
            'reply_markup' => $this->keyboard
        ];
    }
}
