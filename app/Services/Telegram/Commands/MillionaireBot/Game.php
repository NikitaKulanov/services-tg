<?php

namespace App\Services\Telegram\Commands\MillionaireBot;

use App\Services\Telegram\Payloads\EditMessagePayload;
use App\Services\Telegram\Payloads\Keyboards\Buttons\InlineButton;
use App\Services\Telegram\Payloads\Keyboards\InlineKeyboard;

class Game
{
    private static array $questions = [
        [
            'question' => 'Какой фрукт является желтым?',
            'answers' => [
                'A' => 'Яблоко',
                'B' => 'Банан',
                'C' => 'Вишня',
                'D' => 'Слива',
            ],
            'answer' => 'B',
            'photo' => 'fructs.png'
        ],
        [
            'question' => 'Какое из этих животных умеет летать?',
            'answers' => [
                'A' => 'Кот',
                'B' => 'Собака',
                'C' => 'Птица',
                'D' => 'Кролик',
            ],
            'answer' => 'C',
            'photo' => 'animals.png'
        ],
        [
            'question' => 'Кто написал роман "Война и мир"?',
            'answers' => [
                'A' => 'Фёдор Достоевский',
                'B' => 'Антон Чехов',
                'C' => 'Лев Толстой',
                'D' => 'Александр Пушкин'
            ],
            'answer' => 'C',
            'photo' => 'war.png'
        ],
        [
            'question' => 'Какой город является столицей Франции?',
            'answers' => [
                'A' => 'Лондон',
                'B' => 'Рим',
                'C' => 'Берлин',
                'D' => 'Париж',
            ],
            'answer' => 'D',
            'photo' => 'frans.png'
        ],
        [
            'question' => 'Какая планета ближе всего к Солнцу?',
            'answers' => [
                'A' => 'Земля',
                'B' => 'Марс',
                'C' => 'Меркурий',
                'D' => 'Венера',
            ],
            'answer' => 'C',
            'photo' => 'soln.png'
        ],
        [
            'question' => 'Кто является автором теории относительности?',
            'answers' => [
                'A' => 'Исаак Ньютон',
                'B' => 'Никола Тесла',
                'C' => 'Альберт Эйнштейн',
                'D' => 'Галилео Галилей',
            ],
            'answer' => 'C',
            'photo' => 'emc.png'
        ],
        [
            'question' => 'Какой океан самый большой по площади?',
            'answers' => [
                'A' => 'Атлантический',
                'B' => 'Индийский',
                'C' => 'Северный Ледовитый',
                'D' => 'Тихий',
            ],
            'answer' => 'D',
            'photo' => 'okean.png'
        ],
        [
            'question' => 'Какое химическое вещество обозначается символом "O"?',
            'answers' => [
                'A' => 'Кислород',
                'B' => 'Водород',
                'C' => 'Золото',
                'D' => 'Серебро',
            ],
            'answer' => 'A',
            'photo' => 'him.png'
        ],
        [
            'question' => 'Какой элемент периодической таблицы обозначается символом "Fe"?',
            'answers' => [
                'A' => 'Свинец',
                'B' => 'Железо',
                'C' => 'Цинк',
                'D' => 'Медь',
            ],
            'answer' => 'B',
            'photo' => 'alim.png'
        ],
        [
            'question' => 'Какая река является самой длинной в мире?',
            'answers' => [
                'A' => 'Амазонка',
                'B' => 'Нил',
                'C' => 'Янцзы',
                'D' => 'Миссисипи',
            ],
            'answer' => 'B',
            'photo' => 'reka.png'
        ],
        [
            'question' => 'Сколько дней в високосном году?',
            'answers' => [
                'A' => '365',
                'B' => '366',
                'C' => '367',
                'D' => '364',
            ],
            'answer' => 'B',
            'photo' => 'god.png'
        ],
        [
            'question' => 'Какой континент самый большой по площади?',
            'answers' => [
                'A' => 'Африка',
                'B' => 'Антарктида',
                'C' => 'Азия',
                'D' => 'Европа',
            ],
            'answer' => 'C',
            'photo' => 'konten.png'
        ],
        [
            'question' => 'Какое животное является символом Австралии?',
            'answers' => [
                'A' => 'Коала',
                'B' => 'Лев',
                'C' => 'Тигр',
                'D' => 'Слон',
            ],
            'answer' => 'A',
            'photo' => 'avstral.png'
        ],
        [
            'question' => 'Какой элемент периодической таблицы обозначается символом "H"?',
            'answers' => [
                'A' => 'Гелий',
                'B' => 'Водород',
                'C' => 'Углерод',
                'D' => 'Азот',
            ],
            'answer' => 'B',
            'photo' => 'alim.png'
        ],
        [
            'question' => 'Как называется столица Японии?',
            'answers' => [
                'A' => 'Киото',
                'B' => 'Токио',
                'C' => 'Осака',
                'D' => 'Хиросима',
            ],
            'answer' => 'B',
            'photo' => 'japan.png'
        ],
        [
            'question' => 'Какой художник написал "Мону Лизу"?',
            'answers' => [
                'A' => 'Винсент Ван Гог',
                'B' => 'Пабло Пикассо',
                'C' => 'Леонардо да Винчи',
                'D' => 'Микеланджело',
            ],
            'answer' => 'C',
            'photo' => 'mona.png'
        ],
        [
            'question' => 'В каком году состоялся первый полет человека в космос?',
            'answers' => [
                'A' => '1957',
                'B' => '1961',
                'C' => '1965',
                'D' => '1969',
            ],
            'answer' => 'B',
            'photo' => 'kos.png'
        ],
        [
            'question' => 'Какое море омывает берега Египта?',
            'answers' => [
                'A' => 'Черное море',
                'B' => 'Средиземное море',
                'C' => 'Красное море',
                'D' => 'Аравийское море',
            ],
            'answer' => 'C',
            'photo' => 'egipt.png'
        ],
        [
            'question' => 'Как называется самый высокий водопад в мире?',
            'answers' => [
                'A' => 'Ниагарский',
                'B' => 'Виктория',
                'C' => 'Анхель',
                'D' => 'Йосемити',
            ],
            'answer' => 'C',
            'photo' => 'pad.png'
        ],
        [
            'question' => 'Какой ученый открыл закон всемирного тяготения?',
            'answers' => [
                'A' => 'Альберт Эйнштейн',
                'B' => 'Исаак Ньютон',
                'C' => 'Грегор Мендель',
                'D' => 'Майкл Фарадей',
            ],
            'answer' => 'B',
            'photo' => 'tag.png'
        ],
        [
            'question' => 'Какой месяц идет после января?',
            'answers' => [
                'A' => 'Март',
                'B' => 'Февраль',
                'C' => 'Апрель',
                'D' => 'Май',
            ],
            'answer' => 'B',
            'photo' => 'mes.png'
        ],
        [
            'question' => 'Какая планета известна как Красная планета?',
            'answers' => [
                'A' => 'Венера',
                'B' => 'Сатурн',
                'C' => 'Марс',
                'D' => 'Юпитер',
            ],
            'answer' => 'C',
            'photo' => 'mars.png'
        ],
        [
            'question' => 'Как называется наука о животных?',
            'answers' => [
                'A' => 'Ботаника',
                'B' => 'Зоология',
                'C' => 'Геология',
                'D' => 'Астрономия',
            ],
            'answer' => 'B',
            'photo' => 'enimal.png'
        ],
        [
            'question' => 'Какой язык является официальным в Бразилии?',
            'answers' => [
                'A' => 'Испанский',
                'B' => 'Португальский',
                'C' => 'Французский',
                'D' => 'Английский',
            ],
            'answer' => 'B',
            'photo' => 'braz.png'
        ],
        [
            'question' => 'Кто является основателем компании Microsoft?',
            'answers' => [
                'A' => 'Стив Джобс',
                'B' => 'Ларри Пейдж',
                'C' => 'Билл Гейтс',
                'D' => 'Марк Цукерберг',
            ],
            'answer' => 'C',
            'photo' => 'mic.png'
        ],
        [
            'question' => 'Какой элемент периодической таблицы имеет атомный номер 1?',
            'answers' => [
                'A' => 'Гелий',
                'B' => 'Водород',
                'C' => 'Литий',
                'D' => 'Бериллий',
            ],
            'answer' => 'B',
            'photo' => 'alim.png'
        ],
        [
            'question' => 'Какая страна известна своими кленовыми листьями?',
            'answers' => [
                'A' => 'США',
                'B' => 'Швеция',
                'C' => 'Канада',
                'D' => 'Финляндия',
            ],
            'answer' => 'C',
            'photo' => 'can.png'
        ],
        [
            'question' => 'Какой город является столицей Австралии?',
            'answers' => [
                'A' => 'Сидней',
                'B' => 'Мельбурн',
                'C' => 'Канберра',
                'D' => 'Брисбен',
            ],
            'answer' => 'C',
            'photo' => 'avstral.png'
        ],
        [
            'question' => 'Как называется самая маленькая частица химического элемента?',
            'answers' => [
                'A' => 'Молекула',
                'B' => 'Атом',
                'C' => 'Ион',
                'D' => 'Протон',
            ],
            'answer' => 'B',
            'photo' => 'him.png'
        ],
        [
            'question' => 'Какое животное известно как Король джунглей?',
            'answers' => [
                'A' => 'Тигр',
                'B' => 'Лев',
                'C' => 'Леопард',
                'D' => 'Пантера',
            ],
            'answer' => 'B',
            'photo' => 'jun.png'
        ],
    ];

    const NUMBERS_QUESTIONS = [
        1 => 'первый',
        2 => 'второй',
        3 => 'третий',
        4 => 'четвёртый',
        5 => 'пятый',
        6 => 'шестой',
        7 => 'седьмой',
        8 => 'восьмой',
        9 => 'девятый',
        10 => 'десятый'
    ];

    static public function getTextOfNumber(int $number): bool|string
    {
        return self::NUMBERS_QUESTIONS[$number] ?? false;
    }

    static public function getQuestions(): array
    {
        return self::$questions;
    }

    static public function getQuestion(int $id): array
    {
        return self::$questions[$id];
    }

    static public function getRandQuestions(): array
    {
        return self::$questions[array_rand(self::$questions)];
    }

    static public function checkQuestion(array $question, string $answer): bool
    {
        return $question['answer'] === $answer;
    }
}
