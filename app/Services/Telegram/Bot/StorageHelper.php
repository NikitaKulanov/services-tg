<?php

namespace App\Services\Telegram\Bot;

use App\Models\User;
use App\Services\Telegram\DTO\UpdateMessage\Sender;

class StorageHelper
{
    public function __construct(
        public readonly User $user,
        public readonly ?string $bot = null,
    )
    {
    }

    /**
     * @param Sender $sender
     * @return User
     */
    public static function createUserFromSender(Sender $sender): User
    {
        if (!($user = User::where('id_tg', $sender->id)->first())) {
            $user = User::createFromSender($sender);
        }
        return $user;
    }

    /**
     * @return int
     */
    public function getUserIdTG(): int
    {
        return $this->user->id_tg;
    }

    public function addMessageIdForDelete(int $messageId): void
    {
        $messages = $this->getFromStorage('delete_for_messages');
        if (is_array($messages)) {
            $this->saveToStorage([
                'delete_for_messages' => array_merge($messages, [$messageId])
            ]);
        } else {
            $this->saveToStorage([
                'delete_for_messages' => [$messageId]
            ]);
        }
    }

    public function getIdMessagesForDelete($default = null): array
    {
        return $this->getFromStorage('delete_for_messages', $default);
    }

    public function setDeleteForMessages($default = []): void
    {
        $this->saveToStorage([
            'delete_for_messages' => $default
        ]);
    }

    /**
     * @param array $payload
     * @return void
     */
    public function saveToStorage(array $payload): void
    {
        if (is_null($this->bot)) {
            $this->user->saveToStorage($payload);
        } else {
            $this->user->saveToStorage([$this->bot => array_merge($this->user->getFromStorage($this->bot, []), $payload)]);
        }
    }

    /**
     * @param array|string $payload
     * @param $default
     * @return array|string|int|bool|null
     */
    public function getFromStorage(array|string $payload, $default = null): array|string|int|null|bool
    {
        return $this->user->getFromStorage($payload, $default, $this->bot ?? null);
    }

    /**
     * @param bool $value
     * @return void
     */
    public function setWaitingBotAnswer(bool $value = true): void
    {
        $this->saveToStorage([
            'wait_bot_answer' => $value
        ]);
    }

    /**
     * @return bool
     */
    public function botWaitAnswer(): bool
    {
        if ($value = $this->getFromStorage('wait_bot_answer')) {
            return $value;
        } else {
            $this->saveToStorage(['wait_bot_answer' => false]);
            return false;
        }
    }

    /**
     * Установить желаемую команду.
     * Действие после подписки
     *
     * @param $command
     * @return void
     */
    public function setDesiredCommand($command): void
    {
        $this->saveToStorage([
            'desired_command' => $command,
        ]);
    }

    /**
     * Получить желаемую команду.
     * Действие после подписки
     *
     * @param null $default
     * @return string|null
     */
    public function getDesiredCommand($default = null): string|null
    {
        return $this->getFromStorage('desired_command', $default);
    }


    /**
     * Получить выполненную ранее команду
     *
     * @param null $default
     * @return string|null
     */
    public function getCompletedCommand($default = null): string|null
    {
        return $this->getFromStorage('completed_command', $default);
    }

    /**
     * @param $default
     * @return string|null
     */
    public function getPastCommand($default = null): string|null
    {
        return $this->getFromStorage('past_command', $default);
    }

    /**
     * Получить количество действий сделанных в боте
     *
     * @return int
     */
    public function getCountActions(): int
    {
        if ($count = $this->getFromStorage('count_of_actions')) {
            return $count;
        } else {
            $this->saveToStorage(['count_of_actions' => 0]);
            return 0;
        }
    }
}
