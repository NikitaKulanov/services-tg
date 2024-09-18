# Документация

Для взаимодействия с Ботом используются команды

Настройки Бота находятся в файле config/bot.php

# API метод для работы с ботом

## Сценарии: 

- ### Ожидания неопределённого (в том числе и не команду) ответа от пользователя
1. Для того чтобы в следующем запросе бот ожидал ответ, 
установите в Store пользователя значение *wait_bot_answer* равное *true*, при помощи метода:
```php
$user->setWaitingBotAnswer();
```
2. Перед выполнением команды Бот увидит это значение и будет выполнена базовая команда: **UserAnswerCommand**
```php
if ($this->user->botWaitAnswer()) $command = UserAnswerCommand::nameToCall();
```
3. При выполнении **UserAnswerCommand** обязательно отключите ожидания ответа Ботом
```php
public function execute(Bot $bot, Chat $chat, User $user): void
{
    // Your code...
    
    $user->setWaitingBotAnswer(false);
}
```

- ### Выполнения команды "Назад"
1. После выполнения команды (не базовой) Бот установит значения (названия для вызова) позапрошлой и выполненной команды:

```php
$this->user->saveToStorage([
'past_command' => $this->user->getFromStorage('completed_command'),
'completed_command' => $command,
// More data
]);
```

2. Вызов команды "Назад" в коде
```php
   $bot->executeBasicCommand(BackCommand::nameToCall());
```
3. Команда "Назад" (BackCommand) выполняет позапрошлую команду 'past_command':
```php
$bot->executeCommand($user->getFromStorage('past_command', BeginStartCommand::nameToCall()));
```
- ### Проверка подписки

- ### Просьба подписаться



// Можно сделать карту выполненных команд
