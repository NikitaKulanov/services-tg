<?php

use App\Services\Telegram\Commands\Basic\BackCommand;
use App\Services\Telegram\Commands\Basic\BeginStartCommand;
use App\Services\Telegram\Commands\Basic\ConfirmSubscriptionCommand;
use App\Services\Telegram\Commands\Basic\ErrorCommand;
use App\Services\Telegram\Commands\Basic\InactionCommand;
use App\Services\Telegram\Commands\Basic\SubscribeCommand;
use App\Services\Telegram\Commands\Basic\UnknownCommand;
use App\Services\Telegram\Commands\Basic\UserAnswerCommand;
use App\Services\Telegram\Commands\AdapterBot\StartCommand;

return [
    'settings' => [
        'base_token' => "7436812591:AAHUv9bqawq1sN4zTpOBcoO8sqVhVRC3Ik0",
        'url_webhook' => env('URL_WEBHOOK'),

        /** Отвечать в группах */
        'answer_in_groups' => false,

        /** ID чата для отправки ошибок, если false ошибки отправляться не будут */
        'chat_id_for_errors' => env('CHAT_ID_FOR_ERRORS', false),

        /** ID чата для отправки ИНФО, если false ошибки отправляться не будут */
        'chat_id_for_info' => env('CHAT_ID_FOR_INFO', false),

        /** Сообщение пользователю об ошибке */
        'error_message_to_the_user' => 'Произошла ошибка, приносим свои извинения, уже исправляем!',

        /** Сообщение пользователю об неизвестной команде */
        'message_unknown_command' => 'Простите, я вас не понял. Если хотите, можете начать со /start',

        /** Сообщение пользователю об выключенном боте */
        'message_shutdown_bot' => 'Простите, бот пока не доступен, обратитесь попозже',

        'mini_bots' => [

            'SMI' => [
                'token' => '7073387392:AAEC36z_jIPlqnh7zVZ8CyKA40TG9v3VrOo',
                'id_channel' => (integer) env('ID_CHANNEL'),
//                'id_chat_error' => (integer) env('CHAT_ID_FOR_CHANNEL_ERROR'),
                'name_channel' => 'Западные СМИ ⚡',
                'url_channel' => 'https://t.me/zapad_smi',
                'url_news' => 'https://www.theguardian.com/',
                'storage' => 'storage.json'
            ],

            'MusicVK' => [
                'token' => '7879559593:AAHl175DXRgbvHJWhXfpUDA3L9ZeR2QYFq0',
                'simple_commands' => [
                    \App\Services\Telegram\Commands\MusicVK\StartCommand::nameToCall() => \App\Services\Telegram\Commands\MusicVK\StartCommand::class,
                    \App\Services\Telegram\Commands\MusicVK\AccessCommand::nameToCall() => \App\Services\Telegram\Commands\MusicVK\AccessCommand::class,
                ],
                /** Количество действий, если больше просить подписаться */
                'count_of_actions' => 99,
                /** Commands requiring subscription */
                'subscription_required' =>  [
                    \App\Services\Telegram\Commands\MusicVK\AccessCommand::nameToCall()
                ],
            ],

            'MusicBot' => [
                'token' => '7379411798:AAHrphBJ4yBhcAPWgQyL9XBR-f9NxYE7jQk',
                'simple_commands' => [
                    \App\Services\Telegram\Commands\MusicVK\StartCommand::nameToCall() => \App\Services\Telegram\Commands\MusicVK\StartCommand::class,
                    \App\Services\Telegram\Commands\MusicVK\AccessCommand::nameToCall() => \App\Services\Telegram\Commands\MusicVK\AccessCommand::class,
                ],
                /** Количество действий, если больше просить подписаться */
                'count_of_actions' => 99,
                /** Commands requiring subscription */
                'subscription_required' =>  [
                    \App\Services\Telegram\Commands\MusicVK\AccessCommand::nameToCall()
                ],
            ],

            'RemindBot' => [
                'token' => '7046905598:AAEomweb0SHpuCCio9ZXx2AervSmm7OQUGM',
                'simple_commands' => [
                    \App\Services\Telegram\Commands\RemindBot\StartCommand::nameToCall() => \App\Services\Telegram\Commands\RemindBot\StartCommand::class,
                    \App\Services\Telegram\Commands\RemindBot\AccessCommand::nameToCall() => \App\Services\Telegram\Commands\RemindBot\AccessCommand::class,
                ],
                /** Количество действий, если больше просить подписаться */
                'count_of_actions' => 99,
                /** Commands requiring subscription */
                'subscription_required' =>  [
                    \App\Services\Telegram\Commands\RemindBot\AccessCommand::nameToCall()
                ],
            ],

            'FreeEmailBot' => [
                'token' => '1989706728:AAH2kvC51JhJ-lhgWUo13167LcOXzOTCrW4',
                'simple_commands' => [
                    \App\Services\Telegram\Commands\FreeEmailBot\StartCommand::nameToCall() => \App\Services\Telegram\Commands\FreeEmailBot\StartCommand::class,
                    \App\Services\Telegram\Commands\FreeEmailBot\AccessCommand::nameToCall() => \App\Services\Telegram\Commands\FreeEmailBot\AccessCommand::class,
                ],
                /** Количество действий, если больше просить подписаться */
                'count_of_actions' => 99,
                /** Commands requiring subscription */
                'subscription_required' =>  [
                    \App\Services\Telegram\Commands\FreeEmailBot\AccessCommand::nameToCall()
                ],
            ],

            'FileCheckBot' => [
                'token' => '6455367526:AAHwmAR3eutpESazEamfuPt5c1yvHsvSowo',
                'simple_commands' => [
                    \App\Services\Telegram\Commands\FileCheckBot\StartCommand::nameToCall() => \App\Services\Telegram\Commands\FileCheckBot\StartCommand::class,
                    \App\Services\Telegram\Commands\FileCheckBot\AccessCommand::nameToCall() => \App\Services\Telegram\Commands\FileCheckBot\AccessCommand::class,
                ],
                /** Количество действий, если больше просить подписаться */
                'count_of_actions' => 99,
                /** Commands requiring subscription */
                'subscription_required' =>  [
                    \App\Services\Telegram\Commands\FileCheckBot\AccessCommand::nameToCall()
                ],
            ],

            'AdapterBot' => [
                'token' => '7492985910:AAHREm_WtYdJDg3Kk9ryMOivIrAGO_Fuhjc',
                'simple_commands' => [
                    StartCommand::nameToCall() => StartCommand::class,
                ],
                /** Количество действий, если больше просить подписаться */
                'count_of_actions' => 99,
                /** Commands requiring subscription */
                'subscription_required' =>  [
                ],
            ],

            'MillionaireBot' => [
                'token' => '7436546551:AAG2_DVNOz3-HDDAPdYLUISI67GNz3RZANU',
                'simple_commands' => [
                    \App\Services\Telegram\Commands\MillionaireBot\StartCommand::nameToCall() => \App\Services\Telegram\Commands\MillionaireBot\StartCommand::class,
                    \App\Services\Telegram\Commands\MillionaireBot\GameBarCommand::nameToCall() => \App\Services\Telegram\Commands\MillionaireBot\GameBarCommand::class,
                    \App\Services\Telegram\Commands\MillionaireBot\GameBarCommand::NEW_GAME  => \App\Services\Telegram\Commands\MillionaireBot\GameBarCommand::class,
                    \App\Services\Telegram\Commands\MillionaireBot\GameBarCommand::NEW_QUESTION => \App\Services\Telegram\Commands\MillionaireBot\GameBarCommand::class,
                    \App\Services\Telegram\Commands\MillionaireBot\GameBarCommand::ANSWER_A => \App\Services\Telegram\Commands\MillionaireBot\GameBarCommand::class,
                    \App\Services\Telegram\Commands\MillionaireBot\GameBarCommand::ANSWER_B => \App\Services\Telegram\Commands\MillionaireBot\GameBarCommand::class,
                    \App\Services\Telegram\Commands\MillionaireBot\GameBarCommand::ANSWER_C => \App\Services\Telegram\Commands\MillionaireBot\GameBarCommand::class,
                    \App\Services\Telegram\Commands\MillionaireBot\GameBarCommand::ANSWER_D => \App\Services\Telegram\Commands\MillionaireBot\GameBarCommand::class,
                ],
                /** Количество действий, если больше просить подписаться */
                'count_of_actions' => 99,
                /** Commands requiring subscription */
                'subscription_required' =>  [
                    \App\Services\Telegram\Commands\MillionaireBot\GameBarCommand::NEW_QUESTION
                ],
            ],

            'HeroBot' => [
                'token' => '7007457490:AAGGhd7zS-FRDe6Hyhk1bGM9sNTPG_MP4JQ',
                'simple_commands' => [
                    \App\Services\Telegram\Commands\HeroBot\StartCommand::nameToCall() => \App\Services\Telegram\Commands\HeroBot\StartCommand::class,
                    \App\Services\Telegram\Commands\HeroBot\TestBarCommand::nameToCall() => \App\Services\Telegram\Commands\HeroBot\TestBarCommand::class,
                    \App\Services\Telegram\Commands\HeroBot\TestBarCommand::ANSWER_ONE => \App\Services\Telegram\Commands\HeroBot\TestBarCommand::class,
                    \App\Services\Telegram\Commands\HeroBot\TestBarCommand::ANSWER_TWO => \App\Services\Telegram\Commands\HeroBot\TestBarCommand::class,
                    \App\Services\Telegram\Commands\HeroBot\TestBarCommand::ANSWER_THREE => \App\Services\Telegram\Commands\HeroBot\TestBarCommand::class,
                    \App\Services\Telegram\Commands\HeroBot\TestBarCommand::ANSWER_FOUR => \App\Services\Telegram\Commands\HeroBot\TestBarCommand::class,
                ],
                /** Количество действий, если больше просить подписаться */
                'count_of_actions' => 99,
                /** Commands requiring subscription */
                'subscription_required' =>  [
                    \App\Services\Telegram\Commands\HeroBot\TestBarCommand::ANSWER_ONE,
                    \App\Services\Telegram\Commands\HeroBot\TestBarCommand::ANSWER_TWO,
                    \App\Services\Telegram\Commands\HeroBot\TestBarCommand::ANSWER_THREE,
                    \App\Services\Telegram\Commands\HeroBot\TestBarCommand::ANSWER_FOUR,
                ],
            ],

        ]
    ],

    'basic_commands' => [
        BackCommand::nameToCall() => BackCommand::class,
        ConfirmSubscriptionCommand::nameToCall() => ConfirmSubscriptionCommand::class,
        UnknownCommand::nameToCall() => UnknownCommand::class,
        ErrorCommand::nameToCall() => ErrorCommand::class,
        BeginStartCommand::nameToCall() => BeginStartCommand::class,
        SubscribeCommand::nameToCall() => SubscribeCommand::class,
        UserAnswerCommand::nameToCall() => UserAnswerCommand::class,
        InactionCommand::nameToCall() => InactionCommand::class,
    ],

    'channel_subscriptions' =>  [
        [
            'id' => '@zapad_smi',
            'title' => 'Новости Запада ⚡',
            'url' => 'https://t.me/zapad_smi',
        ],
    ]
];
