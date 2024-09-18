<?php

namespace App\Services\Telegram\Payloads;

use App\Services\Telegram\HttpClient\TGClient;

class MessagePayload extends Payload
{
    private string $text;

    const METHOD_API = TGClient::SEND_MESSAGE;

    /**
     * @param int $chatId
     * @param string $text
     */
    public function __construct(int $chatId, string $text)
    {
        $this->setChatId($chatId);
        $this->setText($text);
    }

    /**
     * @param int $chatId
     * @param string $text
     * @return MessagePayload
     */
    public static function create(int $chatId, string $text = ''): MessagePayload
    {
        return new self($chatId, $text);
    }

    /**
     * @param string $text
     * @return MessagePayload
     */
    public function setText(string $text): MessagePayload
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
            'parse_mode' => $this->parseMode,
            'disable_web_page_preview' => $this->disableWebPagePreview,
            'reply_markup' => $this->keyboard
        ];
    }
}
