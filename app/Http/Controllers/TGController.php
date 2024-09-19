<?php

namespace App\Http\Controllers;

use App\Exceptions\TGApiException;
use App\Services\Telegram\Bot\MiniBot;
use App\Services\Telegram\Bot\StorageHelper;
use App\Services\Telegram\DTO\Builder\DTOUpdateBuilder;
use App\Services\Telegram\HttpClient\TGClient;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

class TGController extends Controller
{
    public function setWebhookBot(TGClient $TGClient, string $botName, string $action): Response
    {
        $path = match ($action) {
            'activation' => '/api/bot/' . $botName,
            'shutdown' => '/api/shutdownBot/' . $botName,
            default => throw new \RuntimeException('Wrong action'),
        };

        return $this->setWebhook($TGClient, $path, $botName);
    }

    public function setWebhooksBots(TGClient $TGClient): array
    {
        $responseArray = [];

        foreach (config('bot.settings.mini_bots') as $botName => $settings) {
            $responseArray[] = $this->setWebhook($TGClient, '/api/bot/' . $botName, $botName);
        }

        return $responseArray;
    }

    private function setWebhook(TGClient $TGClient, string $path, string $botName): Response
    {
        $url = config('bot.settings.url_webhook') ?? throw new \RuntimeException('url_webhook not correct');

        $TGClient->setToken(config('bot.settings.mini_bots.' . $botName . '.token'));
        $response = $TGClient->setWebhook(
            $url . $path
        );

        return new Response(
            $response['description'],
            $response->status()
        );
    }

    /**
     * @param Request $request
     * @param string $botName
     * @return Response
     * @throws Throwable
     */
    public function shutdownBot(Request $request, string $botName): Response
    {
        $update = DTOUpdateBuilder::buildUpdateDTO($request->json()->all());

        $bot = new MiniBot(
            botName: $botName,
            update: $update,
            storageHelper: new StorageHelper(
                StorageHelper::createUserFromSender($update->getSender()),
                $botName
            )
        );

        $bot->sendMessageText(
            $bot->getUpdate()->getChat()->id,
            config('bot.message_shutdown_bot', 'Простите, бот пока не доступен, обратитесь попозже')
        );

        return new Response('', 204);
    }

    /**
     * @param Request $request
     * @param string $botName
     * @return Response
     * @throws TGApiException
     * @throws Throwable
     */
    public function messageBot(Request $request, string $botName): Response
    {
//                return new Response('', 200);

        config(['botName' => $botName]);

        if ($botName === 'SMI') {
            return new Response('', 200);
        }

        $update = DTOUpdateBuilder::buildUpdateDTO($request->json()->all());

        switch ($update->myChatMember?->newChatMember->status) {
            case 'member':
            case 'kicked':
                return new Response('', 204);
        }

        $bot = new MiniBot(
            botName: $botName,
            update: $update,
            storageHelper: new StorageHelper(
                StorageHelper::createUserFromSender($update->getSender()),
                $botName
            )
        );

        $bot->executeCommand($bot->getUpdate()->getPayload());

        return new Response('', 204);
    }
}
