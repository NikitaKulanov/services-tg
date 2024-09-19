<?php
namespace App\Services\Telegram\DTO\Parser;
class Post
{
    public function __construct(
        public readonly string $title,
        public readonly string $url,
        public readonly string $text,
        public readonly string $urlImg,
    )
    {
    }
}
