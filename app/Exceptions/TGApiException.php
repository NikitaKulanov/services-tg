<?php

namespace App\Exceptions;

use App\Services\Telegram\HttpClient\TGClientHelper;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class TGApiException extends Exception
{
    /**
     * ID чата, где произошла ошибка
     *
     * @var int
     */
    public int $chatIdUser;

    /**
     * Ответ TG
     *
     * @var string
     */
    public string $responseJson;

    /**
     * Payload отправленный в TG API
     *
     * @var string
     */
    public string $requestJson;

    /**
     * Метод отправленный в TG API
     *
     * @var string
     */
    public string $methodAPI;

    /**
     * Report or log an exception.
     *
     * @return void
     */
    public function report(): void
    {
        /**
         * Отправка сообщения в чат, где произошла ошибка
         */
        if (!App::runningInConsole()) {
            if ($botName = Route::current()->parameter('botName')) {
                if (isset($this->chatIdUser)) {
                    TGClientHelper::sendText(
                        config("bot.settings.mini_bots.{$botName}.token"),
                        $this->chatIdUser,
                        config(
                            'bot.settings.error_message_to_the_user',
                            'Произошла ошибка, приносим свои извинения, уже исправляем!'
                        )
                    );
                }
            }
        }

        /**
         * Отправка сообщения в группу с ошибками
         */
        TGClientHelper::error(
            'Произошла ошибка TGApiException при обращении к api TG! Подробнее в файле логов. Приложение ' . env('APP_NAME')
        );

        /**
         * Отправка сообщения в log файл
         */
        $log = $this->getMessage() . PHP_EOL;
        if (isset($this->methodAPI)) $log = $log . 'Method API: ' . $this->methodAPI . PHP_EOL;
        if (isset($this->responseJson)) $log = $log . 'Response: ' . $this->responseJson . PHP_EOL;
        if (isset($this->requestJson)) $log = $log . 'Request: ' . $this->requestJson . PHP_EOL;

        Log::channel('file_bot')->error(
            $log . 'Файл: ' . $this->getFile() . ' Строка: ' . $this->getLine() . ' Код: ' . $this->getCode() . PHP_EOL
        );
    }

    /**
     * @return Response
     */
    public function render(): Response
    {
        if (Route::currentRouteName() === 'webhook') {
            return response('', 204);
        } else return response('Произошла ошибка при обращении к api TG! Подробнее в файле логов.', 500);
    }
}
