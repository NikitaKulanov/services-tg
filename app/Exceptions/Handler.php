<?php

namespace App\Exceptions;

use App\Services\Telegram\HttpClient\TGClient;
use App\Services\Telegram\HttpClient\TGClientHelper;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadeRequest;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }


    /**
     * @param Throwable $e
     * @return void
     * @throws Throwable
     */
    public function report(Throwable $e): void
    {
        if (!method_exists($e, 'report')) {
            $update = json_decode(FacadeRequest::getContent());

            /**
             * Отправка сообщения в чат, где произошла ошибка
             */
            if (Route::currentRouteName() === 'webhook') {
                if ($botName = Route::current()->parameter('botName')) {
                    if ($chatId = $update->message->chat->id ?? $update->callback_query->message->chat->id ?? null) {
                        TGClientHelper::sendText(
                            config("bot.settings.mini_bots.{$botName}.token"),
                            $chatId,
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
                "Произошла ошибка!" .
                "\nПриложение: " . config('app.name') .
                "\nТекст пользователя: " .
                ($update->message->text ?? $update->callback_query->data ?? 'Отсутствует') .
                "\nТекст: " . $e->getMessage() .
                "\nФайл: " . $e->getFile() .
                "\nСтрока: " . $e->getLine() .
                "\nКод: " . $e->getCode()
            );

        }

        parent::report($e);
    }

    /**
     * @param Request $request
     * @param Throwable $e
     * @return Response
     *
     * @throws Throwable
     */
    public function render($request, Throwable $e): Response
    {
        if (Route::currentRouteName() === 'webhook') {
            return response('', 204);
        }
        return parent::render($request, $e);
    }
}
