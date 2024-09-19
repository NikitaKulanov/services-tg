<?php

namespace App\Services\Telegram\Commands\HeroBot;

use App\Services\Telegram\Bot\MiniBot;
use App\Services\Telegram\Commands\Command;
use App\Services\Telegram\DTO\UpdateMessage\Chat;
use App\Services\Telegram\Payloads\EditMessageMediaPayload;
use App\Services\Telegram\Payloads\InputFiles\InputPhoto;
use App\Services\Telegram\Payloads\Keyboards\Buttons\InlineButton;
use App\Services\Telegram\Payloads\Keyboards\InlineKeyboard;
use App\Services\Telegram\Payloads\PhotoPayload;

class TestBarCommand extends Command
{
    /**
     * Command for call
     */
    const ANSWER_ONE = '/TestBarONE';
    const ANSWER_TWO = '/TestBarTWO';
    const ANSWER_THREE = '/TestBarHREE';
    const ANSWER_FOUR = '/TestBarFOUR';

    private array $data = [
        [
            'title' => "1) Что важнее для вас?",
            'answer_options' => [
                [
                    'answer' => "1. Справедливость и защита невинных",
                    'heroes' => ["Капитан Америка", "Черная вдова"]
                ],
                [
                    'answer' => "2. Мощь и сила ",
                    'heroes' => ["Халк", "Тор"]
                ],
                [
                    'answer' => "3. Интеллект и технологии ",
                    'heroes' => ["Железный человек", "Хэнк Пим"]
                ],
                [
                    'answer' => "4. Гибкость и скорость ",
                    'heroes' => ["Человек-паук", "Ртуть"]
                ]
            ],
            'photo' => 'HeroBot/1a.png',
        ],
        [
            'title' => "2) Какой стиль боя вы предпочитаете?",
            'answer_options' => [
                [
                    'answer' => "1. Боевые искусства ",
                    'heroes' => ["Капитан Америка", "Черная вдова"]
                ],
                [
                    'answer' => "2. Разрушение всего на своем пути ",
                    'heroes' => ["Халк", "Тор"]
                ],
                [
                    'answer' => "3. Стратегия и тактика ",
                    'heroes' => ["Железный человек", "Хэнк Пим"]
                ],
                [
                    'answer' => "4. Уклонение и быстрые удары ",
                    'heroes' => ["Человек-паук", "Ртуть"]
                ]
            ],
            'photo' => 'HeroBot/2a.png'
        ],
        [
            'title' => "3) Какие способности вам ближе?",
            'answer_options' => [
                [
                    'answer' => "1. Физическая сила и выносливость ",
                    'heroes' => ["Халк", "Тор"]
                ],
                [
                    'answer' => "2. Интеллект и гениальность ",
                    'heroes' => ["Железный человек", "Хэнк Пим"]
                ],
                [
                    'answer' => "3. Гибкость и ловкость ",
                    'heroes' => ["Человек-паук", "Черная вдова"]
                ],
                [
                    'answer' => "4. Скорость и маневренность ",
                    'heroes' => ["Ртуть", "Капитан Америка"]
                ]
            ],
            'photo' => 'HeroBot/3a.png'
        ],
        [
            'title' => "4) Как вы предпочитаете решать проблемы?",
            'answer_options' => [
                [
                    'answer' => "1. Сила и насилие ",
                    'heroes' => ["Халк", "Тор"]
                ],
                [
                    'answer' => "2. Изобретательность и научный подход ",
                    'heroes' => ["Железный человек", "Хэнк Пим"]
                ],
                [
                    'answer' => "3. Стратегия и планирование ",
                    'heroes' => ["Капитан Америка", "Черная вдова"]
                ],
                [
                    'answer' => "4. Скорость и ловкость ",
                    'heroes' => ["Человек-паук", "Ртуть"]
                ]
            ],
            'photo' => 'HeroBot/4a.png'
        ],
        [
            'title' => "5) Какие моральные принципы важны для вас?",
            'answer_options' => [
                [
                    'answer' => "1. Честь и долг ",
                    'heroes' => ["Капитан Америка", "Черная вдова"]
                ],
                [
                    'answer' => "2. Самопожертвование и защита ",
                    'heroes' => ["Халк", "Тор"]
                ],
                [
                    'answer' => "3. Справедливость и ответственность ",
                    'heroes' => ["Железный человек", "Хэнк Пим"]
                ],
                [
                    'answer' => "4. Свобода и независимость ",
                    'heroes' => ["Человек-паук", "Ртуть"]
                ]
            ],
            'photo' => 'HeroBot/5a.png'
        ],
        [
            'title' => "6) Как вы проводите свободное время?",
            'answer_options' => [
                [
                    'answer' => "1. Тренировки и подготовка к бою ",
                    'heroes' => ["Капитан Америка", "Черная вдова"]
                ],
                [
                    'answer' => "2. Исследование и эксперименты ",
                    'heroes' => ["Железный человек", "Хэнк Пим"]
                ],
                [
                    'answer' => "3. Путешествия и приключения ",
                    'heroes' => ["Халк", "Ртуть"]
                ],
                [
                    'answer' => "4. Помощь окружающим и защита слабых ",
                    'heroes' => ["Человек-паук", "Тор"]
                ]
            ],
            'photo' => 'HeroBot/6a.png'
        ],
        [
            'title' => "7) Что для вас важнее всего в команде?",
            'answer_options' => [
                [
                    'answer' => "1. Дружба и взаимная поддержка ",
                    'heroes' => ["Капитан Америка", "Человек-паук"]
                ],
                [
                    'answer' => "2. Профессионализм и эффективность ",
                    'heroes' => ["Железный человек", "Черная вдова"]
                ],
                [
                    'answer' => "3. Мощь и авторитет ",
                    'heroes' => ["Халк", "Тор"]
                ],
                [
                    'answer' => "4. Гибкость и координация ",
                    'heroes' => ["Ртуть", "Хэнк Пим"]
                ]
            ],
            'photo' => 'HeroBot/7a.png'
        ],
    ];

