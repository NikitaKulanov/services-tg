<?php

namespace App\Services\Telegram\Bot;

use RuntimeException;
use App\Exceptions\TGApiException;
use App\Services\Telegram\Commands\Basic\SubscribeCommand;
use App\Services\Telegram\Commands\Basic\UnknownCommand;
use App\Services\Telegram\Commands\Basic\UserAnswerCommand;
use App\Services\Telegram\Commands\Command;
use App\Services\Telegram\DTO\UpdateMessage\Update;
use App\Services\Telegram\HttpClient\TGClientHelper;
use Throwable;

class MiniBot extends Bot
{

    /**
     * Базовые команды. Не учитывать, не сохранять
     * @var array
     */
    public readonly array $basicCommands;

    /**
     * @var string Команда поставленная на выполнение
     */
    private string $commandToExecute;

    /**
     * @throws Throwable
     */
    public function __construct(
        string $botName,
        private Update $update,
        public readonly StorageHelper $storageHelper
    )
    {
        parent::__construct($botName);
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
     * @param string $command
     * @param string $default
     * @return void
     * @throws TGApiException
     */
    public function executeCommand(string $command, string $default = UnknownCommand::NAME_TO_CALL): void
    {
        if (!key_exists($command, $this->commands)) $command = $default;

        if ($this->storageHelper->botWaitAnswer()) $command = UserAnswerCommand::nameToCall();

        if (array_key_exists($command, $this->basicCommands)) {
            $this->executeBasicCommand($command, $default);
            return;
        }

        if ($this->askSubscription($command)) {
            $this->storageHelper->setDesiredCommand($command);
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
                'Пользователь ' . $this->storageHelper->user->first_name . ' нажал на старт! Его user_name: @' .
                ($this->user->user_name ?? 'null') . '. Бот: ' . $this->botName . '.'
            );
        }

        $this->storageHelper->saveToStorage([
            'past_command' =>
                $commandToExecute::getPastCommand() ?? $this->storageHelper->getCompletedCommand(),
            'completed_command' => $command,
            'count_of_actions' => $this->storageHelper->getCountActions() + 1,
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
            throw new RuntimeException("You are trying to execute a simple command $command as a basic one");
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
            if ($this->storageHelper->getCountActions() >= $this->freeCountActions) {
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
                        $this->storageHelper->getUserIdTG(),
                        $this->update->getChat()->id
                    )->json()['result']['status'] === 'left'
                ) {
                    return false;
                }
            }
        }
        return true;
    }
}
