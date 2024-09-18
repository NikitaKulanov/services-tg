<?php

namespace App\Services\Telegram\DTO;

class Sender
{
    /**
     * @param int $id Уникальный идентификатор для этого пользователя или бота
     * @param bool $isBot Верно, если этот пользователь — бот
     * @param string $firstName Имя пользователя или бота
     * @param string|null $lastName Фамилия пользователя или бота
     * @param string|null $userName Имя пользователя или бота
     * @param string|null $languageCode IETF языковая метка языка пользователя
     */
    public function __construct(
    public readonly int $id,
    public readonly bool $isBot,
    public readonly string $firstName,
    public readonly ?string $lastName,
    public readonly ?string $userName,
    public readonly ?string $languageCode,
)
{
}
}
