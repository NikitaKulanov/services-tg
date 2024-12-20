<?php

namespace App\Services\Telegram\HttpClient;

use App\Contracts\Telegram\InputFilePayload;
use App\Exceptions\TGApiException;
use App\Services\Telegram\Payloads\Payload;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class TGClient
{
    const SET_WEBHOOK = 'setWebhook';
    const SEND_MESSAGE = 'sendMessage';
    const SEND_PHOTO = 'sendPhoto';
    const SEND_VIDEO = 'sendVideo';
    const SEND_MEDIA_GROUP = 'sendMediaGroup';
    const EDIT_MESSAGE_TEXT = 'editMessageText';
    const EDIT_MESSAGE_MEDIA = 'editMessageMedia';
    const GET_CHAT_MEMBER = 'getChatMember';
    const DELETE_MASSAGE = 'deleteMessage';

    private ?string $urlBot;

    public function __construct(
        ?string      $token = null
    )
    {
        if (!is_null($token)) {
            $this->urlBot = 'https://api.telegram.org/bot' . $token;
        }
    }

    public function setToken(string $token): void
    {
        $this->urlBot = 'https://api.telegram.org/bot' . $token;
    }

    /**
     * @throws TGApiException
     */
    private function checkException(Response $response, array $payload, string $methodAPI): void
    {
        if ($response->serverError() or $response->failed()) {
            $exception = new TGApiException('Incorrect call to TG API');
            $exception->chatIdUser = $payload['chat_id'];
            $exception->methodAPI = $methodAPI;
            $exception->responseJson = $response->body();
            $exception->requestJson = json_encode($payload, JSON_UNESCAPED_UNICODE);
            throw $exception;
        }
    }

    public function sendMessageText(int $chatId, string $text): Response
    {
        return Http::post(
            $this->urlBot . '/' . self::SEND_MESSAGE,
            [
                'chat_id' => $chatId,
                'text' => $text,
            ],
        );
    }

    /**
     * @throws TGApiException
     */
    public function sendPayload(Payload $payload): Response
    {
        if ($payload instanceof InputFilePayload and $payload->hasFile()) {
            $response = Http::attach($payload->getContentForAttach())
                ->post(
                    $this->urlBot . '/' . $payload::METHOD_API,
                    $payload->getArrayForRequest()
                );
        } else {
            $response = Http::post(
                $this->urlBot . '/' . $payload::METHOD_API,
                $payload->getArrayForRequest()
            );
        }

        $this->checkException(
            $response,
            $payload->getArrayForRequest(),
            $payload::METHOD_API
        );

        return $response;
    }

    public function setWebhook(string $url): Response
    {
        $response = Http::get(
            $this->urlBot . '/' . self::SET_WEBHOOK,
            ['url' => $url]
        );

//        $this->checkException(
//            $response,
//            ['url' => $url],
//            'setWebhook'
//        );

        return $response;
    }

    /**
     * @throws TGApiException
     */
    public function checkChannelSubscription(string $channelId, int $userId, int $userChatId): Response
    {
        $response = Http::post(
            $this->urlBot . '/' . self::GET_CHAT_MEMBER,
            [
                'chat_id' => $channelId,
                'user_id' => $userId,
            ]
        );

        $this->checkException(
            $response,
            ['chat_id' => $userChatId, 'user_id' => $userId],
            self::GET_CHAT_MEMBER
        );

        return $response;
    }

    /**
     * @throws TGApiException
     */
    public function deleteMassage(int $chatId, int $messageId, int $userId): Response
    {
        $response = Http::post(
            $this->urlBot . '/' . self::DELETE_MASSAGE,
            [
                'chat_id' => $chatId,
                'message_id' => $messageId,
            ]
        );

        $this->checkException(
            $response,
            ['chat_id' => $chatId, 'message_id' => $messageId],
            self::DELETE_MASSAGE
        );

        return $response;
    }
}
