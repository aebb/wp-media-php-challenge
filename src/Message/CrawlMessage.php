<?php

namespace App\Message;

class CrawlMessage
{
    protected ?string $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }
}
