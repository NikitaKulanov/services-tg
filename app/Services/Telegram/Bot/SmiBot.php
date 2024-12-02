<?php

namespace App\Services\Telegram\Bot;

use App\Exceptions\TGApiException;
use App\Services\Parser\TheGuardianParser;
use App\Services\Telegram\DTO\Parser\Post;
use App\Services\Telegram\HttpClient\TGClientHelper;
use App\Services\Telegram\Payloads\PhotoPayload;
use App\Services\Translator\GoogleTranslateForFree;
use Illuminate\Support\Facades\Storage;
use Throwable;

class SmiBot extends Bot
{
    /**
     * @var array
     */
    private array $configBot;

    /**
     * @var array
     */
    private array $storage;

    /**
     * @var string
     */
    public readonly string $storageFile;

    /**
     * @param TheGuardianParser $theGuardianParser
     * @param GoogleTranslateForFree $googleTranslate
     * @param string $botName
     * @throws Throwable
     */
    public function __construct(
        public TheGuardianParser      $theGuardianParser,
        public GoogleTranslateForFree $googleTranslate,
        string                        $botName,
    )
    {
        parent::__construct($botName);
        $this->configBot = $this->config['settings']['mini_bots']['SMI'];
        $this->storageFile = $this->configBot['storage'];
        $this->storage = json_decode(Storage::get($this->storageFile), true);
    }

    /**
     * Store in storage
     *
     * @param array $payload
     */
    private function saveToStorageFile(array $payload)
    {
        $this->storage = array_merge($this->storage, $payload);
        Storage::put($this->storageFile, json_encode($this->storage));
    }

    /**
     * Get value from storage
     *
     * @param array|string $payload
     * @param null $default
     * @return array|string|int|bool|null
     */
    private function getFromStorageFile(array|string $payload, $default = null): array|string|int|null|bool
    {
        if (is_string($payload)) {
            return $this->storage[$payload] ?? $default;
        } else {
            $array = [];
            foreach ($payload as $item) {
                if (array_key_exists($item, $this->storage)) {
                    $array[$item] = $this->storage[$item];
                }
            }
            return $array !== [] ? $array : $default;
        }
    }

    public function sendPost(Post $post) {
        $this->sendPhoto(
            PhotoPayload::create(
                $this->configBot['id_channel'],
                $post->urlImg
            )
                ->setCaption(
                    "⚡ <b>{$this->translate($post->title)}</b>\n\n" .
                    $this->translate($post->text) . "\n\n" .
                    "👉 <a href='$post->url'>Читать статью полностью</a>\n\n"
//                            . "🤝 <a href='{$this->config['settings']['url_channel']}'>Новости Запада ⚡ Подписаться</a>"
                )
            ->deleteKeyboard()
        );
    }

    public function updateNews()
    {
        $urlsPosts = [];
        foreach (
            $this->theGuardianParser->getPosts(
                $this->configBot['url_news'], $this->getFromStorageFile('url_not_include', [])
            ) as $post
        ) {
            /** @var Post $post */
            try {
                if ($post->title !== '') {
                    $this->sendPost($post);
                } else {
                    TGClientHelper::info(
                        "TheGuardianParser: html элемент заголовок не найден\nСсылка на статью: $post->url"
                    );
                }

            } catch (TGApiException $exception) {
                $response = json_decode($exception->responseJson, true);
                if ($retryAfter = $response['parameters']['retry_after'] ?? null) {
                    sleep($retryAfter + 1);
                    $this->sendPost($post);
                } else {
                    $exception->report();
                    $exception->render();
                }
            }

            $urlsPosts[] = $post->url;
//            break;
            sleep(1);
        }

        if (date('Y M d') === $this->getFromStorageFile('date')) {
            $this->saveToStorageFile([
                'url_not_include' => array_merge($this->getFromStorageFile('url_not_include', []), $urlsPosts)
            ]);
        } else {
            $this->saveToStorageFile([
                'date' => date('Y M d'),
                'url_not_include' => $urlsPosts
            ]);
        }
    }

    /**
     * @param $payload
     * @return array|string
     */
    private function translate($payload): array|string
    {
        $source = 'en';
        $target = 'ru';
        $attempts = 5;

        return $this->googleTranslate->translate($source, $target, $payload, $attempts);
    }
}
