<?php

namespace App\Services\Telegram\Bot;

use App\Exceptions\TGApiException;
use App\Models\User;
use App\Services\Telegram\Commands\Basic\SubscribeCommand;
use App\Services\Telegram\Commands\Basic\UnknownCommand;
use App\Services\Telegram\Commands\Basic\UserAnswerCommand;
use App\Services\Telegram\Commands\Command;
use App\Services\Telegram\DTO\Builder\DTOUpdateBuilder;
use App\Services\Telegram\DTO\Sender;
use App\Services\Telegram\DTO\Update;
use App\Services\Telegram\HttpClient\TGClient;
use App\Services\Telegram\HttpClient\TGClientHelper;
use App\Services\Telegram\Payloads\EditMessageMediaPayload;
use App\Services\Telegram\Payloads\EditMessagePayload;
use App\Services\Telegram\Payloads\MediaGroupPayload;
use App\Services\Telegram\Payloads\MessagePayload;
use App\Services\Telegram\Payloads\PhotoPayload;
use App\Services\Telegram\Payloads\VideoPayload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Throwable;

class Bot extends StorageHelper
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
     * @var string Команда поставленная на выполнение
     */
    private string $commandToExecute;

    /**
     * @var array
     */
    private array $commands;

    /**
     * Количество действий, если больше просить подписаться
     *
     * @var int|null
     */
    private ?int $freeCountActions;

    /**
     * @var TGClient
     */
    public TGClient   $requestClient;


    /**
     * @throws Throwable
     */
    public function __construct(
        public readonly string $botName,
        private Update $update
    )
    {
        $this->config = config('bot') ?? throw new \RuntimeException('No configuration passed to bot');

        $this->freeCountActions = $this->config['settings']['mini_bots'][$botName]['count_of_actions'] ?? null;
        $this->commands = array_merge(
            $this->config['settings']['mini_bots'][$botName]['simple_commands'],
            $this->config['basic_commands']
        );
        $this->basicCommands = $this->config['basic_commands'];
        $this->subscriptionRequired = $this->config['settings']['mini_bots'][$botName]['subscription_required'] ?? null;

        parent::__construct(
            $this->createUserFromSender($this->update->getSender()),
            $botName
        );

        $this->requestClient = new TGClient(
            token : $this->config['settings']['mini_bots'][$botName]['token']
        );
    }

    /**
     * @return Update
     */
    public function getUpdate(): Update
    {
        return $this->update;
    }

    /**
     * @param Update $update
     * @return void
     */
    public function setUpdate(Update $update): void
    {
        $this->update = $update;
    }

    /**
     * @return string
     */
    public function getCommandToExecute(): string
    {
        return $this->commandToExecute;
    }

    /**
     * @param Sender $sender
     * @return User
     */
    private function createUserFromSender(Sender $sender): User
    {
        if (!($user = User::where('id_tg', $sender->id)->first())) {
            $user = User::createFromSender($sender);
        }
        return $user;
    }

    /**
     * @param string $command
     * @param string $default
     * @return void
     * @throws TGApiException
     */
    public function executeCommand(string $command, string $default = UnknownCommand::NAME_TO_CALL): void
    {
        if (!key_exists($command, $this->commands)) $command = $default;

        if (parent::botWaitAnswer()) $command = UserAnswerCommand::nameToCall();

        if (array_key_exists($command, $this->basicCommands)) {
            $this->executeBasicCommand($command, $default);
            return;
        }

        if ($this->askSubscription($command)) {
            parent::setDesiredCommand($command);
            return;
        }

        $this->commandToExecute = $command;

        $commandToExecute = Command::getCommand($this->commands[$command]);

        $commandToExecute->execute(
            $this,
            $this->update->getChat()
        );

        if ($command === '/start') {
            TGClientHelper::info(
                'Пользователь ' . $this->user->first_name . ' нажал на старт! Его user_name: @' .
                ($this->user->user_name ?? 'null') . '. Бот: ' . $this->botName . '.'
            );
        }

        parent::saveToStorage([
            'past_command' =>
                $commandToExecute::getPastCommand() ?? parent::getCompletedCommand(),
            'completed_command' => $command,
            'count_of_actions' => parent::getCountActions() + 1,
            'desired_command' => null
        ]);

        $this->freeCountActions = $this->freeCountActions + 1;
    }

    /**
     * @param string $command
     * @param string $default
     * @return void
     */
    public function executeBasicCommand(string $command, string $default = UnknownCommand::NAME_TO_CALL): void
    {
        if (!key_exists($command, $this->commands)) $command = $default;

        if (!array_key_exists($command, $this->basicCommands)) {
            throw new \RuntimeException("You are trying to execute a simple command {$command} as a basic one");
        }

        $this->commandToExecute = $command;

        Command::getCommand($this->commands[$command])->execute(
            $this,
            $this->update->getChat()
        );
    }

    /**
     * Попросить ли подписаться
     *
     * @param string $command
     * @return bool
     * @throws TGApiException
     */
    private function askSubscription(string $command): bool
    {
        if (is_array($this->subscriptionRequired)) {
            if (in_array($command, $this->subscriptionRequired)) {
                if (!$this->checkChannelSubscriptions()) {
                    $this->executeBasicCommand(SubscribeCommand::nameToCall());
                    return true;
                }
            }
        } elseif (is_int($this->freeCountActions)) {
            if (parent::getCountActions() >= $this->freeCountActions) {
                if (!$this->checkChannelSubscriptions()) {
                    $this->executeBasicCommand(SubscribeCommand::nameToCall());
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Проверка подписки на канал
     *
     * @return bool
     * @throws TGApiException
     */
    public function checkChannelSubscriptions(): bool
    {
        if (array_key_exists('channel_subscriptions', $this->config)) {
            foreach ($this->config['channel_subscriptions'] as $channel) {
                if ($this->requestClient->checkChannelSubscription(
                        $channel['id'],
                        parent::getUserIdTG(),
                        $this->update->getChat()->id
                    )->json()['result']['status'] === 'left'
                ) {
                    return false;
                }
            }
        }
        return true;
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
