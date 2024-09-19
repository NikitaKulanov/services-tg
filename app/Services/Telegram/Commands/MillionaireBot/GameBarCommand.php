<?php

namespace App\Services\Telegram\Commands\MillionaireBot;

use App\Exceptions\TGApiException;
use App\Services\Telegram\Bot\MiniBot;
use App\Services\Telegram\Commands\Command;
use App\Services\Telegram\DTO\UpdateMessage\Chat;
use App\Services\Telegram\Payloads\EditMessageMediaPayload;
use App\Services\Telegram\Payloads\InputFiles\InputPhoto;
use App\Services\Telegram\Payloads\Keyboards\Buttons\InlineButton;
use App\Services\Telegram\Payloads\Keyboards\InlineKeyboard;
use App\Services\Telegram\Payloads\PhotoPayload;

class GameBarCommand extends Command
{
    /**
     * Command for call
     */
    const ANSWER_A = '/A';
    const ANSWER_B = '/B';
    const ANSWER_C = '/C';
    const ANSWER_D = '/D';
    const NEW_GAME = '/newGame';
    const NEW_QUESTION = '/newQuestion';
    const END_GAME = '/endGame';

    private MiniBot $bot;
    private Chat $chat;

    /**
     * @param MiniBot $bot
     * @param Chat $chat
     * @return void
     * @throws TGApiException
     */
    public function execute(MiniBot $bot, Chat $chat): void
    {
        $this->bot = $bot;
        $this->chat = $chat;
        $command = $bot->getCommandToExecute();
        $bar = $bot->storageHelper->getFromStorage(self::nameToCall());

        // Привет, новая игра
        // Следующий вопрос

        if ($command === self::nameToCall()) { // Начнём игру
            $result = $this->sendNewGame();
            $bot->storageHelper->saveToStorage([
                self::nameToCall() => [
                    'message_id' => $result['message_id'],
                    'question' => null,
                    'number' => null,
//                    'winning' => 0
                ]
            ]);

        } elseif ($command === self::NEW_GAME) { // Первый вопрос
            if ($barId = $bar['message_id'] ?? false) {
                $question = Game::getRandQuestions();
                $result = $this->sendFirstQuestion($barId, $question);
            } else {
                $bot->executeCommand(self::nameToCall());
                return;
            }

            $bot->storageHelper->saveToStorage([
                self::nameToCall() => [
                    'message_id' => $result['message_id'],
                    'question' => $question,
                    'number' => 1,
//                    'winning' => 0
                ]
            ]);

        } elseif (in_array($command, [self::ANSWER_A, self::ANSWER_B, self::ANSWER_C, self::ANSWER_D])) {// Проверка вопроса

            $answer = match ($command) {
                self::ANSWER_A => 'A',
                self::ANSWER_B => 'B',
                self::ANSWER_C => 'C',
                self::ANSWER_D => 'D',
            };

            if (Game::checkQuestion($bar['question'], $answer)) {

                // Отправить инфо о том, что ответ правильный
                if ($barId = $bar['message_id'] ?? false) {
                    if ($bar['number'] >= 10) {
                        $result = $this->sendEndGame($barId);
                        $bot->storageHelper->saveToStorage([
                            self::nameToCall() => null
                        ]);
                        return;
                    } else {
                        $result = $this->sendInfoCorrectAnswer($barId);
                        $bot->storageHelper->saveToStorage([
                            self::nameToCall() => [
                                'message_id' => $result['message_id'],
                                'question' => null,
                                'number' => $bar['number'],
//                        'winning' => $bar['winning'] + 1000
                            ]
                        ]);
                    }
                } else {
                    $bot->executeCommand(self::nameToCall());
                    return;
                }

            } else {
                // Отправить сообщение, что ответ не правильный, начни новую игру
                $bot->storageHelper->saveToStorage([
                    self::nameToCall() => null
                ]);

                if ($barId = $bar['message_id'] ?? false) {
                    $this->sendInfoErrorAnswer($barId);
                } else {
                    $bot->executeCommand(self::nameToCall());
                    return;
                }
            }
        } elseif ($command === self::NEW_QUESTION) {
            if ($barId = $bar['message_id'] ?? false) {
                $question = Game::getRandQuestions();
                $result = $this->sendNewQuestion($barId, $question, Game::getTextOfNumber($bar['number'] + 1));
            } else {
                $bot->executeCommand(self::nameToCall());
                return;
            }

            $bot->storageHelper->saveToStorage([
                self::nameToCall() => [
                    'message_id' => $result['message_id'],
                    'question' => $question,
                    'number' => $bar['number'] + 1,
//                    'winning' => $bar['winning']
                ]
            ]);
        }
    }