    private array $score = [
        'Капитан Америка' => 0,
        'Черная вдова' => 0,
        'Халк' => 0,
        'Тор' => 0,
        'Железный человек' => 0,
        'Хэнк Пим' => 0,
        'Человек-паук' => 0,
        'Ртуть' => 0,
    ];

    private array $heroPhoto = [
        'Капитан Америка' => 'HeroBot/kapitan-amerika.jpg',
        'Черная вдова' => 'HeroBot/vdova.jpg',
        'Халк' => 'HeroBot/halk.jpg',
        'Тор' => 'HeroBot/Tor.jpg',
        'Железный человек' => 'HeroBot/chelovek.jpg',
        'Хэнк Пим' => 'HeroBot/murove.jpg',
        'Человек-паук' => 'HeroBot/chelovek-pauk.jpg',
        'Ртуть' => 'HeroBot/rtut.jpg',
    ];

    /**
     * @param MiniBot $bot
     * @param Chat $chat
     * @return void
     */
    public function execute(MiniBot $bot, Chat $chat): void
    {
        $command = $bot->getCommandToExecute();

        if ($command === self::nameToCall()) {
            $barLevel = 0;
            $score = $this->score;

            $text = $this->data[$barLevel]['title'] . "\n";

            foreach ($this->data[$barLevel]['answer_options'] as $value) {
                $text .= $value['answer'] . "\n";
            }

            $result = $bot->sendPhoto(
                PhotoPayload::create($chat->id, InputPhoto::create($this->data[$barLevel]['photo']))
                    ->setCaption($text)
                    ->setKeyboard(
                        InlineKeyboard::create()->setKeyboardButton([
                            [
                                InlineButton::create()->setText('1')->setCallbackData(self::ANSWER_ONE),
                                InlineButton::create()->setText('2')->setCallbackData(self::ANSWER_TWO),
                                InlineButton::create()->setText('3')->setCallbackData(self::ANSWER_THREE),
                                InlineButton::create()->setText('4')->setCallbackData(self::ANSWER_FOUR),
                            ]
                        ])
                    )
            );
        } else {
            $barData = $bot->storageHelper->getFromStorage(self::nameToCall());
            $barLevel = $barData['bar']['bar_level'];
            $score = $barData['bar']['score'];

            if ($barLevel >= count($this->data) - 1) {
                $hero = array_search(max($score), $score);
                $bot->sendPhoto(
                    PhotoPayload::create($chat->id, InputPhoto::create($this->heroPhoto[$hero])
                        ->setCaption("Ты " . $hero)
                    )
                        ->setKeyboard(
                            InlineKeyboard::create()->setKeyboardButton([
                                [
                                    InlineButton::create()->setText('Пройти тест ещё раз 🔥')->setCallbackData(TestBarCommand::nameToCall()),
                                ]
                            ])
                        )
                );
            } else {
                $messageId = $barData['message_id'];

                $answer = match ($command) {
                    self::ANSWER_ONE => 0,
                    self::ANSWER_TWO => 1,
                    self::ANSWER_THREE => 2,
                    self::ANSWER_FOUR => 3,
                };
                $score = $this->updateScore($barLevel, $score, $answer);
                $barLevel++;

                $text = $this->data[$barLevel]['title'] . "\n";

                foreach ($this->data[$barLevel]['answer_options'] as $value) {
                    $text .= $value['answer'] . "\n";
                }

                $result = $bot->editMessageMedia(EditMessageMediaPayload::create(
                    $chat->id,
                    $messageId,
                    InputPhoto::create($this->data[$barLevel]['photo'])->setCaption($text)
                )
                    ->setKeyboard(
                        InlineKeyboard::create()->setKeyboardButton([
                            [
                                InlineButton::create()->setText('1')->setCallbackData(self::ANSWER_ONE),
                                InlineButton::create()->setText('2')->setCallbackData(self::ANSWER_TWO),
                                InlineButton::create()->setText('3')->setCallbackData(self::ANSWER_THREE),
                                InlineButton::create()->setText('4')->setCallbackData(self::ANSWER_FOUR),
                            ]
                        ])
                    )
                );
            }
        }

        $bot->storageHelper->saveToStorage([
            self::nameToCall() => [
                'message_id' => $result['message_id'] ?? null, 'bar' => ['bar_level' => $barLevel, 'score' => $score]
            ]
        ]);
    }

    private function updateScore(int $barLevel, array $score, int $answer): array
    {
        foreach ($this->data[$barLevel]['answer_options'][$answer]['heroes'] as $hero) {
            $score[$hero] = $score[$hero] + 1;
        }
        return $score;
    }

    private function getKeyboardButton(array $data, int $length, int $barLevel): array
    {
        return [];
    }

    /**
     * @return string
     */
    static function nameToCall(): string
    {
        return '/takeTheTest';
    }

    /**
     * @return string|null
     */
    public static function getPastCommand(): ?string
    {
        return null;
    }
}
