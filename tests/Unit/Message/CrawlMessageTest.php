<?php

namespace App\Tests\Unit\Message;

use App\Message\CrawlMessage;
use PHPUnit\Framework\TestCase;

/** @coversDefaultClass \App\Message\CrawlMessage */
class CrawlMessageTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::getUrl
     */
    public function testGetters()
    {
        $url = 'dummy-url.com';

        $message = new CrawlMessage($url);
        $this->assertEquals($url, $message->getUrl());
    }
}
