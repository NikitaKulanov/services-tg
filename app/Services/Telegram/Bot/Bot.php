<?php

namespace App\Services\Telegram\Bot;

use App\Services\Telegram\HttpClient\TGClient;
use App\Services\Telegram\Payloads\EditMessageMediaPayload;
use App\Services\Telegram\Payloads\EditMessagePayload;
use App\Services\Telegram\Payloads\MediaGroupPayload;
use App\Services\Telegram\Payloads\MessagePayload;
use App\Services\Telegram\Payloads\PhotoPayload;
use App\Services\Telegram\Payloads\VideoPayload;
use Throwable;

abstract class Bot
{
    /**
     * @var array
     */
    public readonly array  $config;

    /**
     * Базовые команды. Не учитывать, не сохранять
     * @var array
     */
    public readonly array $basicCommands;

    /**
     * Для выполнения этих команд требуется подписка
     * @var array|null
     */
    public readonly ?array $subscriptionRequired;

    /**
     * @var array
     */
    protected array $commands;

    /**
     * Количество действий, если больше просить подписаться
     *
     * @var int|null
     */
    protected ?int $freeCountActions;

    /**
     * @var TGClient
     */
    protected TGClient   $requestClient;

    /**
     * @throws Throwable
     */
    public function __construct(
        public readonly string $botName,
    )
    {
        $this->config = config('bot') ?? throw new \RuntimeException('No configuration passed to bot');

        $this->freeCountActions = $this->config['settings']['mini_bots'][$botName]['count_of_actions'] ?? null;

        $this->basicCommands = $this->config['basic_commands'] ?? [];

        $this->commands = array_merge(
            $this->config['settings']['mini_bots'][$botName]['simple_commands'] ?? [],
            $this->basicCommands
        );

        $this->subscriptionRequired = $this->config['settings']['mini_bots'][$botName]['subscription_required'] ?? null;

        $this->requestClient = new TGClient(
            token : $this->config['settings']['mini_bots'][$botName]['token']
        );
    }

    public function sendMessageText(int $chatId, string $text): array
    {
        return $this->requestClient->sendMessageText($chatId, $text)->json()['result'];
    }

    public function deleteMassage(int $chatId, int $messageId, int $userId): bool
    {
        return $this->requestClient->deleteMassage($chatId, $messageId, $userId)->json()['result'];
    }

    public function sendMessage(MessagePayload $payload): array
    {
        return $this->requestClient->sendPayload($payload)->json()['result'];
    }

    public function sendPhoto(PhotoPayload $payload): array
    {
        return $this->requestClient->sendPayload($payload)->json()['result'];
    }

    public function sendVideo(VideoPayload $payload): array
    {
        return $this->requestClient->sendPayload($payload)->json()['result'];
    }

    public function sendMediaGroup(MediaGroupPayload $payload): array
    {
        return $this->requestClient->sendPayload($payload)->json()['result'];
    }

    public function editMessageText(EditMessagePayload $payload): array
    {
        return $this->requestClient->sendPayload($payload)->json()['result'];
    }

    public function editMessageMedia(EditMessageMediaPayload $payload): array
    {
        return $this->requestClient->sendPayload($payload)->json()['result'];
    }
}
