<?php
namespace App\Services\Parser;
use App\Services\Telegram\DTO\Parser\Post;
use App\Services\Telegram\HttpClient\TGClientHelper;
use DiDom\Document;
use Illuminate\Support\Facades\Http;

class TheGuardianParser
{
    /**
     * Получить ссылки на посты, сегодня
     *
     * @param string $urlNews
     * @return array
     * @throws \DiDom\Exceptions\InvalidSelectorException
     */
    public function getUrlPosts(string $urlNews): array
    {
        date_default_timezone_set('Europe/London');

        $urlPages = [
            Http::get($urlNews . 'world/'. date('Y') . '/' . date('M') . '/' . date('d') .'/all')->body(),
            Http::get($urlNews . 'us-news/'. date('Y') . '/' . date('M') . '/' . date('d') .'/all')->body() // Новости из США
        ];

        $arrayHrefs = [];

        foreach ($urlPages as $document) {
            $document = new Document(
                $document
            );

            $posts = $document->find('.fc-item__container');

            foreach ($posts as $post) {

                $href = $post->first('a')->getAttribute('href');

                if (
                    str_contains($href, '/live/') or
                    str_contains($href, '/video/') or
                    str_contains($href, '/gallery/')
                ) {
                    continue;
                } else {
                    $arrayHrefs[] = $href;
                }
            }
        }

        return $arrayHrefs;
    }

    /**
     * @param string $urlNews
     * @param array $urlsNotInclude
     * @return array
     */
    public function getPosts(string $urlNews, array $urlsNotInclude = []): array
    {
        $arrayPosts = [];
        foreach ($this->getUrlPosts($urlNews) as $href) {
            if (!in_array($href, $urlsNotInclude)) {
//                info($href);

                $document = Http::get($href)->body();

                $document = new Document($document);

                if ($document->has('.dcr-u0152o')) {
                    $title = $document->first('.dcr-u0152o')->text();
                } elseif ($document->has('.dcr-1w6uej9')) {
                    $title = $document->first('.dcr-1w6uej9')->text();
                } else {
                    TGClientHelper::info("TheGuardianParser: html элемент заголовок не найден\nСсылка на статью: $href");
                    continue;
                }

                $paragraphs = $document->first('#maincontent')->find('p');
                if (array_key_exists(0, $paragraphs) and array_key_exists(1, $paragraphs)) {
                    $text = $paragraphs[0]->text() . "\n\n" . $paragraphs[1]->text();
                } else continue;

//                for ($i = 0; $i <= 1; $i++) {
//                    $text .= $paragraphs[$i]->text() . "\n\n";
//                }
                $arrayPosts[] = new Post(
                    $title,
                    $href,
                    $text,
                    $document->first('picture')->first('img')->getAttribute('src')
                );
            }
        }
        return $arrayPosts;
    }
}
