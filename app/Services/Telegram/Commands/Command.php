<?php

namespace App\Services\Telegram\Commands;

use App\Services\Telegram\Bot\MiniBot;
use App\Services\Telegram\DTO\UpdateMessage\Chat;

abstract class Command
{
    /**
     * The Telegram command description.
     *
     * @var string
     */
    protected string $description;

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description ?? null;
    }

    /**
     * @return string|null
     */
    abstract static public function getPastCommand(): ?string;

    /**
     * Command handler
     *
     * @param MiniBot $bot
     * @param Chat $chat
     * @return void
     */
    abstract public function execute(MiniBot $bot, Chat $chat): void;

    /**
     * Command for call
     *
     * @return string
     */
    abstract static function nameToCall(): string;

    public static function getCommand(string $class): Command
    {
        if (class_exists($class)) {
            return new $class;
        } else throw new \RuntimeException("Class {$class} not found");
    }

    public static function searchCommand(string $nameToCall, array $commands): ?Command
    {
        if (array_key_exists($nameToCall, $commands)) {
            return self::getCommand($commands[$nameToCall]);
        } else return null;
    }
}