    private function sendNewGame(): array // Отправить Bar
    {
        return $this->bot->sendPhoto(
            PhotoPayload::create($this->chat->id, InputPhoto::create('MillionaireBot/' . 'new-game.png'))
                ->setCaption("<b>Начнём игру?</b>")
                ->setKeyboard(
                    InlineKeyboard::create()->setKeyboardButton([
                        [
                            InlineButton::create()->setText('Начать новую игру')->setCallbackData(self::NEW_GAME),
                        ]
                    ])
                )
        );
    }

    private function sendEndGame(int $messageId): array
    {
        return $this->bot->editMessageMedia(EditMessageMediaPayload::create(
            $this->chat->id,
            $messageId,
            InputPhoto::create('MillionaireBot/' . 'end.png')->setCaption("Ура, Вы дошли до конца 🤩\nПримите наши поздравления 🤗")
        )
            ->setKeyboard(
                InlineKeyboard::create()->setKeyboardButton([
                    [
                        InlineButton::create()->setText('Сыграть ещё раз')->setCallbackData(StartCommand::nameToCall()),
                    ]
                ])
            )
        );
    }

    private function sendFirstQuestion(int $messageId, array $question): array // Первый вопрос
    {
        return $this->bot->editMessageMedia(EditMessageMediaPayload::create(
            $this->chat->id,
            $messageId,
            InputPhoto::create('MillionaireBot/' . $question['photo'])->setCaption("Хорошо, мы начинаем игру\n<b>Кто хочет стать миллионером</b>!\n\nИ так, первый вопрос...\n\n<b>{$question['question']}</b>")
        )
            ->setKeyboard(
                InlineKeyboard::create()->setKeyboardButton([
                    [
                        InlineButton::create()->setText('A. ' . $question['answers']['A'])->setCallbackData(GameBarCommand::ANSWER_A),
                        InlineButton::create()->setText('C. ' . $question['answers']['C'])->setCallbackData(GameBarCommand::ANSWER_C),
                    ],
                    [
                        InlineButton::create()->setText('B. ' . $question['answers']['B'])->setCallbackData(GameBarCommand::ANSWER_B),
                        InlineButton::create()->setText('D. ' . $question['answers']['D'])->setCallbackData(GameBarCommand::ANSWER_D),
                    ]
                ])
            )
        );
    }

    private function sendInfoCorrectAnswer($messageId): array
    {
        return $this->bot->editMessageMedia(EditMessageMediaPayload::create(
            $this->chat->id,
            $messageId,
            InputPhoto::create('MillionaireBot/vin.png')->setCaption("Ура, ваш ответ правильный!")
        )
            ->setKeyboard(
                InlineKeyboard::create()->setKeyboardButton([
                    [
                        InlineButton::create()->setText('Продолжить')->setCallbackData(GameBarCommand::NEW_QUESTION),
                    ]
                ])
            )
        );
    }

    private function sendInfoErrorAnswer($messageId): array
    {
        return $this->bot->editMessageMedia(EditMessageMediaPayload::create(
            $this->chat->id,
            $messageId,
            InputPhoto::create('MillionaireBot/proig.png')->setCaption("Ваш ответ не правильный \nК сожалению Вы проиграли")
        )
            ->setKeyboard(
                InlineKeyboard::create()->setKeyboardButton([
                    [
                        InlineButton::create()->setText('Продолжить')->setCallbackData(StartCommand::nameToCall()),
                    ]
                ])
            )
        );
    }

    private function sendNewQuestion(int $messageId, array $question, string $number): array
    {
        return $this->bot->editMessageMedia(EditMessageMediaPayload::create(
            $this->chat->id,
            $messageId,
            InputPhoto::create('MillionaireBot/' . $question['photo'])->setCaption("И так, " . $number . " вопрос\n{$question['question']}")
        )
            ->setKeyboard(
                InlineKeyboard::create()->setKeyboardButton([
                    [
                        InlineButton::create()->setText('A. ' . $question['answers']['A'])->setCallbackData(GameBarCommand::ANSWER_A),
                        InlineButton::create()->setText('C. ' . $question['answers']['C'])->setCallbackData(GameBarCommand::ANSWER_C),
                    ],
                    [
                        InlineButton::create()->setText('B. ' . $question['answers']['B'])->setCallbackData(GameBarCommand::ANSWER_B),
                        InlineButton::create()->setText('D. ' . $question['answers']['D'])->setCallbackData(GameBarCommand::ANSWER_D),
                    ]
                ])
            )
        );
    }


    /**
     * @return string|null
     */
    static public function getPastCommand(): ?string
    {
        return null;
    }

    /**
     * @return string
     */
    static function nameToCall(): string
    {
        return '/gameBar';
    }
}
